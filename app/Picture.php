<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Picture extends Model
{
  use SoftDeletes;

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];


  public function pages()
  {
    return $this->belongsToMany('App\Page', 'page_pictures', 'picture_id', 'page_id')
                ->withPivot('title', 'description', 'position', 'x', 'y', 'w', 'h', 'deleted_at')
    	          ->withTimestamps();
  }

}
