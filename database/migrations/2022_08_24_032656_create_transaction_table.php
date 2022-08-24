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
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 36)->index();
            $table->string('phone', 15)->index();
            $table->string('nomor_pln', 100)->nullable();
            $table->string('product_code', 20);
            $table->string('tipe', 20);
            $table->decimal('price',11,0)->default(0);
            $table->decimal('price_sell',11,0)->default(0);
            $table->decimal('nominal',11,0)->default(0);
            $table->decimal('saldo',11,0)->default(0);
            $table->decimal('margin_admin',11,0)->default(0);
            $table->decimal('margin_agen',11,0)->default(0);
            $table->decimal('donasi',11,0)->default(0);
            $table->enum('status',['paid','pending','cancel','refund','new'])->default('new');
            $table->decimal('supplier_id',11,0)->nullable();
            $table->string('note')->nullable();
            $table->longText('response_server')->nullable();
            $table->string('sn_pln')->nullable();
            $table->enum('channel', ['web','whatsapp','telegram','facebook','sms'])->default('web');
            $table->decimal('server',11,0)->nullable()->default(1);
            $table->decimal('counter',11,0)->nullable()->default(0);
            $table->string('sender',15)->nullable();
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
        Schema::dropIfExists('transaction');
    }
};
