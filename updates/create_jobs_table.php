<?php namespace Waka\Wakajob\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_wakajob_jobs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('label');
            $table->integer('status')->default(0);
            $table->integer('progress')->default(0);
            $table->integer('progress_max')->default(0);
            $table->integer('user_id')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_canceled')->default(false);
            $table->text('metadata');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_wakajob_jobs');
    }
}
