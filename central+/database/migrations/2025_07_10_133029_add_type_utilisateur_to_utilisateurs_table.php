<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->string('type_utilisateur')->after('email'); // exemple: 'hopital', 'pharmacie', etc.
        });
    }

    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropColumn('type_utilisateur');
        });
    }
};
