<?php

namespace App\Http\Controllers\be;

use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    public function index()
    {
        $dataHero = Hero::all();
        return view('be.pages.hero.index', compact('dataHero'));
    }

    public function create()
    {
        return view('be.pages.hero.create');
    }

    public function preview()
    {
        $dataHero = Hero::where('status', 1)->get();
        return view('be.pages.hero.preview', compact('dataHero'));
    }

    public function edit($id)
    {
        $data = Hero::find($id);
        if (!$data) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'title_hero.*' => 'nullable|string|max:255',
            'description.*' => 'nullable|string',
            'hero_image.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ], [
            'title_hero.*.max' => 'Judul hero maksimal 255 karakter',
            'hero_image.*.image' => 'File harus berupa gambar',
            'hero_image.*.mimes' => 'Gambar harus dalam format jpeg, png, jpg, atau webp',
            'hero_image.*.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $heroData = [];

            foreach ($request->title_hero as $key => $title) {
                $description = $request->description[$key] ?? null;
                $heroImage = $request->file('hero_image')[$key] ?? null;

                // Abaikan entri yang semuanya kosong
                if (empty($title) && empty($description) && !$heroImage) {
                    continue;
                }

                $heroEntry = [
                    'title_hero' => $title,
                    'description' => $description,
                    'hero_image' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Proses upload gambar jika ada
                if ($heroImage) {
                    $filename = 'hero_' . uniqid() . '.' . $heroImage->getClientOriginalExtension();
                    $path = $heroImage->storeAs('hero_images', $filename, 'public');
                    $heroEntry['hero_image'] = $path;
                }

                $heroData[] = $heroEntry;
            }

            if (empty($heroData)) {
                return back()->with('error', 'Tidak ada data yang valid untuk disimpan.')
                    ->withInput();
            }

            // Masukkan data ke database
            Hero::insert($heroData);

            DB::commit();

            return redirect()->route('be/hero.index')
                ->with('success', 'Berhasil menambahkan ' . count($heroData) . ' hero baru');
        } catch (\Exception $e) {
            DB::rollBack();

            foreach ($heroData as $hero) {
                if ($hero['hero_image']) {
                    Storage::disk('public')->delete($hero['hero_image']);
                }
            }

            return back()->with('error', 'Gagal menyimpan data. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title_hero' => 'required|string|max:255',
            'description' => 'required|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $hero = Hero::findOrFail($id);

            // Proses upload gambar
            if ($request->hasFile('hero_image')) {
                $newImagePath = $request->file('hero_image')->store('hero_images', 'public');

                // Hapus gambar lama jika ada
                if ($hero->hero_image) {
                    Storage::disk('public')->delete($hero->hero_image);
                }

                $hero->hero_image = $newImagePath;
            }

            // Update 
            $hero->title_hero = $request->input('title_hero');
            $hero->description = $request->input('description');
            $hero->save();

            DB::commit();
            return redirect()->route('be/hero.index')->with('success', 'Hero successfully updated!');
        } catch (\Exception $e) {
            DB::rollBack();

            // hapus gambar jika di rollback
            if (isset($newImagePath)) {
                Storage::disk('public')->delete($newImagePath);
            }

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Cari record Hero berdasarkan ID
            $hero = Hero::findOrFail($id);

            // Periksa apakah hero memiliki gambar terkait dan hapus gambar dari penyimpanan
            if ($hero->hero_image && Storage::disk('public')->exists($hero->hero_image)) {
                // Hapus gambar hero dari penyimpanan
                Storage::disk('public')->delete($hero->hero_image);
            }

            // Hapus record Hero dari database
            $hero->delete();

            DB::commit();

            // Menampilkan pesan sukses
            session()->flash('success', 'Hero Berhasil Dihapus');
            return redirect()->route('be/hero.index');
        } catch (\Exception $e) {
            DB::rollBack();

            // Menampilkan pesan error jika terjadi kesalahan
            return redirect()->back()->with('error', 'Gagal menghapus hero: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            DB::beginTransaction();

            // hitung hero aktif
            $activeHeroCount = Hero::where('status', 1)->count();

            // Cari hero berdasarkan id
            $hero = Hero::findOrFail($id);

            // jika mengaktifkan lebih dari 5 hero aktif
            if ($hero->status == 0 && $activeHeroCount >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 5 hero dapat diaktifkan'
                ], 400);
            }

            // Toggle status
            $hero->status = !$hero->status;
            $hero->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => $hero->status,
                'message' => $hero->status ? 'Hero diaktifkan' : 'Hero dinonaktifkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log::error('Toggle Status Error: ' . $e->getMessage());
            // Log::error('Error Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}
