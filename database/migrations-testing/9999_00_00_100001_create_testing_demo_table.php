<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * \Playground\Matrix\Models\Tag
 */
return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('testing_demo', function (Blueprint $table) {

            // Primary key

            $table->uuid('id')->primary();

            // IDs

            $table->uuid('created_by_id')->nullable()->index();
            $table->uuid('modified_by_id')->nullable()->index();
            $table->uuid('owned_by_id')->nullable()->index();
            $table->uuid('parent_id')->nullable()->index();
            $table->string('demo_type')->nullable()->index();

            // Dates

            $table->timestamps();

            $table->softDeletes();

            // Permissions

            $table->bigInteger('gids')->default(0)->unsigned();
            $table->tinyInteger('po')->default(0)->unsigned();
            $table->tinyInteger('pg')->default(0)->unsigned();
            $table->tinyInteger('pw')->default(0)->unsigned();
            $table->boolean('only_admin')->default(0);
            $table->boolean('only_user')->default(0);
            $table->boolean('only_guest')->default(0);
            $table->boolean('allow_public')->default(0);

            // Status

            $table->bigInteger('status')->default(0)->unsigned();
            $table->bigInteger('rank')->default(0);
            $table->bigInteger('size')->default(0);

            // Matrix

            $table->json('matrix')->nullable()->default(new Expression('(JSON_OBJECT())'));
            $table->bigInteger('x')->nullable();
            $table->bigInteger('y')->nullable();
            $table->bigInteger('z')->nullable();
            $table->decimal('r', 65, 10)->nullable();
            $table->decimal('theta', 10, 6)->nullable();
            $table->decimal('rho', 10, 6)->nullable();
            $table->decimal('phi', 10, 6)->nullable();
            $table->decimal('elevation', 65, 10)->nullable();
            $table->decimal('latitude', 8, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();

            // Flags

            $table->boolean('active')->default(1)->index();
            $table->boolean('flagged')->default(0);
            $table->boolean('internal')->default(0);
            $table->boolean('locked')->default(0);
            $table->boolean('unknown')->default(0);

            // Columns

            $table->string('label', 128)->default('');
            $table->string('title', 255)->default('');
            $table->string('byline', 255)->default('');
            $table->string('slug', 128)->nullable()->index();
            $table->string('url', 512)->default('');
            $table->string('description', 512)->default('');
            $table->string('introduction', 512)->default('');
            $table->mediumText('content')->nullable();
            $table->mediumText('summary')->nullable();

            // Ui

            $table->string('icon', 128)->default('');
            $table->string('image', 512)->default('');
            $table->string('avatar', 512)->default('');
            $table->json('ui')->nullable()->default(new Expression('(JSON_OBJECT())'));

            // JSON

            $table->json('assets')->nullable()->default(new Expression('(JSON_OBJECT())'));
            $table->json('meta')->nullable()->default(new Expression('(JSON_OBJECT())'));
            $table->json('notes')->nullable()->default(new Expression('(JSON_ARRAY())'))->comment('Array of note objects');
            $table->json('options')->nullable()->default(new Expression('(JSON_OBJECT())'));
            $table->json('sources')->nullable()->default(new Expression('(JSON_OBJECT())'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testing_demo');
    }
};
