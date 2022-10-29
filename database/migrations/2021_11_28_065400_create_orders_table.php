<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(User::class, 'freelancer_id')->nullable();
            $table->foreignIdFor(Category::class);
            $table->string('title');
            $table->text('description');
            $table->integer('price');
            $table->integer('views')->default(0);
            $table->enum('status', Order::STATUSES)->default('active');
            $table->boolean('isUrgent')->default(false);
            $table->boolean('isSafe')->default(false);
            $table->integer('days')->default(3);
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
