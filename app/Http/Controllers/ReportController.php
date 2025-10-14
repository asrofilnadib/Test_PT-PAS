<?php

  namespace App\Http\Controllers;

  use App\Mail\MailManager;
  use Barryvdh\DomPDF\PDF;
  use Illuminate\Http\Request;
  use App\Models\Barang;
  use App\Models\Satuan;
  use App\Models\User;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\File;
  use Illuminate\Support\Facades\Log;
  use Illuminate\Support\Facades\Mail;

  class ReportController extends Controller
  {
    /**
     * Display a listing of the resource.
     */
    protected $barang;

    public function __construct()
    {
      $this->barang = new Barang();
    }
    public function index(Request $request)
    {
      $barang = Barang::all();

      // Jika tidak ada filter, hanya tampilkan form
      if (!$request->has('id_barang') && !$request->has('filter_by') && !$request->has('from_date')) {
        return view('app.report', compact('barang'));
      }

      // Validasi input
      $request->validate([
        'id_barang' => 'nullable|exists:barang,id',
        'filter_by' => 'nullable|in:all,barang_masuk,barang_keluar',
        'from_date' => 'nullable|date',
        'to_date' => 'nullable|date|after_or_equal:from_date'
      ], [
        'to_date.after_or_equal' => 'Tanggal akhir harus lebih besar atau sama dengan tanggal awal'
      ]);

      $idBarang = $request->input('id_barang');
      $filterBy = $request->input('filter_by', 'all');
      $fromDate = $request->input('from_date');
      $toDate = $request->input('to_date');

      // Set default dates jika kosong
      if (!$fromDate) {
        $fromDate = now()->startOfMonth()->format('Y-m-d');
      }
      if (!$toDate) {
        $toDate = now()->format('Y-m-d');
      }

      // Query untuk data agregat (summary)
      $query = DB::table('transaksi_barang')
        ->join('barang', 'barang.id', '=', 'transaksi_barang.id_barang')
        ->join('satuan', 'barang.id_satuan', '=', 'satuan.id')
        ->select(
          'barang.id as barang_id',
          'barang.nama_barang',
          'barang.jenis_barang',
          'satuan.nama as name_satuan',
          'transaksi_barang.jenis',
          'transaksi_barang.tanggal_transaksi',
          'transaksi_barang.qty as jumlah'
        )
        ->whereBetween('transaksi_barang.tanggal_transaksi', [$fromDate, $toDate]);

      // Filter by barang
      if ($idBarang) {
        $query->where('barang.id', $idBarang);
      }

      // Filter by jenis transaksi
      if ($filterBy === 'barang_masuk') {
        $query->where('transaksi_barang.jenis', 'Masuk');
      } elseif ($filterBy === 'barang_keluar') {
        $query->where('transaksi_barang.jenis', 'Keluar');
      }

      $data = $query->orderBy('transaksi_barang.tanggal_transaksi', 'desc')->get();

      // Hitung summary
      $summary = [
        'masuk' => $data->where('jenis', 'Masuk')->sum('jumlah'),
        'keluar' => $data->where('jenis', 'Keluar')->sum('jumlah'),
      ];
      $summary['selisih'] = $summary['masuk'] - $summary['keluar'];
      $summary['aktual_stock'] = Barang::where('id', $request->id_barang)->sum('stock') + ($summary['masuk'] - $summary['keluar']);

      // Format periode
      $periode = \Carbon\Carbon::parse($fromDate)->format('d/m/Y') . ' - ' .
        \Carbon\Carbon::parse($toDate)->format('d/m/Y');

      // Get manager info
      $admin = User::role('Admin')->first();
      $noAdmin = $admin->no_telp ?? null;
      $emailAdmin = $admin->email ?? null;

      return view('app.report', compact(
        'barang',
        'data',
        'summary',
        'periode',
        'noAdmin',
        'emailAdmin'
      ));
    }

    public function mail(Request $request)
    {
      $request->validate([
        'subject' => 'required|string|max:255',
        'body' => 'required|string'
      ]);

      $subject = $request->input('subject');
      $body = $request->input('body');
      $reportData = json_decode(html_entity_decode($request->input('report_data')), true);
      $periode = $request->input('periode') ?? now()->format('d/m/Y');

      $admin = User::role('Admin')->first();
      if (!$admin || !$admin->email) {
        return redirect()->route('report')->with('error', 'Email manager tidak ditemukan');
      }

//      $filter = [
//        'id_barang' => $request->input('id_barang'),
//        'filter_by' => $request->input('filter_by'),
//        'from_date' => $request->input('from_date'),
//        'to_date' => $request->input('to_date'),
//      ];

      try {
//        $pdfPath = $this->barang->generatePDFFile($filter);
//
//        $excelPath = storage_path('app/public/laporan_barang_' . now()->format('Ymd_His') . '.xls');
//        $this->generateExcelFile($reportData, $excelPath);

        $data = [
          'subject' => $subject,
          'body' => $body,
          'periode' => $periode,
          'report_data' => $reportData
        ];

        Mail::to($admin->email)->send((new MailManager($data))
//          ->attach($pdfPath)
//          ->attach($excelPath)
        );

//        File::delete([$pdfPath, $excelPath]);

        return redirect()->route('report')
          ->with('success', 'Email berhasil dikirim ke ' . $admin->email);
      } catch (\Exception $e) {
        return redirect()->route('report')
          ->with('error', 'Gagal mengirim email: ' . $e->getMessage());
      }
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
      $data = new Barang();
      $data->nama_barang = $request->nama_barang;
      $data->jenis_barang = $request->jenis_barang;
      if ($file = $request->file('foto')) {

        $nama_file = md5_file($file->getRealPath()) . "_" . $file->getClientOriginalName();
        $path = 'file/barang';
        $file->move($path, $nama_file);
        $data->foto = $nama_file;
      }
      $data->stock = $request->stock;
      $data->id_satuan = $request->id_satuan;
      $data->save();
      return redirect()->route('barang')->with('success', "Data Barang Berhasil Ditambahkan !");
    }

    public function detail(Request $request)
    {
      $data = Barang::where('id', $request->id)->with('satuan')->first();
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
      $data = Barang::find($request->id);
      $data->nama_barang = $request->nama_barang;
      $data->jenis_barang = $request->jenis_barang;
      if ($file = $request->file('foto')) {

        $nama_file = md5_file($file->getRealPath()) . "_" . $file->getClientOriginalName();
        $path = 'file/barang';
        $file->move($path, $nama_file);
        $data->foto = $nama_file;
      }
      $data->stock = $request->stock;
      $data->id_satuan = $request->id_satuan;
      $data->save();
      return redirect()->route('barang')->with('success', "Data Barang Berhasil Diupdate !");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      try {
        $barang = Barang::findOrFail($id);
        // unlink("file/barang/" . $barang->foto);
        $barang->delete();
        return redirect()->route('barang')->with('success', "Data barang Berhasil Di Hapus !");
      } catch (\Throwable $e) {
        return redirect()->route('barang')->with('error', $e);
      }
    }

    private function generateExcelFile($data, $filePath)
    {
      $handle = fopen($filePath, 'wb');

      // Header
      fputcsv($handle, ['No', 'Nama Barang', 'Jenis Barang', 'Satuan', 'Jenis Transaksi', 'Jumlah', 'Tanggal Transaksi'], "\t");

      // Rows
      foreach ($data as $index => $item) {
        fputcsv($handle, [
          $index + 1,
          $item['nama_barang'] ?? '-',
          $item['jenis_barang'] ?? '-',
          $item['name_satuan'] ?? '-',
          $item['jenis'] ?? '-',
          $item['jumlah'] ?? '0',
          $item['tanggal_transaksi'] ?? '-'
        ], "\t");
      }

      fclose($handle);
    }


    public function pdf(Request $request)
    {
      $filters = $request->only(['id_barang', 'filter_by', 'from_date', 'to_date']);
//      dd($filters);
      $pdfPath = $this->barang->generatePDFFile($filters);

      return response()->file($pdfPath);
    }
  }
