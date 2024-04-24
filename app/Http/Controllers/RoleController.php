<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:Role View', ['only' => ['index']]);
        $this->middleware('can:Role Create', ['only' => ['store']]);
        $this->middleware('can:Role Edit', ['only' => ['update ']]);
        $this->middleware('can:Role Delete', ['only' => ['destroy ']]);
    }

    public function index()
    {
        return view('pages.role.index', [
            'title' => 'Data Role'
        ]);
    }

    public function get()
    {
        if (request()->ajax()) {
            $roles = Role::get();
            return response()->json($roles);
        }
    }

    public function data()
    {
        if (request()->ajax()) {
            $data = Role::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    if (auth()->user()->getPermissions('Role Permission')) {
                        $permission = "<button class='btn btn-sm btn-warning btnPermission py-2 px-3 mx-1' data-id='$model->id' data-name='$model->name'>Hak Akses</button>";
                    } else {
                        $permission = "";
                    }
                    if (auth()->user()->getPermissions('Role Edit')) {
                        $edit = "<button class='btn btn-sm btn-info btnEdit py-2 px-3 mx-1' data-id='$model->id' data-name='$model->name'>Edit</button>";
                    } else {
                        $edit = "";
                    }

                    if (auth()->user()->getPermissions('Role Delete')) {
                        $delete = "<button class='btn btn-sm btn-danger btnDelete py-2 px-3 mx-1' data-id='$model->id' data-name='$model->name'>Hapus</button>";
                    } else {
                        $delete = "";
                    }

                    $action = $permission . $edit . $delete;
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
            'name' => ['required', Rule::unique('roles')->ignore(request('id'))]
        ]);

        Role::updateOrCreate([
            'id'  => request('id')
        ], [
            'name' => request('name')
        ]);

        if (request('id')) {
            $message = 'Role berhasil disimpan.';
        } else {
            $message = 'Role berhasil ditambahakan.';
        }
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function removePermission()
    {
        $role_id = request('role_id');
        $permission = request('permission');
        Role::findById($role_id)->revokePermissionTo($permission);
        return response()->json(['status' => 'success', 'message' => 'Hak Akses di role berhasil dihapus.']);
    }

    public function addPermission()
    {
        $id = request('id');
        $name = request('name');
        Role::findById($id)->givePermissionTo($name);
        return response()->json(['status' => 'success', 'message' => 'Hak Akses di role berhasil ditambahkan.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Role berhasil dihapus.']);
    }
}
