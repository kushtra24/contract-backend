<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id');
            $table->text('filename');
            $table->string('mime')->nullable()->default('');
            $table->string('display_filename')->nullable();
            $table->integer('size')->unsigned();
            $table->tinyInteger('deleted')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_docs');
    }
}
