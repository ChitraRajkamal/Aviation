<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        return view('frontend.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = auth()->user();
        if($user->status == -1){
            Auth::logout();
            return redirect()->route('login')->with('error', 'Invalid username or password.');
        }
        if(!$user->status){
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }
        if($user->isAdmin() || $user->isOrganization()){
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route(route: 'dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if($user->google_token){ //Google OAuth
			try {
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL,"https://oauth2.googleapis.com/revoke");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,
					http_build_query(array('token' => $user->google_token))
				);
				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch);

                $user->google_token = '';
                $user->save();
			} catch (\Throwable $th) {
				//throw $th;
			}
		}

        if($user->facebook_token){
			try {
				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://graph.facebook.com/v22.0/me/permissions?access_token=$user->facebook_token',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'DELETE',
				));

				$response = curl_exec($curl);

				curl_close($curl);

                $user->facebook_token = '';
                $user->save();
			} catch (\Throwable $th) {
				//throw $th;
			}
		}

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
