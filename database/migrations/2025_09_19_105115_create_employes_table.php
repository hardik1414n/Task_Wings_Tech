<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_code')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->bigInteger('mobile_number');
            $table->text('address');
            $table->string('gender');
            $table->string('profile_image');
            $table->string('type');
            $table->string('c_name')->nullable();
            $table->string('designation')->nullable();
            $table->date('j_date')->nullable();
            $table->date('e_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employes');
    }
};