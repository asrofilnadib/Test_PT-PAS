<?php

  namespace App\Http\Controllers;

  use Carbon\Carbon;
  use Illuminate\Http\Request;
  use App\Models\TransaksiBarang;
  use App\Models\Barang;
  use Illuminate\Support\Facades\Auth;

  class TransaksiBarangController extends Controller
  {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $transaksi_barang = TransaksiBarang::with('barang')->get();
      $barang = Barang::all();
      return view('app.transaksi_barang',compact('transaksi_barang','barang'));
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
        if($request->jenis === "Keluar"){
          $barang = Barang::find($request->id_barang);
          $jumlah = $barang->stock - $request->qty;
          if($jumlah < 0 ){
            return redirect()->route('transaksi_barang')->with('error', "Stock Barang Tidak Mencukupi !");
          }
        }

        $data = new TransaksiBarang();
        $data->user_id = Auth::user()->id;
        $data->id_barang = $request->id_barang;
        $data->jenis = $request->jenis;
        $data->qty = $request->qty;

        if($file = $request->file('foto')){

          $nama_file = md5_file($file->getRealPath()) ."_".$file->getClientOriginalName();
          $path = 'file/transaksi_barang';
          $file->move($path,$nama_file);
          $data->foto = $nama_file;
        }
        $data->tanggal_transaksi = Carbon::parse($request->transaksi_barang)->toDateTimeString();
        $data->save();
        return redirect()->back()->with('success', "Data TransaksiBarang Berhasil Ditambahkan !");
      } catch (\Throwable $err) {
        return redirect()->back()->with('error', $err->getMessage());
      }
    }

    public function detail(Request $request){
      $data = TransaksiBarang::where('id',$request->id)->with('barang')->first();
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
        $data = TransaksiBarang::where('id', $request->id)->firstOrFail();
//        dd($data);
        $data->user_id = Auth::user()->id;
        $data->id_barang = $request->id_barang;
        $data->jenis = $request->jenis;
        $data->qty = $request->qty;
        if($file = $request->file('foto')){
          $nama_file = md5_file($file->getRealPath()) ."_".$file->getClientOriginalName();
          $path = 'file/transaksi_barang';
          $file->move($path,$nama_file);
          $data->foto = $nama_file;
        }
        $data->tanggal_transaksi = Carbon::parse($request->tanggal_transaksi)->toDateTimeString();
        $data->updated_at = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $data->save();
        return redirect()->back()->with('success', "Data Transaksi Barang Berhasil Diupdate !");
      } catch (\Throwable $err) {
        return redirect()->back()->with('error', $err->getMessage());
      }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      try{
        $transaksi_barang = TransaksiBarang::findOrFail($id);
        unlink("file/transaksi_barang/" . $transaksi_barang->foto);
        $transaksi_barang->delete();
        return redirect()->back()->with('success', "Data transaksi_barang Berhasil Di Hapus !");
      }catch(\Exception $e){
        return redirect()->back()->with('error', $e->getMessage());
      }
    }
  }
