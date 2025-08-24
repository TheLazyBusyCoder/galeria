<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            // If it's enum:
            DB::statement("ALTER TABLE messages MODIFY COLUMN type ENUM('text','file','voice') NOT NULL");

            // OR if it's varchar and too short, just extend length:
            // $table->string('type', 20)->change();
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            // rollback to old state
            DB::statement("ALTER TABLE messages MODIFY COLUMN type ENUM('text','file') NOT NULL");

            // OR
            // $table->string('type', 10)->change();
        });
    }
};
