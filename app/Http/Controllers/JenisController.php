<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class JenisController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:Jenis View', ['only' => ['index']]);
        $this->middleware('can:Jenis Create', ['only' => ['store']]);
        $this->middleware('can:Jenis Edit', ['only' => ['update ']]);
        $this->middleware('can:Jenis Delete', ['only' => ['destroy ']]);
    }

    public function index()
    {
        return view('pages.jenis.index', [
            'title' => 'Data Jenis'
        ]);
    }

    public function data()
    {
        if (request()->ajax()) {
            $data = Jenis::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    if (auth()->user()->getPermissions('Jenis Edit')) {
                        $edit = "<button class='btn btn-sm btn-info btnEdit py-2 px-3 mx-1' data-id='$model->id' data-nama='$model->nama'>Edit</button>";
                    } else {
                        $edit = "";
                    }

                    if (auth()->user()->getPermissions('Jenis Delete')) {
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

    public function get()
    {
        if (request()->ajax()) {
            $id = request('id');
            $rolePermissions = Role::findById($id)->permissions->pluck('name');
            $permissions = Jenis::WhereNotIn('name', $rolePermissions)->get();
            return response()->json($permissions);
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
            'nama' => ['required', Rule::unique('jenis')->ignore(request('id'))]
        ]);

        Jenis::updateOrCreate([
            'id'  => request('id')
        ], [
            'nama' => request('nama')
        ]);

        if (request('id')) {
            $message = 'Jenis berhasil disimpan.';
        } else {
            $message = 'Jenis berhasil ditambahakan.';
        }
        return response()->json(['status' => 'succcess', 'message' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Jenis::find($id)->delete();
        return response()->json(['status' => 'succcess', 'message' => 'Data Jenis berhasil dihapus.']);
    }
}
