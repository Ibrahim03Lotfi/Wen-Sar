<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use App\Models\Category;
use App\Models\Business;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $governorates = Governorate::with('districts')->get();
        $categories = Category::whereNull('parent_id')->get();
        $featuredBusinesses = Business::where('is_featured', true)
            ->with(['category', 'subArea'])
            ->orderBy('featured_rank')
            ->take(6)
            ->get();

        return view('home', compact('governorates', 'categories', 'featuredBusinesses'));
    }

    public function categories()
    {
        $categories = Category::withCount('businesses')->get();
        return view('categories', compact('categories'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10|max:1000',
        ]);

        // Here you would typically send an email or store the contact form submission
        // For now, we'll just redirect back with a success message

        return back()->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
    }
}
