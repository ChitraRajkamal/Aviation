<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $excludedTables = [
        'cart', 'failed_jobs', 'job_batches', 'jobs', 'language_phrases', 
        'languages', 'migrations', 'password_reset_tokens', 'personal_access_tokens', 
        'wishlist'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*$tables = array_map(function ($table) {
            $arr = (array) $table;
            return reset($arr);
        }, DB::select('SHOW TABLES'));
        $tables = array_diff(
            $tables, 
            $this->excludedTables
        );

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->bigInteger('organization_id')->default(0)->after('id')->index();
                $table->bigInteger('created_by_id')->default(0);
                $table->bigInteger('updated_by_id')->default(0);
            });
        }*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*$tables = array_map(function ($table) {
            $arr = (array) $table;
            return reset($arr);
        }, DB::select('SHOW TABLES'));
        $tables = array_diff(
            $tables, 
            $this->excludedTables
        );

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('organization_id');
                $table->dropColumn('created_by_id');
                $table->dropColumn('updated_by_id');
            });
        }*/
    }
};
