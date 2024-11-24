<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::unprepared("
            CREATE TRIGGER update_payment_status_after_amount_update
            AFTER UPDATE ON payos_transactions
            FOR EACH ROW
            BEGIN
                DECLARE total_price DECIMAL(11, 2);

                -- Fetch the total_price from the orders table
                SELECT total_price INTO total_price
                FROM orders
                WHERE id = NEW.order_id;

                -- Check if the amount is greater than or equal to the total_price
                IF NEW.amount >= total_price THEN
                    UPDATE orders
                    SET payment_status = 'Đã thanh toán'
                    WHERE id = NEW.order_id;
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::unprepared("
            DROP TRIGGER IF EXISTS update_payment_status_after_amount_update;
        ");
    }
};
