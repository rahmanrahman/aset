<?php

namespace App\Http\Controllers;

use App\Models\Komputer;
use App\Models\PergantianKomputer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PergantianKomputerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Pergantian Komputer Index')->only(['index']);
        $this->middleware('can:Pergantian Komputer Create')->only(['create', 'store']);
        $this->middleware('can:Pergantian Komputer Edit')->only(['Edit', 'Update']);
        $this->middleware('can:Pergantian Komputer Delete')->only(['destroy']);
    }
    public function index()
    {
        $items = PergantianKomputer::with('komputer')->latest()->get();
        return view('pages.pergantian-komputer.index', [
            'title' => 'Data Pergantian',
            'items' => $items
        ]);
    }

    public function create()
    {
        $data_komputer = Komputer::orderBy('kode', 'ASC')->get();
        return view('pages.pergantian-komputer.create', [
            'title' => 'Tambah Pergantian',
            'data_komputer' => $data_komputer
        ]);
    }

    public function store(Request $request)
    {
        request()->validate([
            'komputer_id' => ['required'],
            'deskripsi' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->all();
            PergantianKomputer::create($data);
            DB::commit();
            return redirect()->route('pergantian-komputer.index')->with('success', 'Pergantian Komputer berhasil ditambahkan.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('pergantian-komputer.index')->with('error', $th->getMessage());
        }
    }

    public function edit($uuid)
    {
        $item = PergantianKomputer::where('uuid', $uuid)->firstOrFail();
        return view('pages.pergantian-komputer.edit', [
            'title' => 'Edit Pergantian Komputer',
            'item' => $item,
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $item = PergantianKomputer::where('uuid', $uuid)->firstOrFail();
        request()->validate([
            'deskripsi' => ['required']
        ]);

        DB::beginTransaction();
        try {

            $data = request()->all();
            $item->update($data);
            DB::commit();
            return redirect()->route('pergantian-komputer.index')->with('success', 'Pergantian Komputer berhasil diupdate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('pergantian-komputer.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($uuid)
    {
        try {
            $item = PergantianKomputer::where('uuid', $uuid)->firstOrFail();
            $item->delete();
            return redirect()->route('pergantian-komputer.index')->with('success', 'Pergantian Komputer berhasil dihapus.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('pergantian-komputer.index')->with('error', $th->getMessage());
        }
    }
}
