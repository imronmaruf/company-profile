<?php

namespace App\Http\Controllers\be;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserSettingAccountController extends Controller
{
    public function index()
    {
        $account = auth()->user();
        return view('be.pages.user-setting-account.index', compact('account'));
    }

    public function edit()
    {
        $account = auth()->user();
        return view('be.pages.user-setting-account.edit', compact('account'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update profil
            $user->name = $request->name;
            $user->email = $request->email;

            // Jika ingin mengganti password
            if ($request->filled('new_password')) {
                // Periksa password saat ini
                if (!Hash::check($request->current_password, $user->password)) {
                    throw ValidationException::withMessages([
                        'current_password' => ['Password saat ini salah']
                    ]);
                }
                $user->password = Hash::make($request->new_password);
            }
            // Simpan perubahan
            $user->save();
            // Commit transaksi
            DB::commit();
            // Redirect ke halaman profil
            return redirect()->route('be/account/setting.index')
                ->with('success', 'Profil berhasil diperbarui');
        } catch (ValidationException $e) {
            // Rollback transaksi jika validasi gagal
            DB::rollBack();

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // Rollback transaksi untuk error lain
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil')
                ->withInput();
        }
    }
}
