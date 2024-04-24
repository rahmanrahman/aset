<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:Brand View', ['only' => ['index']]);
        $this->middleware('can:Brand Create', ['only' => ['store']]);
        $this->middleware('can:Brand Edit', ['only' => ['update ']]);
        $this->middleware('can:Brand Delete', ['only' => ['destroy ']]);
    }

    public function index()
    {
        return view('pages.brand.index', [
            'title' => 'Data Brand'
        ]);
    }

    public function data()
    {
        if (request()->ajax()) {
            $data = Brand::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    if (auth()->user()->getPermissions('Brand Edit')) {
                        $edit = "<button class='btn btn-sm btn-info btnEdit py-2 px-3 mx-1' data-id='$model->id' data-nama='$model->nama'>Edit</button>";
                    } else {
                        $edit = "";
                    }

                    if (auth()->user()->getPermissions('Brand Delete')) {
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
            'nama' => ['required', Rule::unique('brand')->ignore(request('id'))]
        ]);

        DB::beginTransaction();
        try {
            Brand::updateOrCreate([
                'id'  => request('id')
            ], [
                'nama' => request('nama')
            ]);

            if (request('id')) {
                $response = ['status' => 'success', 'message' => 'Brand berhasil diupdate.'];
            } else {
                $response = ['status' => 'success', 'message' => 'Brand berhasil ditambahkan.'];
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
            Brand::find($id)->delete();
            DB::commit();
            $response = ['status' => 'success', 'message' => 'Data Brand berhasil dihapus'];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $response = ['status' => 'error', 'messaage' => $th->getMessage()];
        }
        return response()->json($response);
    }
}
