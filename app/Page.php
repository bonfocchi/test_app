<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

  protected $fillable = ['title'];

  public function catalog()
  {
    return $this->belongsTo(Catalog::class);
  }
}
