<?php namespace Waka\Wakajob\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateJobsTableU110 extends Migration
{
    public function up()
    {
        Schema::table('waka_wakajob_jobs', function (Blueprint $table) {
            $table->string('q_name')->default('default');
        });
    }

    public function down()
    {
        Schema::table('waka_wakajob_jobs', function (Blueprint $table) {
            $table->dropColumn('q_name');
        });
    }
}
