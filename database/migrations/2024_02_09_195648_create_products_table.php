<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('sku', 50)->unique();

            //For this exam I want to use nullOnDelete constrained. For me, the product should
            // not be removed when the category is deleted. It should be null instead.
            // However, since category is softly deleted, nullOnDelete will not work.
            // Hence, I do this manually in the code and can be seen in the category model
            $table->foreignIdFor(Category::class)->nullable();

            $table->text('description');
            $table->string('added_by', 50);
            $table->string('image', 150);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
