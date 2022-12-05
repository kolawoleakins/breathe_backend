<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->string('orderid');
            $table->string('userid');
            $table->string('address');
            $table->string('phone');
            $table->string('item_id');
            $table->string('item_name');
            $table->float('price');
            $table->integer('quantity');
            $table->string('preference');
            $table->enum('ordertype', ['express','normal'])->default('normal');
            $table->boolean('is_paid')->default(false);
            $table->string('payment_method')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->enum('status', ['pending','processing','completed','decline'])->default('pending');
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
        Schema::dropIfExists('checkouts');
    }
}
