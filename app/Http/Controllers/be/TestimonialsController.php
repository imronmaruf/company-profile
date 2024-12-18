<?php

namespace App\Http\Controllers\be;

use App\Models\Testimonials;
use Illuminate\Http\Request;
use PHPUnit\Event\Code\Test;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TestimonialsController extends Controller
{
    public function index()
    {

        $dataTestimonials = Testimonials::all();
        return view('be.pages.testimonials.index', compact('dataTestimonials'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'name' => 'required|min:3',
            'position' => 'nullable',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'testimonial' => 'required',
        ]);

        try {
            // Memulai transaksi database
            DB::beginTransaction();

            $newTestimonial = new Testimonials();
            $newTestimonial->name = $data['name'];
            $newTestimonial->position = $data['position'];
            $newTestimonial->testimonial = $data['testimonial'];

            if ($request->hasFile('photo_path')) {
                $data['photo_path'] = $request->file('photo_path')->store('photo_testimonials', 'public');
            }

            $newTestimonial->photo_path = $data['photo_path'];

            $newTestimonial->save();

            DB::commit();;
            return redirect()->route('be/testimonials.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $data = Testimonials::find($id);
        if (!$data) {
            return response()->json(['error' => 'Testimonials not found'], 404);
        }
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|min:3',
            'position' => 'nullable',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'testimonial' => 'required',
        ]);

        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Mencari data testimonial berdasarkan ID
            $testimonial = Testimonials::findOrFail($id);

            // Cek apakah ada foto yang diupload
            if ($request->hasFile('photo_path')) {
                // Hapus foto lama jika ada
                if ($testimonial->photo_path && Storage::disk('public')->exists($testimonial->photo_path)) {
                    Storage::disk('public')->delete($testimonial->photo_path);
                }

                // Simpan foto baru
                $testimonial->photo_path = $request->file('photo_path')->store('photo_testimonials', 'public');
            }

            // Update data
            $testimonial->name = $request->input('name');
            $testimonial->position = $request->input('position');
            $testimonial->testimonial = $request->input('testimonial');

            // Simpan perubahan
            $testimonial->save();

            // Commit transaksi
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('be/testimonials.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Menghapus gambar jika transaksi rollback
            if (isset($testimonial->photo_path)) {
                Storage::disk('public')->delete($testimonial->photo_path);
            }

            // Menampilkan pesan error
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Cari record testimonials berdasarkan ID
            $testimonials = Testimonials::findOrFail($id);

            // Periksa apakah testimonials memiliki gambar terkait dan hapus gambar dari penyimpanan
            if ($testimonials->photo_path && Storage::disk('public')->exists($testimonials->photo_path)) {
                // Hapus gambar testimonials dari penyimpanan
                Storage::disk('public')->delete($testimonials->photo_path);
            }
            // Hapus record testimonials dari database
            $testimonials->delete();
            DB::commit();
            // Menampilkan pesan sukses
            session()->flash('success', 'Testimonial Berhasil Dihapus');
            return redirect()->route('be/testimonials.index');
        } catch (\Exception $e) {
            DB::rollBack();

            // Menampilkan pesan error jika terjadi kesalahan
            return redirect()->back()->with('error', 'Gagal menghapus data Tim: ' . $e->getMessage());
        }
    }
}
