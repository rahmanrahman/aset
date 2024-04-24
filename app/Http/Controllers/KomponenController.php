<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Komponen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KomponenController extends Controller
{
    public function index()
    {
        $items = Komponen::with('jenis')->orderBy('nama', 'ASC')->get();
        return view('pages.komponen.index', [
            'title' => 'Data Komponen',
            'items' => $items
        ]);
    }

    public function create()
    {
        $data_jenis = Jenis::orderBy('nama', 'ASC')->get();
        return view('pages.komponen.create', [
            'title' => 'Tambah Komponen',
            'data_jenis' => $data_jenis
        ]);
    }

    public function store(Request $request)
    {
        request()->validate([
            'nama' => ['required',   Rule::unique('komponen')->where(function ($query) use ($request) {
                return $query->where('jenis_id', $request->jenis_id);
            }),],
            'jenis_id' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->all();
            Komponen::create($data);
            DB::commit();
            return redirect()->route('komponen.index')->with('success', 'Komponen berhasil ditambahkan.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('komponen.index')->with('error', $th->getMessage());
        }
    }

    public function edit($uuid)
    {
        $item = Komponen::where('uuid', $uuid)->firstOrFail();
        $data_jenis = Jenis::orderBy('nama', 'ASC')->get();
        return view('pages.komponen.edit', [
            'title' => 'Edit Komponen',
            'item' => $item,
            'data_jenis' => $data_jenis
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $item = Komponen::where('uuid', $uuid)->firstOrFail();
        request()->validate([
            'nama' => ['required',   Rule::unique('komponen')->where(function ($query) use ($request, $item) {
                return $query->where('jenis_id', $request->jenis_id)->where('id', '!=', $item->id);
            }),],
            'jenis_id' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->all();
            $item->update($data);
            DB::commit();
            return redirect()->route('komponen.index')->with('success', 'Komponen berhasil diupdate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('komponen.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($uuid)
    {
        try {
            $item = Komponen::where('uuid', $uuid)->firstOrFail();
            $item->delete();
            return redirect()->route('komponen.index')->with('success', 'Komponen berhasil dihapus.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('komponen.index')->with('error', $th->getMessage());
        }
    }
}
