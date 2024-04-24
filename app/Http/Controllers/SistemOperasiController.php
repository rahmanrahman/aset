<?php

namespace App\Http\Controllers;

use App\Models\SistemOperasi;
use App\Models\SistemOperasiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SistemOperasiController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:SistemOperasi View', ['only' => ['index']]);
        $this->middleware('can:SistemOperasi Create', ['only' => ['store']]);
        $this->middleware('can:SistemOperasi Edit', ['only' => ['update ']]);
        $this->middleware('can:SistemOperasi Delete', ['only' => ['destroy ']]);
    }

    public function index()
    {
        return view('pages.sistem_operasi.index', [
            'title' => 'Data Sistem Operasi'
        ]);
    }

    public function data()
    {
        if (request()->ajax()) {
            $data = SistemOperasi::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    if (auth()->user()->getPermissions('SistemOperasi Edit')) {
                        $edit = "<button class='btn btn-sm btn-info btnEdit py-2 px-3 mx-1' data-id='$model->id' data-nama='$model->nama'data-versi='$model->versi'>Edit</button>";
                    } else {
                        $edit = "";
                    }

                    if (auth()->user()->getPermissions('SistemOperasi Delete')) {
                        $delete = "<button class='btn btn-sm btn-danger btnDelete py-2 px-3 mx-1' data-id='$model->id' data-nama='$model->nama'>Hapus</button>";
                    } else {
                        $delete = "";
                    }
                    $action = $edit . $delete;
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'nama' => ['required', Rule::unique('sistem_operasi')->ignore(request('id'))],
            'versi' => ['required', Rule::unique('sistem_operasi')->ignore(request('id'))]
        ]);

        DB::beginTransaction();
        try {
            SistemOperasi::updateOrCreate([
                'id'  => request('id')
            ], [
                'nama' => request('nama'),
                'versi' => request('versi')
            ]);

            if (request('id')) {
                $response = ['status' => 'success', 'message' => 'Sistem Operasi berhasil diupdate.'];
            } else {
                $response = ['status' => 'success', 'message' => 'Sistem Operasi berhasil ditambahkan.'];
            }
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $response = ['status' => 'error', 'message' => $th->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            SistemOperasi::find($id)->delete();
            DB::commit();
            $response = ['status' => 'success', 'message' => 'Data Sistem Operasi berhasil dihapus'];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $response = ['status' => 'error', 'messaage' => $th->getMessage()];
        }
        return response()->json($response);
    }

    public function detail()
    {
        if (request()->ajax()) {
            $items = SistemOperasiDetail::where('sistem_operasi_id', request('id'))->get();
            return response()->json($items);
        }
    }
}
