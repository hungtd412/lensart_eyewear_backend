<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::unprepared("
            CREATE TRIGGER after_order_insert
            AFTER INSERT ON order_details
            FOR EACH ROW
            BEGIN
                DECLARE branchId BIGINT;
                DECLARE userId BIGINT;

                -- Get branch_id and user_id from the orders table
                SELECT branch_id, user_id INTO branchId, userId
                FROM orders
                WHERE id = NEW.order_id;

                -- Decrease the quantity in product_details
                UPDATE product_details
                SET quantity = quantity - NEW.quantity
                WHERE product_id = NEW.product_id
                  AND branch_id = branchId
                  AND color = NEW.color;

                -- Delete the product variant from cart_details for the specific user
                DELETE FROM cart_details
                WHERE product_id = NEW.product_id
                  AND branch_id = branchId
                  AND color = NEW.color
                  AND cart_id = (SELECT id FROM carts WHERE user_id = userId);
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::unprepared("DROP TRIGGER IF EXISTS after_order_insert");
    }
};
