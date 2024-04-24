<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule as ValidationRule;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:Department View', ['only' => ['index']]);
        $this->middleware('can:Department Create', ['only' => ['store']]);
        $this->middleware('can:Department Edit', ['only' => ['update ']]);
        $this->middleware('can:Department Delete', ['only' => ['destroy ']]);
    }

    public function index()
    {
        return view('pages.department.index', [
            'title' => 'Data department'
        ]);
    }

    public function data()
    {
        if (request()->ajax()) {
            $data = Department::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    if (auth()->user()->getPermissions('Department Edit')) {
                        $edit = "<button class='btn btn-sm btn-info btnEdit py-2 px-3 mx-1' data-id='$model->id' data-nama='$model->nama'>Edit</button>";
                    } else {
                        $edit = "";
                    }

                    if (auth()->user()->getPermissions('Department Delete')) {
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
            'nama' => ['required', ValidationRule::unique('department')->ignore(request('id'))]
        ]);

        DB::beginTransaction();
        try {
            Department::updateOrCreate([
                'id'  => request('id')
            ], [
                'nama' => request('nama')
            ]);

            if (request('id')) {
                $response = ['status' => 'success', 'message' => 'Department berhasil diupdate.'];
            } else {
                $response = ['status' => 'success', 'message' => 'Department berhasil ditambahkan.'];
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
            Department::find($id)->delete();
            DB::commit();
            $response = ['status' => 'success', 'message' => 'Data Department berhasil dihapus'];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $response = ['status' => 'error', 'messaage' => $th->getMessage()];
        }
        return response()->json($response);
    }
}
