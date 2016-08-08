<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{

  public function catalogs()
  {
    return $this->hasMany(Catalog::class);
  }

  /**
   * Adds catalog belonging to this admin.
   *
   * @var catalog
   */
  public function addCatalog(Catalog $catalog)
  {
    return $this->catalogs()->save($catalog);
  }
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];
}
