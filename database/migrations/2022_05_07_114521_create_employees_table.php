<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('username')->nullable();
            $table->string('profile_img')->nullable();
            $table->string('address')->nullable();
            $table->date('date_hired')->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->integer('avb_leave')->default(30);
            $table->string('nic');
            $table->timestamps();
            $table->engine = "InnoDB";

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
