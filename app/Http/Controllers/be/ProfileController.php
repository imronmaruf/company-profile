<?php

namespace App\Http\Controllers\be;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::first(); // Pastikan ini benar-benar mengembalikan null

        // Tambahkan debugging
        Log::info('Profile in controller:', ['profile' => $profile]);
        // $profile = Profile::first(); // Ambil satu data saja
        return view('be.pages.profile.index', compact('profile'));
    }

    public function edit()
    {
        $profile = Profile::first();  // Ambil satu data pertama
        return view('be.pages.profile.edit', compact('profile'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'maps' => 'nullable|string',
            'instagram_link' => 'nullable|string',
            'whatsapp_link' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Persiapan data untuk disimpan
            $data = $request->only(['company_name', 'address', 'maps', 'instagram_link', 'whatsapp_link', 'phone_number', 'email', 'description']);

            // Cek apakah ada file logo yang diunggah
            if ($request->hasFile('logo_path')) {
                $data['logo_path'] = $request->file('logo_path')->store('logo', 'public');
            }

            // Simpan data ke tabel profiles
            Profile::create($data);

            // Commit transaksi jika berhasil
            DB::commit();
            // Set pesan sukses di session
            Session::flash('success', 'Profile added successfully!');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();
            // Set pesan error di session   z
            Session::flash('error', 'Failed to add profile. Please try again.');
        }
        // Redirect kembali ke halaman sebelumnya
        return redirect()->back();
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'company_name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'maps' => 'nullable|string',
                'instagram_link' => 'nullable|string',
                'whatsapp_link' => 'nullable|string',
                'phone_number' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'description' => 'nullable|string',
                'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            DB::beginTransaction();

            $profile = Profile::first();

            $data = $request->only([
                'company_name',
                'address',
                'maps',
                'instagram_link',
                'whatsapp_link',
                'phone_number',
                'email',
                'description'
            ]);

            // Proses upload logo
            if ($request->hasFile('logo_path')) {
                // Hapus file lama jika ada
                if ($profile->logo_path) {
                    Storage::disk('public')->delete($profile->logo_path);
                }

                // Simpan file baru
                $logoPath = $request->file('logo_path')->store('logo', 'public');
                $data['logo_path'] = $logoPath;
            }
            // Update profile
            $profile->update($data);
            DB::commit();
            Session::flash('success', 'Profile updated successfully.');
            return redirect()->route('be/profile.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Failed to update profile: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
