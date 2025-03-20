<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('client_name');
        $table->string('client_email');
        $table->decimal('total_amount', 8, 2);
        $table->string('status')->default('en attente');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('client_name');
        $table->dropColumn('client_email');
        $table->dropColumn('total_amount');
        $table->dropColumn('status');
    });
}
};
