<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->enum('type', ['html', 'link', 'pdf'])->index();
            $table->longText('description')->nullable();
            $table->longText('html_snippet')->nullable();
            $table->string('link')->nullable();
            $table->enum('link_target', ['_parent', '_blank'])->nullable();
            $table->string('file')->nullable();
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
        Schema::dropIfExists('resources');
    }
};
