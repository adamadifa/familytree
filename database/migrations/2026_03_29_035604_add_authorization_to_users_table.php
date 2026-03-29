<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('member')->after('email');
            $table->foreignId('family_member_id')->nullable()->constrained('family_members')->nullOnDelete()->after('role');
        });

        // Set the first user as admin to prevent lockout
        $firstUser = \App\Models\User::first();
        if ($firstUser) {
            $firstUser->role = 'admin';
            $firstUser->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['family_member_id']);
            $table->dropColumn(['role', 'family_member_id']);
        });
    }
};
