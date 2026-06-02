<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // En Laravel, pour modifier un enum, il est souvent préférable d'utiliser DB::statement
        // car change() sur enum nécessite doctrine/dbal qui ne supporte pas bien les enums natifs MariaDB/MySQL
        DB::statement("ALTER TABLE visites MODIFY COLUMN statut ENUM('en_attente', 'confirmée', 'effectuée', 'annulée', 'finalisée') DEFAULT 'en_attente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE visites MODIFY COLUMN statut ENUM('en_attente', 'confirmée', 'effectuée', 'annulée') DEFAULT 'en_attente'");
    }
};
