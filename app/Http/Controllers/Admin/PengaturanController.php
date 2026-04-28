<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = Pengaturan::all()->pluck('value', 'key');
        return view('admin.pengaturan.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'app_logo', 'app_favicon']);

        // Handle file uploads
        if ($request->hasFile('app_logo')) {
            $logo = $request->file('app_logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/settings'), $logoName);
            $data['app_logo'] = 'uploads/settings/' . $logoName;
        }

        if ($request->hasFile('app_favicon')) {
            $favicon = $request->file('app_favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('uploads/settings'), $faviconName);
            $data['app_favicon'] = 'uploads/settings/' . $faviconName;
        }

        foreach ($data as $key => $value) {
            Pengaturan::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
}
