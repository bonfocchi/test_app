<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;


class Catalog extends Model
{
    protected $fillable = ['name'];

    public function admin()
    {
      return $this->belongsTo(Admin::class);
    }

    public function pages()
    {
      return $this->hasMany(Page::class);
    }

    public function admin_slug()
    {
      return '/admin/catalogs/'. $this->id;
    }

    /**
     * Adds page belonging to this catalog.
     *
     * @var catalog
     */
    public function addPage(Page $page)
    {
      return $this->pages()->save($page);
    }


    /**
     * Gets the last page position for the catalog.
     *
     * @var int
     */
    public function get_last_position(){

      $last_position = 0;

      //$last_page = DB::select('select id, position, MAX(position) from pages where catalog_id = ?', [$catalog_id]);

      $last_page = DB::select( 'SELECT id, position
                                FROM   pages
                                WHERE position=(SELECT MAX(position) FROM pages WHERE catalog_id = ?);',
                                      [$this->id]);

      if( !empty($last_page) && isset($last_page[0]) ){
        $last_position = $last_page[0]->position;
      }

      return $last_position;
    }
}
