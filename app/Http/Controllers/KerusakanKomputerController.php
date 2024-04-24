<?php

namespace App\Http\Controllers;

use App\Models\KerusakanKomputer;
use App\Models\Komputer;
use App\Models\PergantianKomputer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KerusakanKomputerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Kerusaakan Komputer Index')->only(['index']);
        $this->middleware('can:Kerusaakan Komputer Create')->only(['create', 'store']);
        $this->middleware('can:Kerusaakan Komputer Edit')->only(['Edit', 'Update']);
        $this->middleware('can:Kerusaakan Komputer Delete')->only(['destroy']);
    }
    public function index()
    {
        $items = KerusakanKomputer::with('komputer')->latest()->get();
        return view('pages.kerusakan-komputer.index', [
            'title' => 'Data Kerusakan Komputer',
            'items' => $items
        ]);
    }

    public function create()
    {
        $data_komputer = Komputer::orderBy('kode', 'ASC')->get();
        return view('pages.kerusakan-komputer.create', [
            'title' => 'Tambah Kerusakan Komputer',
            'data_komputer' => $data_komputer
        ]);
    }

    public function store(Request $request)
    {
        request()->validate([
            'komputer_id' => ['required'],
            'status' => ['required', 'numeric'],
            'deskripsi' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $cek = KerusakanKomputer::where('komputer_id', request('komputer_id'))->whereIn('status', [0, 1])->count();
            if ($cek > 0)
                return redirect()->route('kerusakan-komputer.index')->with('error', 'Komputer terbut statusnya Belum Diperbaiki/Sedang Diperbaiki. Silahkan selesaikan terlebih dahulu.');
            $data = request()->all();
            KerusakanKomputer::create($data);
            DB::commit();
            return redirect()->route('kerusakan-komputer.index')->with('success', 'Kerusakan Komputer berhasil ditambahkan.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('kerusakan-komputer.index')->with('error', $th->getMessage());
        }
    }

    public function edit($uuid)
    {
        $item = KerusakanKomputer::where('uuid', $uuid)->firstOrFail();
        return view('pages.kerusakan-komputer.edit', [
            'title' => 'Edit Kerusakan Komputer',
            'item' => $item,
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $item = KerusakanKomputer::where('uuid', $uuid)->firstOrFail();
        request()->validate([
            'status' => ['required', 'numeric'],
            'deskripsi' => ['required'],
            'tanggal_perbaikan' => [Rule::when(request('status') == 2, ['required'])]
        ]);

        DB::beginTransaction();
        try {

            $data = request()->all();
            $is_pergantian = request('pergantian_cek');
            // jika sebelumnya tidak ada pergantian kemudian di ceklis
            if (!$item->pergantian && $is_pergantian) {
                //create adata pergantian
                $item->pergantian()->create([
                    'uuid' => \Str::uuid(),
                    'komputer_id' => $item->komputer_id,
                    'deskripsi' => request('deskripsi_pergantian')
                ]);
            } elseif ($item->pergantian && !$is_pergantian) {
                // delete pergantian
                $item->pergantian()->delete();
            } else {
                $item->pergantian()->update([
                    'deskripsi' => request('deskripsi_pergantian')
                ]);
            }
            if (request('status') != 2)
                $data['tanggal_perbaikan'] = NULL;
            $item->update($data);
            DB::commit();
            return redirect()->route('kerusakan-komputer.index')->with('success', 'Kerusakan Komputer berhasil diupdate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('kerusakan-komputer.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($uuid)
    {
        try {
            $item = KerusakanKomputer::where('uuid', $uuid)->firstOrFail();
            $item->delete();
            return redirect()->route('kerusakan-komputer.index')->with('success', 'Kerusakan Komputer berhasil dihapus.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('kerusakan-komputer.index')->with('error', $th->getMessage());
        }
    }
}
