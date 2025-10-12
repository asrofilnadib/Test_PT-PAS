<?php

  namespace App\Http\Controllers;

  use App\Jobs\SendNotificationJob;
  use App\Models\Barang;
  use Carbon\Carbon;
  use Illuminate\Http\Request;
  use App\Models\Satuan;
  use Illuminate\Support\Facades\App;
  use Illuminate\Support\Facades\Auth;

  class BarangController extends Controller
  {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $barang = Barang::select('barang.*')
        ->selectRaw('
            barang.stock +
            (
                COALESCE(SUM(CASE WHEN transaksi_barang.jenis = "Masuk" THEN transaksi_barang.qty ELSE 0 END), 0)
                -
                COALESCE(SUM(CASE WHEN transaksi_barang.jenis = "Keluar" THEN transaksi_barang.qty ELSE 0 END), 0)
            ) AS stock_aktual
        ')
        ->leftJoin('transaksi_barang', 'barang.id', '=', 'transaksi_barang.id_barang')
        ->groupBy('barang.id')
        ->with('satuan')
        ->get();

      $satuan = Satuan::all();
//      dd($barang);
      return view('app.barang',compact('barang','satuan'));
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
        $data = new Barang();
        $data->user_id = Auth::user()->id;
        $data->nama_barang = $request->nama_barang;
        $data->jenis_barang = $request->jenis_barang;
        if($file = $request->file('foto')){
          $nama_file = md5_file($file->getRealPath()) ."_".$file->getClientOriginalName();
          $path = 'file/barang';
          $file->move($path,$nama_file);
          $data->foto = $nama_file;
        }
        $data->stock = $request->stock;
        $data->id_satuan = $request->id_satuan;
        $data->expired_at = $request->expired_at;
        $data->save();

        $dataNotify = [
          'nama_barang' => $data->nama_barang,
          'jenis_barang' => 'Ditambahkan',
          'qty' => $data->stock,
        ];

        SendNotificationJob::dispatch($dataNotify);

        return redirect()->back()->with('success', "Data Barang Berhasil Ditambahkan !");
      } catch (\Throwable $ere) {
        return redirect()->back()->with('error', "Terjadi Kesalahan Saat Menambahkan Data.");
      }
    }

    public function detail(Request $request)
    {
      //
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
        $data = Barang::where('id', $request->id)->firstOrFail();
        $data->user_id = Auth::user()->id;
        $data->nama_barang = $request->nama_barang;
        $data->jenis_barang = $request->jenis_barang;

        // Handle file upload if exists
        if ($file = $request->file('foto')) {
          $nama_file = md5_file($file->getRealPath()) . "_" . $file->getClientOriginalName();
          $path = 'file/barang';
          $file->move($path, $nama_file);
          $data->foto = $nama_file;
        }

        $data->stock = $request->stock;
        $data->id_satuan = $request->id_satuan;
        $data->expired_at = $request->expired_at;
        $data->updated_at = Carbon::now('Asia/Jakarta');
        $data->save();

        $dataNotify = [
          'nama_barang' => $data->nama_barang,
          'jenis_barang' => 'Diperbarui',
          'qty' => $data->stock,
        ];

        SendNotificationJob::dispatch($dataNotify);

        return redirect()->back()->with('success', 'Data barang berhasil diperbarui!');
      } catch (\Throwable $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
      }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      try {
        $barang = Barang::findOrFail($id);

         /*// delete file if exists
         if ($barang->foto && file_exists("file/barang/{$barang->foto}")) {
             unlink("file/barang/{$barang->foto}");
         }*/

        $barang->delete();
        return redirect()->back()->with('success', 'Data barang berhasil dihapus!');
      } catch (\Throwable $e) {
        return redirect()->back()->with('error', 'Gagal menghapus data barang.');
      }
    }
  }
