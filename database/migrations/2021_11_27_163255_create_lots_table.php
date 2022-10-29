<?php

use App\Models\Category;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Category::class);
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->integer('price');
            $table->integer('discount_price')->nullable();
            $table->integer('views')->default(0);
            $table->json('properties')->nullable();
            $table->boolean('isPremium')->default(false);
            $table->enum('status', Lot::STATUSES)->default('moderation');
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
        Schema::dropIfExists('lots');
    }
}
