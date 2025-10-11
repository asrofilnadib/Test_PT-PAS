<?php

  namespace App\Http\Controllers;

  use Carbon\Carbon;
  use Illuminate\Http\Request;
  use App\Models\User;
  use App\Models\Satuan;
  use Illuminate\Support\Facades\Hash;
  use Spatie\Permission\Models\Role;

  class UserController extends Controller
  {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $users = User::all();
      $roles = Role::all();
      return view('app.users', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
      try {
        $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|email|unique:users,email',
          'alamat' => 'nullable|string|max:255',
          'no_telp' => 'nullable|string|max:20',
          'role' => 'required|exists:roles,id',
          'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->alamat = $request->alamat;
        $user->no_telp = $request->no_telp;
        $user->password = Hash::make($request->password);
        $user->save();

        $role = Role::findById($request->role);
        $user->assignRole($role);

        return redirect()->back()->with('success', 'Data User Berhasil Ditambahkan!');
      } catch (\Throwable $err) {
        return redirect()->back()->with('error', 'Data User Gagal Ditambahkan!');
      }
    }

    public function detail(Request $request)
    {
      $data = User::where('id', $request->id)->first();
      return response()->json([
        'data' => $data,
      ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
      try {
        $user = User::find($request->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->alamat = $request->alamat;
        $user->no_telp = $request->no_telp;

        if (!empty($request->password)) {
          $user->password = bcrypt($request->password);
        }

        $user->updated_at = now('Asia/Jakarta');
        $user->save();

        if ($request->has('role')) $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'Data User Berhasil Diupdate!');
      } catch (\Throwable $err) {
        return redirect()->back()->with('error', 'Data User Gagal Diupdate!');
      }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      try {
        $user = User::findOrFail($id);
        // unlink("file/user/" . $user->foto);
        $user->delete();
        return redirect()->back()->with('success', "Data user Berhasil Di Hapus !");
      } catch (\Throwable $e) {
        return redirect()->back()->with('error', $e);
      }
    }
  }
