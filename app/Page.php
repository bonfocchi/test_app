<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Page extends Model
{

  protected $fillable = ['title'];

  public function catalog()
  {
    return $this->belongsTo(Catalog::class);
  }

  /**
   * Sets the position after the page is created.
   */
  public function set_position(){

    $last_position = $this->catalog->get_last_position();

    DB::table('pages')
          ->where('id', $this->id)
          ->update( ['position' => ($last_position+1)] );

  }


  /**
   * Clears the position before a page is deleted.
   */
  public function clear_position(){

    DB::select( 'UPDATE `pages` SET
        `position` = CASE
            WHEN (`position` >= '.$this->position.') THEN
                `position`- 1
            ELSE
                `position`
          END
          WHERE catalog_id = ?',
          [$this->catalog_id]);

  }

  /**
   * Updates the position for a page and puts the rest of the catalog pages in sync.
   */
  public function update_position($new_position){

    $old_position = $this->position;

    DB::select( 'UPDATE `pages` SET
        `position` = CASE
            WHEN (`position` = '.$old_position.') THEN
                '.$new_position.'
            WHEN (`position` > '.$old_position.' and `position` <= '.$new_position.') THEN
                `position`- 1
            WHEN (`position` < '.$old_position.' and `position` >= '.$new_position.') THEN
                `position`+ 1
            ELSE
                `position`
          END
          WHERE catalog_id = ?',
          [$this->catalog_id]);

  }
}
