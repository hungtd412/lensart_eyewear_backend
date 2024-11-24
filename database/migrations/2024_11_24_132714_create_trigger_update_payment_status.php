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
                DECLARE totalPrice DECIMAL(11, 2);
                DECLARE sumAmount DECIMAL(11, 2);

                SELECT total_price INTO totalPrice
                FROM orders
                WHERE id = NEW.order_id;

                SELECT sum(amount) INTO sumAmount
                FROM payos_transactions
                WHERE order_id = NEW.order_id;


                IF sumAmount >= totalPrice THEN
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
