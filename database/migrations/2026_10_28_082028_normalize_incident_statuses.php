<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('incidents')->where('status', 'Open')->update(['status' => 'Nieuw']);
        DB::table('incidents')->where('status', 'In behandeling')->update(['status' => 'In onderzoek']);

        Schema::table('incidents', function (Blueprint $table): void {
            $table->string('status')->default('Nieuw')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('incidents')->where('status', 'Nieuw')->update(['status' => 'Open']);
        DB::table('incidents')->where('status', 'In onderzoek')->update(['status' => 'In behandeling']);

        Schema::table('incidents', function (Blueprint $table): void {
            $table->string('status')->default('Open')->change();
        });
    }
};
