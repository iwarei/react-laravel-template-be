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
            // deleted_atカラムを追加
            $table->softDeletes()->after('updated_at');

            // 一旦Unique制約を外す
            $table->dropUnique('users_email_unique');
            // (Laravel 10のBreeze APIの場合この制約はついていない為コメントアウト)
            // $table->dropUnique('users_username_unique');

            // 新たに複合Unique制約を追加
            $table->unique(['email', 'deleted_at'], 'users_email_deleted_at_unique');
            $table->unique(['name', 'deleted_at'], 'users_name_deleted_at_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 複合Unique制約を外す
            $table->dropUnique('users_email_deleted_at_unique');
            $table->dropUnique('users_name_deleted_at_unique');

            // unique制約を設定
            $table->unique('email', 'users_email_unique');
            // $table->unique('username', 'users_username_unique');

            // deleted_atカラムを削除
            $table->dropColumn('deleted_at');
        });
    }
};
