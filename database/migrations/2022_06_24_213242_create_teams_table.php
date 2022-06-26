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
        /*
        "id" => "1",
         "name" => "Newcastle",
            "code" => "NEW",
            "country" => "England",
            "founded" => 1892,
            "national" => false,
            "logo" => "https://media.api-sports.io/football/teams/34.
         */
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('team_id')->comment("1");
            $table->string('name')->comment("Newcastle");
            $table->string('code')->comment("NEW");
            $table->string('country')->comment("England");
            $table->unsignedInteger('founded')->comment("1892");
            $table->boolean('national')->comment("false");
            $table->string('logo')->comment("https://media.api-sports.io/football/teams/34.png");
            $table->unsignedInteger('strength');
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
        Schema::dropIfExists('teams');
    }
};
