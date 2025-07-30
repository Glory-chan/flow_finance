<?php
// database/migrations/xxxx_xx_xx_add_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->decimal('total_balance', 15, 2)->default(0.00)->after('avatar');
            $table->json('preferences')->nullable()->after('total_balance');
            $table->timestamp('last_login_at')->nullable()->after('preferences');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'total_balance', 'preferences', 'last_login_at']);
        });
    }
};