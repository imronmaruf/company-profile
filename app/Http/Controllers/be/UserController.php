<?php

namespace App\Http\Controllers\be;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index()
    {
        $dataUser = User::all();
        return view('be.pages.data-user.index', compact('dataUser'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'name' => 'required|min:3|unique:users',
            // 'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
        ]);

        try {
            // Memulai transaksi database
            DB::beginTransaction();

            $newUser = new User();
            $newUser->name = $data['name'];
            // $newUser->username = $data['username'];
            $newUser->email = $data['email'];
            $newUser->password = Hash::make($request->username);
            $newUser->save();

            DB::commit();;
            return redirect()->route('be/user.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $data = User::find($id);
        if (!$data) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            // 'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            // 'role' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $user = User::findOrFail($id);

            if ($request->email != $user->email) {
                $existingUser = User::where('id', '!=', $user->id)->where('email', $request->email)->first();
                if ($existingUser) {
                    return redirect()->back()->with('error', 'Email sudah digunakan');
                }
            }

            $user->name = $data['name'];
            // $user->username = $data['username'];
            $user->email = $data['email'];
            // $user->role = $data['role'];
            $user->save();

            DB::commit();
            Session::flash('success', 'Berhasil update data');
            return redirect()->route('be/user.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            Session()->flash('success', 'User  Berhasil Dihapus');
            return redirect()->route('be/user.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
