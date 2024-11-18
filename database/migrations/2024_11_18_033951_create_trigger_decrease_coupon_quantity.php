<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::unprepared("
            CREATE TRIGGER decrease_coupon_quantity_after_order_insert
            AFTER INSERT ON orders
            FOR EACH ROW
            BEGIN
                IF NEW.coupon_id IS NOT NULL THEN
                    UPDATE coupons
                    SET quantity = quantity - 1
                    WHERE id = NEW.coupon_id
                    AND quantity > 0;
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::unprepared("DROP TRIGGER IF EXISTS decrease_coupon_quantity_after_order_insert");
    }
};
