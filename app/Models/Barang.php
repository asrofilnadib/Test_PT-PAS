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

  public function getId()
  {
    return $this->attributes['id'];
  }

  public function getShowAttrbute($value)
  {
    $now = Carbon::now();
    return $this->attributes['expired_at'] < $now ? 0 : 1;
  }
}
