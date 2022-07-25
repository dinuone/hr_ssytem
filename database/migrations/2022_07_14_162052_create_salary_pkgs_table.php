<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryPkgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_pkgs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('department_id');
            $table->decimal('basic');
            $table->decimal('allowances');
            $table->float('epf_etf');
            $table->decimal('amount');
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_pkgs');
    }
}
