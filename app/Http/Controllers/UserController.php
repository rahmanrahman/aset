<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:User View', ['only' => ['index']]);
        $this->middleware('can:User Create', ['only' => ['store']]);
        $this->middleware('can:User Edit', ['only' => ['update ']]);
        $this->middleware('can:User Delete', ['only' => ['destroy ']]);
    }

    public function index()
    {
        return view('pages.user.index', [
            'title' => 'Data User'
        ]);
    }

    public function data()
    {
        if (request()->ajax()) {
            $data = User::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    if (auth()->user()->getPermissions('User Edit')) {
                        $edit = "<button class='btn btn-sm btn-info btnEdit py-2 px-3 mx-1' data-id='$model->id'>Edit</button>";
                    } else {
                        $edit = "";
                    }

                    if (auth()->user()->getPermissions('User Delete')) {
                        $delete = "<button class='btn btn-sm btn-danger btnDelete py-2 px-3 mx-1' data-id='$model->id' data-name='$model->name'>Hapus</button>";
                    } else {
                        $delete = "";
                    }
                    $action = $edit . $delete;
                    return $action;
                })
                ->addColumn('roles', function ($model) {
                    foreach ($model->roles as $role) {
                        return '<span class="badge badge-info">' . $role->name . '</span>';
                    }
                })
                ->rawColumns(['action', 'roles'])
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
            'name' => ['required'],
            'email' => ['required', Rule::unique('users')->ignore(request('id'))],
            'password' => [Rule::when(request('id') == NULL, ['required', 'min:6', 'confirmed'])],
            'password_confirmation' => [Rule::when(request('id') == NULL, ['required'])],
            'role' => ['required']
        ]);

        $data = [
            'name' => request('name'),
            'email' => request('email')
        ];
        if (request('id')) {
            if (request('password'))
                $data['password'] = bcrypt(request('password'));
            $user = User::find(request('id'))->update($data);
            $user->syncRoles(request('role'));
            $message = 'User berhasil disimpan.';
        } else {
            $data['password'] = bcrypt(request('password'));
            $user = User::create($data);
            $user->assignRole(request('role'));
            $message = 'User berhasil ditambahakan.';
        }
        return response()->json(['status' => 'succcess', 'message' => $message]);
    }

    /**n
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['status' => 'succcess', 'message' => 'Data User berhasil dihapus.']);
    }

    public function show($id)
    {
        if (request()->ajax()) {
            $item = User::with('roles')->find($id);
            if ($item) {
                return response()->json($item);
            }
        }
    }
}
