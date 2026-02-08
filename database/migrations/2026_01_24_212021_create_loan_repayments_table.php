<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('installment_no'); // 1..N
            $table->date('due_date');

            $table->decimal('principal_due', 15, 2)->default(0);
            $table->decimal('interest_due', 15, 2)->default(0);
            $table->decimal('amount_due', 15, 2); // principal_due + interest_due (+ fees if any)

            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->timestamp('paid_at')->nullable();

            $table->enum('status', [
                'pending',
                'partial',
                'paid',
                'overdue',
            ])->default('pending')->index();

            $table->timestamps();

            $table->unique(['loan_id', 'installment_no']);
            $table->index(['loan_id', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_repayments');
    }
};
