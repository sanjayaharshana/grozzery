<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSetting;
use App\Models\Banner;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSettingsController extends Controller
{
    public function index()
    {
        $heroSettings = HomeSetting::getByGroup('hero');
        $sectionSettings = HomeSetting::getByGroup('sections');
        $banners = Banner::orderBy('sort_order')->get();
        $promotions = Promotion::orderBy('sort_order')->get();

        return view('admin.home-settings', compact('heroSettings', 'sectionSettings', 'banners', 'promotions'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:500',
            'hero_button_text' => 'required|string|max:100',
            'hero_button_url' => 'required|string|max:255',
            'featured_title' => 'required|string|max:255',
            'bestseller_title' => 'required|string|max:255',
            'categories_title' => 'required|string|max:255',
            'vendors_title' => 'required|string|max:255',
        ]);

        $settings = [
            'hero_title', 'hero_subtitle', 'hero_button_text', 'hero_button_url',
            'featured_title', 'bestseller_title', 'categories_title', 'vendors_title'
        ];

        foreach ($settings as $setting) {
            HomeSetting::setValue($setting, $request->$setting);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:flash_sale,promotion,announcement',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:100',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->back()->with('success', 'Banner created successfully!');
    }

    public function updateBanner(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:flash_sale,promotion,announcement',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:100',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->back()->with('success', 'Banner updated successfully!');
    }

    public function deleteBanner(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();

        return redirect()->back()->with('success', 'Banner deleted successfully!');
    }

    public function storePromotion(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:shipping,discount,offer',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'minimum_order' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        Promotion::create($request->all());

        return redirect()->back()->with('success', 'Promotion created successfully!');
    }

    public function updatePromotion(Request $request, Promotion $promotion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:shipping,discount,offer',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'minimum_order' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $promotion->update($request->all());

        return redirect()->back()->with('success', 'Promotion updated successfully!');
    }

    public function deletePromotion(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->back()->with('success', 'Promotion deleted successfully!');
    }
}
