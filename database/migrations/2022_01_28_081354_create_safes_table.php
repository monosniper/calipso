<?php

use App\Models\Offer;
use App\Models\Order;
use App\Models\Safe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSafesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class);
            $table->enum('status', Safe::STATUSES)->default(Safe::ACTIVE_STATUS);
            $table->integer('amount')->nullable();
            $table->integer('days')->nullable();
            $table->text('tz')->nullable();
            $table->string('result_link', 2083)->nullable();
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
        Schema::dropIfExists('safes');
    }
}
