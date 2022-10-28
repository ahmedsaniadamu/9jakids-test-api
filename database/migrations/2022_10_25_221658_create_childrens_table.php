<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childrens', function (Blueprint $table) {
            $table-> increments('id');
            $table -> integer('parent_id') -> unsigned();
            $table -> string('name', 200) -> nullable(false);
            $table -> string('age_range','6') -> nullable(false);
            $table -> string('gender',7) -> nullable(false);            
            $table -> integer('code') -> nullable(false) -> unique();
            $table->timestamps();
            //create a relationship between parent and child
            $table -> foreign('parent_id') -> references('id') -> on('parents') -> onDelete('cascade') ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('childrens');
    }
}
