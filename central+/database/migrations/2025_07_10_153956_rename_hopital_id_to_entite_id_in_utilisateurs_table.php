<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->renameColumn('hopital_id', 'entite_id');
        });
    }

    public function down()
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->renameColumn('entite_id', 'hopital_id');
        });
    }
};
