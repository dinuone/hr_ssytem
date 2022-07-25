<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpsalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empsalaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id');
            $table->unsignedBigInteger('dep_id');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('pkg_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('month');
            $table->boolean('status')->default(0);
            $table->decimal('amount');
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('dep_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('pkg_id')->references('id')->on('salary_pkgs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empsalaries');
    }
}
