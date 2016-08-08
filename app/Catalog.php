<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = ['name'];

    public function admin()
    {
      return $this->belongsTo(Admin::class);
    }

    public function admin_slug()
    {
      return '/admin/catalogs/'. $this->id;

    }
}
