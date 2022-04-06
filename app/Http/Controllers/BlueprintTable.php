<?php
namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BlueprintTable{
    /**
     * default columns
     * No need to change
     */
    protected $user_columns = [
        "name",
        "email",
        "password",
        "remember_token",
        "email_verified_at"
    ];

    protected $token_columns = [
        "name",
        "token",
        "abilities",
        "tokenable_id",
        "tokenable_type",
        "updated_at",
        "created_at"
    ];
    public function __construct()
    {
        //nothing to setup
    }

    /**
     * Creating default table of users
     * Callable
     */
    public function makeUserTable(){
        Schema::create('users',function(Blueprint $table){
            $table->id();
            $table->string($this->user_columns[0]);
            $table->string($this->user_columns[1]);
            $table->string($this->user_columns[2]);
            $table->string($this->user_columns[3]);
            $table->dateTime($this->user_columns[4]);
        });

        return true;
    }

    public function makePersAccTokenTable(){
        Schema::create('personal_access_tokens',function(Blueprint $table){
            $table->id();
            $table->string($this->token_columns[0]);
            $table->string($this->token_columns[1]);
            $table->string($this->token_columns[2])->nullable();
            $table->bigInteger($this->token_columns[3])->nullable();
            $table->string($this->token_columns[4])->nullable();
            $table->dateTime($this->token_columns[5]);
            $table->dateTime($this->token_columns[6]);
        });

        return true;
    }
}