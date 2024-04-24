<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AplikasiController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:Aplikasi View', ['only' => ['index']]);
        $this->middleware('can:Aplikasi Create', ['only' => ['store']]);
        $this->middleware('can:Aplikasi Edit', ['only' => ['update ']]);
        $this->middleware('can:Aplikasi Delete', ['only' => ['destroy ']]);
    }

    public function index()
    {
        return view('pages.aplikasi.index', [
            'title' => 'Data Aplikasi'
        ]);
    }

    public function data()
    {
        if (request()->ajax()) {
            $data = Aplikasi::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    if (auth()->user()->getPermissions('Aplikasi Edit')) {
                        $edit = "<button class='btn btn-sm btn-info btnEdit py-2 px-3 mx-1' data-id='$model->id' data-nama='$model->nama' data-deskripsi='$model->deskripsi'>Edit</button>";
                    } else {
                        $edit = "";
                    }

                    if (auth()->user()->getPermissions('Aplikasi Delete')) {
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
            'nama' => ['required', Rule::unique('aplikasi')->ignore(request('id'))],
            'deskripsi' => ['required', Rule::unique('aplikasi')->ignore(request('id'))]
        ]);

        DB::beginTransaction();
        try {
            Aplikasi::updateOrCreate([
                'id'  => request('id')
            ], [
                'nama' => request('nama'),
                'deskripsi' => request('deskripsi')
            ]);

            if (request('id')) {
                $response = ['status' => 'success', 'message' => 'Aplikasi berhasil diupdate.'];
            } else {
                $response = ['status' => 'success', 'message' => 'Aplikasi berhasil ditambahkan.'];
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
            Aplikasi::find($id)->delete();
            DB::commit();
            $response = ['status' => 'success', 'message' => 'Data Aplikasi berhasil dihapus'];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $response = ['status' => 'error', 'messaage' => $th->getMessage()];
        }
        return response()->json($response);
    }
}
