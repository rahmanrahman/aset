<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:Vendor View', ['only' => ['index']]);
        $this->middleware('can:Vendor Create', ['only' => ['store']]);
        $this->middleware('can:Vendor Edit', ['only' => ['update ']]);
        $this->middleware('can:Vendor Delete', ['only' => ['destroy ']]);
    }

    public function index()
    {
        return view('pages.vendor.index', [
            'title' => 'Data Vendor'
        ]);
    }

    public function data()
    {
        if (request()->ajax()) {
            $data = Vendor::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    if (auth()->user()->getPermissions('Vendor Edit')) {
                        $edit = "<button class='btn btn-sm btn-info btnEdit py-2 px-3 mx-1' data-id='$model->id' >Edit</button>";
                    } else {
                        $edit = "";
                    }
                    if (auth()->user()->getPermissions('Vendor Delete')) {
                        $delete = "<button class='btn btn-sm btn-danger btnDelete py-2 px-3 mx-1' data-id='$model->id' >Hapus</button>";
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
            'nama' => ['required', Rule::unique('brand')->ignore(request('id'))],
            'email' => ['email']
        ]);

        DB::beginTransaction();
        try {
            Vendor::updateOrCreate([
                'id'  => request('id')
            ], [
                'nama' => request('nama'),
                'email' => request('email'),
                'nomor_telepon' => request('nomor_telepon'),
                'alamat' => request('alamat')
            ]);

            if (request('id')) {
                $response = ['status' => 'success', 'message' => 'Vendor berhasil diupdate.'];
            } else {
                $response = ['status' => 'success', 'message' => 'Vendor berhasil ditambahkan.'];
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
            Vendor::find($id)->delete();
            DB::commit();
            $response = ['status' => 'success', 'message' => 'Data Vendor berhasil dihapus'];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $response = ['status' => 'error', 'messaage' => $th->getMessage()];
        }
        return response()->json($response);
    }

    public function getById()
    {
        if (request()->ajax()) {
            $item = Vendor::find(request('id'));
            if ($item) {
                return response()->json([
                    'status' => 'success',
                    'data' => $item
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'data' => []
                ]);
            }
        }
    }
}
