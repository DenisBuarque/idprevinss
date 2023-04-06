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
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("financial_id");
            $table->double('value',8,2);
            $table->date('date');
            $table->set('active',['N','S','C'])->default('N');
            $table->string('image')->nullable();
            
            $table->foreign('user_id')->references("id")->on('users')->onDelete("cascade");
            $table->foreign('financial_id')->references("id")->on('financials')->onDelete("cascade");
            
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
        Schema::dropIfExists('installments');
    }
};
