<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_cms extends Model{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'cms';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ps_user';

}