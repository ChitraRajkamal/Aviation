<?php

namespace App\Http\Controllers;

use App\Models\FaqCategory;

class FaqController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::where('status', 1)
            ->withCount([
                'faqs' => function ($q) {
                    $q->where('status', 1);
                }
            ])
            ->orderBy('sort_order')
            ->get();

        return view('frontend.faqs.index', compact('categories'));
    }

    public function show(FaqCategory $category)
    {
        $categories = FaqCategory::where('status',1)
            ->where('id','!=',$category->id)
            ->withCount([
                'faqs' => function ($q) {
                    $q->where('status', 1);
                }
            ])
            ->orderBy('sort_order')
            ->get();
        $faqs = $category->faqs()
            ->where('status', 1)
            ->get();

        return view('frontend.faqs.show', compact('category', 'faqs', 'categories'));
    }
}
