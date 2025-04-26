<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            $regions = Region::all();
            
            // Debug untuk memastikan ada data regions
            \Log::info('Regions data:', $regions->toArray());
            
            return view('menu.user', compact('users', 'regions'));
        } catch (\Exception $e) {
            \Log::error('Error in index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $regions = Region::all();
        return view('menu.user_create', compact('regions'));
    }

    public function store(Request $request)
    {
        try {
            // Debug: Log semua data yang diterima
            \Log::info('Data yang diterima:', $request->all());

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'mobile_number' => 'required|string|max:15|regex:/^[0-9]+$/',
                'region' => 'required|string|max:255',
                'role' => 'required|in:1,2,3'
            ]);

            // Debug: Log data yang sudah divalidasi
            \Log::info('Data yang divalidasi:', $validated);

            try {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'mobile_number' => $request->mobile_number,
                    'region' => $request->region,
                    'role' => $request->role
                ]);

                // Debug: Log user yang berhasil dibuat
                \Log::info('User berhasil dibuat:', $user->toArray());

                return response()->json([
                    'success' => true,
                    'message' => 'User berhasil ditambahkan'
                ]);
            } catch (\Exception $e) {
                // Debug: Log error saat create user
                \Log::error('Error saat create user:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan user: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Debug: Log error validasi
            \Log::error('Validation error:', $e->errors());

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Debug: Log error umum
            \Log::error('Error umum:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(User $user)
    {
        try {
            $editUser = $user;
            $users = User::where('id', '!=', $user->id)->get();
            $regions = Region::all();
            return view('menu.user', compact('users', 'editUser', 'regions'));
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan atau terjadi kesalahan.');
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'mobile_number' => 'required|string|max:15|regex:/^[0-9]+$/',
                'region' => 'required|string|max:255',
                'role' => 'required|in:1,2,3'
            ]);

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'region' => $request->region,
                'role' => $request->role
            ];

            if ($request->filled('password')) {
                $request->validate(['password' => 'string|min:8']);
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating user ' . $user->id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            // Debug: Log user yang akan dihapus
            \Log::info('Mencoba menghapus user:', $user->toArray());
            
            $user->delete();
            
            // Debug: Log sukses
            \Log::info('User berhasil dihapus');
            
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            // Debug: Log error detail
            \Log::error('Error deleting user:', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ], 500);
        }
    }
}
