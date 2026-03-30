<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','operateur','visiteur','agent_municipal') NOT NULL DEFAULT 'visiteur'");
        } elseif ($driver === 'pgsql') {
            // PostgreSQL: Supprimer et recréer la contrainte CHECK
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['admin'::text, 'operateur'::text, 'visiteur'::text, 'agent_municipal'::text]))");
        }
        // SQLite n'a pas de contrainte stricte sur les enums, donc pas besoin de modification
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','operateur','visiteur') NOT NULL DEFAULT 'visiteur'");
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['admin'::text, 'operateur'::text, 'visiteur'::text]))");
        }
    }
};
