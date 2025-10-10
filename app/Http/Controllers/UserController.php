<?php

  namespace App\Http\Controllers;

  use Carbon\Carbon;
  use Illuminate\Http\Request;
  use App\Models\User;
  use App\Models\Satuan;

  class UserController extends Controller
  {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $user = User::all();
      return view('app.users', compact('user'));
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
      $data = new User();
      $this->extracted($request, $data);
      $data->password = bcrypt($request->password);
      $data->role = $request->role;
      $data->save();
      return redirect()->route('user')->with('success', "Data User Berhasil Ditambahkan !");
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
      $data = User::find($request)->first();
//    dd($data);
      $this->extracted($request, $data);
      if (isset($request->password)) {
        $data->password = bcrypt($request->password);
      }
      $data->role = $request->role;
      $data->updated_at = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
      $data->save();
      return redirect()->route('user')->with('success', "Data User Berhasil Diupdate !");
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
        return redirect()->route('user')->with('success', "Data user Berhasil Di Hapus !");
      } catch (\Throwable $e) {
        return redirect()->route('user')->with('error', $e);
      }
    }

    /**
     * @param Request $request
     * @param $data
     * @return void
     */
    public function extracted(Request $request, $data): void
    {
      $data->name = $request->name;
      $data->alamat = $request->alamat;
      if ($file = $request->file('foto')) {
        $nama_file = md5_file($file->getRealPath()) . "_" . $file->getClientOriginalName();
        $path = 'file/user';
        $file->move($path, $nama_file);
        $data->foto = $nama_file;
      }
      $data->no_telp = $request->no_telp;
      $data->email = $request->email;
    }
  }
