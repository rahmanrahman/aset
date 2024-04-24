<?php

namespace App\Http\Controllers;

use App\Models\IpAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class IpAddressController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:IP View', ['only' => ['index']]);
        $this->middleware('can:IP Create', ['only' => ['store']]);
        $this->middleware('can:IP Edit', ['only' => ['update ']]);
        $this->middleware('can:IP Delete', ['only' => ['destroy ']]);
    }

    public function index()
    {
        return view('pages.ipaddress.index', [
            'title' => 'Data IP'
        ]);
    }

    public function data()
    {
        if (request()->ajax()) {
            $data = IpAddress::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    if (auth()->user()->getPermissions('IP Edit')) {
                        $edit = "<button class='btn btn-sm btn-info btnEdit py-2 px-3 mx-1' data-id='$model->id' data-ip='$model->ip'>Edit</button>";
                    } else {
                        $edit = "";
                    }

                    if (auth()->user()->getPermissions('IP Delete')) {
                        $delete = "<button class='btn btn-sm btn-danger btnDelete py-2 px-3 mx-1' data-id='$model->id' data-ip='$model->ip'>Hapus</button>";
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
            'ip' => ['required', Rule::unique('ip_address')->ignore(request('id'))]
        ]);

        DB::beginTransaction();
        try {
            IpAddress::updateOrCreate([
                'id'  => request('id')
            ], [
                'ip' => request('ip')
            ]);

            if (request('id')) {
                $response = ['status' => 'success', 'message' => 'IP berhasil diupdate.'];
            } else {
                $response = ['status' => 'success', 'message' => 'IP berhasil ditambahkan.'];
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
            IpAddress::find($id)->delete();
            DB::commit();
            $response = ['status' => 'success', 'message' => 'Data IP berhasil dihapus'];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $response = ['status' => 'error', 'messaage' => $th->getMessage()];
        }
        return response()->json($response);
    }
}
