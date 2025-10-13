<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
}
