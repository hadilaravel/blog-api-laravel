<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settinges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('keywords');
            $table->string('description')->nullable();
            $table->text('icon');
            $table->text('logo_footer');
            $table->text('logo_header');
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
        Schema::dropIfExists('settinges');
    }
};
