<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
        public function index()
        {
            $jabatans = Jabatan::latest()->paginate(10);
            return view('jabatan.index', compact('jabatans'));
        }

        public function create()
        {
            return view('jabatan.create');
        }

        public function store(Request $request)
        {
            $request->validate(['nama_jabatan' => 'required|string|max:100|unique:jabatan']);
            Jabatan::create($request->all());
            return redirect()->route('jabatan.index')->with('success', 'Jabatan baru berhasil ditambahkan.');
        }

        public function edit(Jabatan $jabatan)
        {
            return view('jabatan.edit', compact('jabatan'));
        }

        public function update(Request $request, Jabatan $jabatan)
        {
            $request->validate(['nama_jabatan' => 'required|string|max:100|unique:jabatan,nama_jabatan,' . $jabatan->id]);
            $jabatan->update($request->all());
            return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
        }

        public function destroy(Jabatan $jabatan)
        {
            // Cek jika ada pegawai yang masih menggunakan jabatan ini
            if ($jabatan->pegawai()->count() > 0) {
                return back()->with('error', 'Jabatan tidak bisa dihapus karena masih digunakan oleh pegawai.');
            }
            $jabatan->delete();
            return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
        }
}
