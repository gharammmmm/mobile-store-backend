<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobiles', function (Blueprint $table) {
            $table->id();

            // --- Display Columns ---
            $table->string('name', 255);
            $table->string('brand', 100);
            $table->decimal('price', 10, 2);
            $table->integer('ram')->comment('GB');
            $table->integer('storage')->comment('GB');
            $table->integer('battery')->comment('mAh');
            $table->decimal('screen_size', 4, 2)->comment('inches');
            $table->integer('camera')->comment('MP rear');
            $table->string('processor', 100)->nullable();
            $table->string('os', 50)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->timestamps();

            // --- AI Model Columns (20 features) ---
            $table->integer('ram_mb')->comment('RAM in MB -> ai:ram');
            $table->tinyInteger('bluetooth')->default(1)->comment('ai:blue');
            $table->decimal('clock_speed', 3, 1)->default(1.5)->comment('GHz');
            $table->tinyInteger('dual_sim')->default(1);
            $table->integer('front_camera')->default(8)->comment('MP -> ai:fc');
            $table->tinyInteger('four_g')->default(1);
            $table->decimal('mobile_depth', 3, 1)->default(0.5)->comment('cm -> ai:m_dep');
            $table->integer('mobile_wt')->default(180)->comment('grams');
            $table->integer('n_cores')->default(8);
            $table->integer('px_height')->default(1920);
            $table->integer('px_width')->default(1080);
            $table->integer('sc_h')->default(15)->comment('screen cm');
            $table->integer('sc_w')->default(7)->comment('screen cm');
            $table->integer('talk_time')->default(15)->comment('hours');
            $table->tinyInteger('three_g')->default(1);
            $table->tinyInteger('touch_screen')->default(1);
            $table->tinyInteger('wifi')->default(1);
            // storage -> ai:int_memory (no duplicate)
            // camera  -> ai:pc         (no duplicate)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobiles');
    }
};
