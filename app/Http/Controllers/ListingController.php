<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Listing;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $listings = Listing::where('is_active', true)
            ->with('tags')
            ->latest()
            ->get();

        $tags = Tag::orderBy('name')
            ->get();

        if ($request->filled('s')) {
            $query = strtolower($request->get('s'));
            $listings = $listings->filter(function ($listing) use ($query) {
                $searchableFields = [
                    'title',
                    'company',
                    'location',
                    'content',
                ];

                foreach ($searchableFields as $field) {
                    if (Str::contains(strtolower($listing->$field), $query)) {
                        return true;
                    }
                }

                return false;
            });
        }

        if ($request->filled('tag')) {
            $tag = $request->get('tag');
            $listings = $listings->filter(function ($listing) use ($tag) {
                return $listing->tags->contains('slug', $tag);
            });
        }

        return view('listings.index', compact('listings', 'tags'));
    }

    public function show(Listing $listing, Request $request)
    {
        return view('listings.show', compact('listing'));
    }

    public function apply(Listing $listing, Request $request)
    {
        $listing->clicks()->create([
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
        ]);

        return redirect()->to($listing->apply_link);
    }

    public function create()
    {
        return view('listings.create');
    }

}
