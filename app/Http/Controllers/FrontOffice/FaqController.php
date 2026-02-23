<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->get();
        $categories = Faq::active()->distinct()->pluck('category')->filter()->values();
        
        return view('front-office.faq.index', compact('faqs', 'categories'));
    }



}
