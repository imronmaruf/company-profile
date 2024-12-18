<?php

namespace App\Http\Controllers\be;

use App\Models\Teams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $dataTeams = Teams::all();
        return view('be.pages.teams.index', compact('dataTeams'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'name' => 'required|min:3|unique:teams',
            'position' => 'required',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Memulai transaksi database
            DB::beginTransaction();

            $newTeams = new Teams();
            $newTeams->name = $data['name'];
            $newTeams->position = $data['position'];

            if ($request->hasFile('photo_path')) {
                $data['photo_path'] = $request->file('photo_path')->store('photo_teams', 'public');
            }

            $newTeams->photo_path = $data['photo_path'];

            $newTeams->save();

            DB::commit();;
            return redirect()->route('be/teams.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $data = Teams::find($id);
        if (!$data) {
            return response()->json(['error' => 'Teams not found'], 404);
        }
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|min:3',
            'position' => 'required',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Mencari data team berdasarkan ID
            $teams = Teams::findOrFail($id);

            // Cek apakah ada foto yang diupload
            if ($request->hasFile('photo_path')) {
                // Hapus foto lama jika ada
                if ($teams->photo_path && Storage::disk('public')->exists($teams->photo_path)) {
                    Storage::disk('public')->delete($teams->photo_path);
                }

                // Simpan foto baru
                $teams->photo_path = $request->file('photo_path')->store('photo_teams', 'public');
            }

            // Update nama dan posisi
            $teams->name = $request->input('name');
            $teams->position = $request->input('position');

            // Simpan perubahan
            $teams->save();

            // Commit transaksi
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('be/teams.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Menghapus gambar jika transaksi rollback
            if (isset($teams->photo_path)) {
                Storage::disk('public')->delete($teams->photo_path);
            }

            // Menampilkan pesan error
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Cari record Teams berdasarkan ID
            $teams = Teams::findOrFail($id);

            // Periksa apakah teams memiliki gambar terkait dan hapus gambar dari penyimpanan
            if ($teams->photo_path && Storage::disk('public')->exists($teams->photo_path)) {
                // Hapus gambar teams dari penyimpanan
                Storage::disk('public')->delete($teams->photo_path);
            }
            // Hapus record teams dari database
            $teams->delete();
            DB::commit();
            // Menampilkan pesan sukses
            session()->flash('success', 'Tim Berhasil Dihapus');
            return redirect()->route('be/teams.index');
        } catch (\Exception $e) {
            DB::rollBack();

            // Menampilkan pesan error jika terjadi kesalahan
            return redirect()->back()->with('error', 'Gagal menghapus data Tim: ' . $e->getMessage());
        }
    }
}
