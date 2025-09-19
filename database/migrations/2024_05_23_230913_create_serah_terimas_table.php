<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('serah_terimas', function (Blueprint $table) {
            $table->id();
            $table->string('no_trx');
            $table->string('nik');
            $table->string('name');
            $table->string('department');
            $table->string('item_code');
            $table->string('item_name');
            $table->string('remark');

            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serah_terimas');
    }
};
