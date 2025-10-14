<?php

  namespace App\Http\Controllers;

  use App\Models\Barang;
  use App\Models\TransaksiBarang;
  use Carbon\Carbon;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\DB;

  class DashboardController extends Controller
  {
    public function index()
    {
      return view('dashboard');
    }

    public function filter(Request $request)
    {
      try {
        $from = $request->from_date ? Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay() : null;
        $to = $request->to_date ? Carbon::createFromFormat('Y-m-d', $request->to_date)->endOfDay() : null;
      } catch (\Exception $e) {
        return response()->json(['error' => 'Invalid date format'], 400);
      }

      // query item transaction with date filter
      $queryMasuk = TransaksiBarang::where('jenis', 'masuk');
      $queryKeluar = TransaksiBarang::where('jenis', 'keluar');
      $queryAktivitas = TransaksiBarang::with(['barang', 'user']);

      if ($from && $to) {
        $queryMasuk->whereBetween('tanggal_transaksi', [$from, $to]);
        $queryKeluar->whereBetween('tanggal_transaksi', [$from, $to]);
        $queryAktivitas->whereBetween('tanggal_transaksi', [$from, $to]);
      }

      $barangMasuk = $queryMasuk->sum('qty');
      $barangKeluar = $queryKeluar->sum('qty');

      // take the items that are involved in transactions during the filter period
      $barangIds = TransaksiBarang::when($from && $to, function ($q) use ($from, $to) {
        $q->whereBetween('tanggal_transaksi', [$from, $to]);
      })->pluck('id_barang')->unique();

      // total of item in the filter period
      $totalBarang = Barang::whereIn('id', $barangIds)->count();

      $stockAman = $stockMenipis = $stockHabis = 0;

      $listBarang = Barang::with(['transaksi' => function ($q) use ($from, $to) {
        if ($from && $to) {
          $q->whereBetween('tanggal_transaksi', [$from, $to]);
        }
      }])->whereIn('id', $barangIds)->get();

      foreach ($listBarang as $barang) {
        $masuk = $barang->transaksi()->where('jenis', 'masuk')->sum('qty');
        $keluar = $barang->transaksi()->where('jenis', 'keluar')->sum('qty');
        $aktual = $barang->stock + ($masuk - $keluar);
        if ($aktual > 10) {
          $stockAman++;
        } else if ($aktual <= 10 && $aktual >= 1) {
          $stockMenipis++;
        } else {
          $stockHabis++;
        }
      }

      $barangMenipis = $stockMenipis;

      $aktivitas = $queryAktivitas->latest()->get()->map(function ($t) {
        return [
          'tanggal' => $t->tanggal_transaksi
            ? Carbon::parse($t->tanggal_transaksi)->format('d M Y')
            : '-',
          'nama_barang' => optional($t->barang)->nama_barang ?? '-',
          'jenis' => ucfirst($t->jenis),
          'qty' => $t->qty,
          'user' => optional($t->user)->name ?? '-',
        ];
      });

      return response()->json([
        'total_barang' => $totalBarang,
        'barang_masuk' => $barangMasuk,
        'barang_keluar' => $barangKeluar,
        'barang_menipis' => $barangMenipis,
        'stock_aman' => $stockAman,
        'stock_menipis' => $stockMenipis,
        'stock_habis' => $stockHabis,
        'aktivitas' => $aktivitas
      ]);
    }
  }
