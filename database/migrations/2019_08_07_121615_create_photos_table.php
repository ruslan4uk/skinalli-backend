<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('name_photo')->nullable();
            $table->json('color')->nullable();
            $table->string('about')->nullable();
            $table->text('image_path')->nullable();
            $table->text('image_preview_path')->nullable();
            $table->text('image_lazy')->nullable();

            $table->text('image_path_webp')->nullable();
            $table->text('image_preview_path_webp')->nullable();
            $table->text('image_lazy_webp')->nullable();

            $table->boolean('active')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
