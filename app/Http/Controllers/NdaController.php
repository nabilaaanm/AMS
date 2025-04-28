<?php

namespace App\Http\Controllers;

use App\Models\Nda;
use Illuminate\Http\Request;
use PDF; // Pastikan Anda mengimpor PDF

class NdaController extends Controller
{
    public function index()
    {
        $ndas = Nda::all(); // Ambil semua data NDA
        return view('menu.nda.nda', compact('ndas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nda_name' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'bagian' => 'nullable|string|max:255',
        ]);

        $tanggal = now(); // Tanggal sekarang
        $tanggal_berlaku = now()->addMonths(3); // Tanggal berlaku 3 bulan dari sekarang

        $nda = new Nda();
        $nda->name = $request->nda_name;
        $nda->no_ktp = $request->no_ktp;
        $nda->alamat = $request->alamat;
        $nda->perusahaan = $request->perusahaan;
        $nda->region = $request->region;
        $nda->bagian = $request->bagian;
        $nda->tanggal = $tanggal;
        $nda->tanggal_berlaku = $tanggal_berlaku;
        $nda->signature = $request->signature;
        $nda->save();

        // Tentukan tipe NDA berdasarkan ada tidaknya data perusahaan
        $type = $nda->perusahaan ? 'eksternal' : 'internal';
        
        // Generate PDF sesuai tipe
        $pdf = PDF::loadView('menu.nda.nda_' . $type . '_pdf', compact('nda'));
        $pdf->save(public_path('pdf/nda_' . $type . '_' . $nda->id . '.pdf'));

        return redirect()->route('nda.index')->with('success', 'NDA berhasil ditambahkan.');
    }

    public function download($id)
    {
        $nda = Nda::findOrFail($id);
        
        // Tentukan tipe NDA berdasarkan ada tidaknya data perusahaan
        $type = $nda->perusahaan ? 'eksternal' : 'internal';
        
        // Generate PDF sesuai tipe
        $pdf = PDF::loadView('menu.nda.nda_' . $type . '_pdf', compact('nda'));
        return $pdf->download('nda_' . $type . '_' . $nda->id . '.pdf');
    }

    public function update(Request $request, Nda $nda)
    {
        // Implementasi update
    }

    public function destroy(Nda $nda)
    {
        // Implementasi delete
    }
}
