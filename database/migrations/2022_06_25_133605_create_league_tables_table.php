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
        Schema::create('league_tables', function (Blueprint $table) {
            $table->id();
            $table->integer('team_id')->comment("1");
            $table->integer('points')->default(0)->comment("PTS");
            $table->integer('played')->default(0)->comment("P");
            $table->integer('won')->default(0)->comment("W");
            $table->integer('draw')->default(0)->comment("D");
            $table->integer('lost')->default(0)->comment("L");
            $table->integer('goal_difference')->default(0)->comment("GD");
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
        Schema::dropIfExists('league_tables');
    }
};
