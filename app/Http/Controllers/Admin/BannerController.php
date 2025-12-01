<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::ordered()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        $bannersCount = Banner::active()->count();
        return view('admin.banners.create', compact('bannersCount'));
    }

    public function store(Request $request)
    {
        $activeBannersCount = Banner::active()->count();

        if ($activeBannersCount >= 10 && $request->is_active) {
            return back()->with('error', 'Maksimal 10 banner aktif. Nonaktifkan banner lain terlebih dahulu.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/banners'), $imageName);
            $validated['image_path'] = 'uploads/banners/' . $imageName;
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner)
    {
        $activeBannersCount = Banner::active()->where('id', '!=', $banner->id)->count();
        return view('admin.banners.edit', compact('banner', 'activeBannersCount'));
    }

    public function update(Request $request, Banner $banner)
    {
        $activeBannersCount = Banner::active()->where('id', '!=', $banner->id)->count();

        if ($activeBannersCount >= 10 && $request->is_active && !$banner->is_active) {
            return back()->with('error', 'Maksimal 10 banner aktif. Nonaktifkan banner lain terlebih dahulu.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image_path && file_exists(public_path($banner->image_path))) {
                unlink(public_path($banner->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/banners'), $imageName);
            $validated['image_path'] = 'uploads/banners/' . $imageName;
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diupdate.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path && file_exists(public_path($banner->image_path))) {
            unlink(public_path($banner->image_path));
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil dihapus.');
    }
}
