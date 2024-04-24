<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\IpAddress;
use App\Models\Komputer;
use App\Models\SistemOperasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KomputerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Komputer Index')->only(['index']);
        $this->middleware('can:Komputer Create')->only(['create', 'store']);
        $this->middleware('can:Komputer Edit')->only(['Edit', 'Update']);
        $this->middleware('can:Komputer Delete')->only(['destroy']);
    }
    public function index()
    {
        $items = Komputer::with(['sistem_operasi', 'sistem_operasi_detail'])->orderBy('nama', 'ASC')->get();
        return view('pages.komputer.index', [
            'title' => 'Data Komputer',
            'items' => $items
        ]);
    }

    public function create()
    {
        $data_sistem_operasi = SistemOperasi::orderBy('nama', 'ASC')->get();
        $data_department = Department::orderBy('nama', 'ASC')->get();
        $data_ip_address = IpAddress::orderBy('ip', 'ASC')->where('status', 0)->get();
        return view('pages.komputer.create', [
            'title' => 'Tambah Komputer',
            'data_sistem_operasi' => $data_sistem_operasi,
            'data_ip_address' => $data_ip_address,
            'data_department' => $data_department
        ]);
    }

    public function store(Request $request)
    {
        request()->validate([
            'kode' => ['required', 'unique:komputer'],
            'nama' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->all();
            Komputer::create($data);
            DB::commit();
            return redirect()->route('komputer.index')->with('success', 'Komputer berhasil ditambahkan.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('komputer.index')->with('error', $th->getMessage());
        }
    }

    public function show($uuid)
    {
        $item = Komputer::where('uuid', $uuid)->firstOrFail();
        return view('pages.komputer.show', [
            'title' => 'Detail Komputer',
            'item' => $item
        ]);
    }
    public function edit($uuid)
    {
        $item = Komputer::where('uuid', $uuid)->firstOrFail();
        $data_sistem_operasi = SistemOperasi::orderBy('nama', 'ASC')->get();
        $data_department = Department::orderBy('nama', 'ASC')->get();
        $data_ip_address = IpAddress::orderBy('ip', 'ASC')->where('status', 0)->get();
        return view('pages.komputer.edit', [
            'title' => 'Edit Komputer',
            'item' => $item,
            'data_department' => $data_department,
            'data_sistem_operasi' => $data_sistem_operasi,
            'data_ip_address' => $data_ip_address
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $item = Komputer::where('uuid', $uuid)->firstOrFail();
        request()->validate([
            'kode' => ['required', Rule::unique('komputer')->ignore($item->id)],
            'nama' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->all();
            $item->update($data);
            DB::commit();
            return redirect()->route('komputer.index')->with('success', 'Komputer berhasil diupdate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('komputer.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($uuid)
    {
        try {
            $item = Komputer::where('uuid', $uuid)->firstOrFail();
            $item->delete();
            return redirect()->route('komputer.index')->with('success', 'Komputer berhasil dihapus.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('komputer.index')->with('error', $th->getMessage());
        }
    }
}
