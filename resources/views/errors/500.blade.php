@extends('layouts.admin.skeleton')
@section('title', __('404 Error'))

@section('content')
<div style="text-align: center; margin-top: 50px;">
  <i class="ph ph-warning-circle" style="font-size: 100px; color: #ff6f61;"></i>
  <h1>500</h1>
  <h4>Server Error</h4>
  <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: #007bff;">Go back to home</a>
</div>
@endsection