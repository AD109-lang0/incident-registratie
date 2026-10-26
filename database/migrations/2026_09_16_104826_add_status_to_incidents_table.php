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
        if (! Schema::hasColumn('incidents', 'status')) {
            Schema::table('incidents', function (Blueprint $table): void {
                $table->string('status')->default('Open')->after('type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('incidents', 'status')) {
            Schema::table('incidents', function (Blueprint $table): void {
                $table->dropColumn('status');
            });
        }
    }
};
