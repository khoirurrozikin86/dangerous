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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('no_trx_out');
            $table->bigInteger('employee_id', false, true)->unsigned()->index();
            $table->bigInteger('item_id', false, true)->unsigned()->index();
            $table->string('no_trx_return');
            $table->string('remark');
            $table->timestamps();


            $table->foreign('employee_id')
            ->references('id')
            ->on('employees')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('item_id')
            ->references('id')
            ->on('items')
            ->onUpdate('cascade')
            ->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
