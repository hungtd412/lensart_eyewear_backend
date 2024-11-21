<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::unprepared("
            CREATE TRIGGER after_order_cancel
            AFTER UPDATE ON orders
            FOR EACH ROW
            BEGIN
                -- Check if the order_status is changed to 'Đã hủy'
                IF NEW.order_status = 'Đã hủy' AND OLD.order_status != 'Đã hủy' THEN
                    -- Increase the quantity in product_details
                    UPDATE product_details pd
                    JOIN order_details od ON pd.product_id = od.product_id
                                             AND pd.branch_id = NEW.branch_id
                                             AND pd.color = od.color
                    SET pd.quantity = pd.quantity + od.quantity
                    WHERE od.order_id = NEW.id;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::unprepared("DROP TRIGGER IF EXISTS after_order_cancel");
    }
};
