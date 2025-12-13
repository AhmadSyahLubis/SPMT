<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Perbaiki kolom notifiable_type
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('notifiable_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Tidak perlu melakukan apa-apa di sini karena ini adalah perbaikan
    }
};
