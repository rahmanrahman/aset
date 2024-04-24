<?php

namespace App\Http\Controllers;

use App\Models\Komponen;
use App\Models\KomponenDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KomponenDetailController extends Controller
{
    public function index()
    {
        $komponen = Komponen::where('uuid', request('komponen_uuid'))->firstOrFail();
        $items = KomponenDetail::with('komponen')->where('komponen_id', $komponen->id)->get();
        return view('pages.komponen-detail.index', [
            'komponen_uuid' => $komponen->uuid
        ], [
            'title' => 'Data Detail Komponen',
            'items' => $items,
            'komponen' => $komponen
        ]);
    }

    public function create()
    {
        $komponen = Komponen::where('uuid', request('komponen_uuid'))->firstOrFail();
        return view('pages.komponen-detail.create', [
            'title' => 'Tambah Komponen',
            'komponen' => $komponen
        ]);
    }

    public function store(Request $request)
    {
        request()->validate([
            'keterangan' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->all();
            $komponen = Komponen::where('uuid', request('komponen_uuid'))->firstOrFail();
            $komponen->details()->create($data);
            DB::commit();
            return redirect()->route('komponen-detail.index', [
                'komponen_uuid' => $komponen->uuid
            ])->with('success', 'Detail Komponen berhasil ditambahkan.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('komponen-detail.index', [
                'komponen_uuid' => $komponen->uuid
            ])->with('error', $th->getMessage());
        }
    }

    public function edit($uuid)
    {
        $item = KomponenDetail::where('uuid', $uuid)->firstOrFail();
        return view('pages.komponen-detail.edit', [
            'title' => 'Edit Detail Komponen',
            'item' => $item,
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $item = KomponenDetail::where('uuid', $uuid)->firstOrFail();
        request()->validate([
            'keterangan' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->all();
            $item->update($data);
            DB::commit();
            return redirect()->route('komponen-detail.index', [
                'komponen_uuid' => $item->komponen->uuid
            ])->with('success', 'Detail Komponen berhasil diupdate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('komponen-detail.index', [
                'komponen_uuid' => $item->komponen->uuid
            ])->with('error', $th->getMessage());
        }
    }

    public function destroy($uuid)
    {
        try {
            $item = KomponenDetail::where('uuid', $uuid)->firstOrFail();
            $item->delete();
            return redirect()->route('komponen-detail.index', [
                'komponen_uuid' => $item->komponen->uuid
            ])->with('success', 'Detail Komponen berhasil dihapus.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('komponen-detail.index', [
                'komponen_uuid' => $item->komponen->uuid
            ])->with('error', $th->getMessage());
        }
    }
}
