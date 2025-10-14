<?php

namespace App\Models;

use PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Barang extends Model
{

  protected $table = 'barang';
  protected $guarded = 'id';


  public function satuan()
  {
    return $this->belongsTo(Satuan::class, 'id_satuan', 'id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function transaksi()
  {
    return $this->hasMany(TransaksiBarang::class, 'id_barang', 'id');
  }

  public function getId()
  {
    return $this->attributes['id'];
  }

  public function getShowAttrbute($value)
  {
    $now = Carbon::now();
    return $this->attributes['expired_at'] < $now ? 0 : 1;
  }

  public function getDetailBarang($id)
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
      ->where('barang.id', $id)
      ->groupBy('barang.id')
      ->with('satuan')
      ->get();

    return json_decode(json_encode($barang), true);
  }

  public function getStockAktualAttribute()
  {
    $masuk = $this->transaksi()->where('jenis', 'masuk')->sum('qty');
    $keluar = $this->transaksi()->where('jenis', 'keluar')->sum('qty');

    return $this->stock + ($masuk - $keluar);
  }

  public function generatePDFFile($filters)
  {
    $idBarang = $filters['id_barang'] ?? null;
    $filterBy = $filters['filter_by'] ?? 'all';
    $fromDate = $filters['from_date'] ?? now()->startOfMonth()->format('Y-m-d');
    $toDate = $filters['to_date'] ?? now()->format('Y-m-d');

    $query = DB::table('transaksi_barang')
      ->join('barang', 'barang.id', '=', 'transaksi_barang.id_barang')
      ->join('satuan', 'barang.id_satuan', '=', 'satuan.id')
      ->select(
        'barang.nama_barang',
        'barang.jenis_barang',
        'satuan.nama as name_satuan',
        'transaksi_barang.jenis',
        'transaksi_barang.qty as jumlah',
        'transaksi_barang.tanggal_transaksi'
      )
      ->whereBetween('transaksi_barang.tanggal_transaksi', [$fromDate, $toDate]);

    if ($idBarang) {
      $query->where('barang.id', $idBarang);
    }

    if ($filterBy === 'barang_masuk') {
      $query->where('transaksi_barang.jenis', 'Masuk');
    } elseif ($filterBy === 'barang_keluar') {
      $query->where('transaksi_barang.jenis', 'Keluar');
    }

    $data = $query->orderBy('transaksi_barang.tanggal_transaksi', 'desc')->get();

    $periode = Carbon::parse($fromDate)->format('d/m/Y') . ' - ' .
      Carbon::parse($toDate)->format('d/m/Y');

    $pdf = PDF::loadView('pdf.report', [
      'data' => $data,
      'periode' => $periode
    ]);

    $fileName = 'laporan_barang_' . now()->format('Ymd_His') . '.pdf';
    $path = storage_path('app/public/' . $fileName);
    $pdf->save($path);

    return $path;
  }
}
