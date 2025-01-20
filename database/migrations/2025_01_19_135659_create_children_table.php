<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birth_date');
            $table->enum('ethnicity', ['branco', 'pardo', 'negro', 'indigena', 'amarelo']);
            $table->enum('gender', ['masculino', 'feminino']);

            // Endereço
            $table->string('address');
            $table->string('address_number');
            $table->string('complement')->nullable();
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');

            // Dados dos pais
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();

            // Relacionamento com o usuário (pai/responsável)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes(); // Para exclusão lógica
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
