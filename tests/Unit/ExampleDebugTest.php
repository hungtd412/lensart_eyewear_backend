<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Ví dụ đơn giản về cách debug/log trong Unit Test Laravel
 */
class ExampleDebugTest extends TestCase
{
    /**
     * Ví dụ 1: Sử dụng dump() - Hiển thị trong console
     */
    public function test_example_1_using_dump(): void
    {
        $data = [
            'name' => 'John Doe',
            'age' => 30,
            'email' => 'john@example.com',
        ];

        // dump() - hiển thị và tiếp tục chạy test
        dump('User data:', $data);
        dump('Name:', $data['name']);
        dump('Age:', $data['age']);

        $this->assertEquals('John Doe', $data['name']);
    }

    /**
     * Ví dụ 2: Sử dụng dd() - Dump and Die (dừng test)
     * 
     * Uncomment để test:
     */
    public function test_example_2_using_dd(): void
    {
        $result = ['status' => 'success', 'message' => 'Test passed'];

        // dd() - dừng test tại đây
        // dd('Result:', $result);

        // Code sau dd() sẽ không chạy nếu uncomment dd() ở trên
        $this->assertTrue(true);
    }

    /**
     * Ví dụ 3: Sử dụng Log facade - Ghi vào log file
     */
    public function test_example_3_using_log(): void
    {
        $userId = 1;
        $orderId = 123;

        // Ghi vào storage/logs/laravel.log
        Log::info('Order created', [
            'userId' => $userId,
            'orderId' => $orderId,
            'timestamp' => now()->toDateTimeString(),
        ]);

        Log::debug('Debug information', [
            'data' => ['key' => 'value'],
        ]);

        // Xem log: tail -f storage/logs/laravel.log
        $this->assertTrue(true);
    }

    /**
     * Ví dụ 4: Log trong loop
     */
    public function test_example_4_logging_in_loop(): void
    {
        $items = [
            ['id' => 1, 'price' => 100000],
            ['id' => 2, 'price' => 200000],
            ['id' => 3, 'price' => 150000],
        ];

        foreach ($items as $item) {
            // Log từng item
            dump("Item ID: {$item['id']}, Price: {$item['price']}");
            
            // Hoặc log vào file
            Log::debug('Processing item', [
                'itemId' => $item['id'],
                'price' => $item['price'],
            ]);
        }

        $total = array_sum(array_column($items, 'price'));
        dump('Total price:', $total);

        $this->assertEquals(450000, $total);
    }

    /**
     * Ví dụ 5: Debug với nhiều biến
     */
    public function test_example_5_debug_multiple_variables(): void
    {
        $user = ['id' => 1, 'name' => 'John'];
        $cart = ['items' => 3, 'total' => 500000];
        $result = ['status' => 'success'];

        // Dump nhiều biến cùng lúc
        dump([
            'user' => $user,
            'cart' => $cart,
            'result' => $result,
        ]);

        // Hoặc log với context
        Log::info('Checkout process', [
            'user' => $user,
            'cart' => $cart,
            'result' => $result,
        ]);

        $this->assertTrue(true);
    }

    /**
     * Ví dụ 6: Format JSON đẹp
     */
    public function test_example_6_format_json(): void
    {
        $data = [
            'status' => 'success',
            'data' => [
                'orderId' => 123,
                'total' => 500000,
            ],
        ];

        // Format JSON đẹp
        dump('JSON format:', json_encode($data, JSON_PRETTY_PRINT));

        $this->assertTrue(true);
    }

    /**
     * Ví dụ 7: Sử dụng var_export
     */
    public function test_example_7_using_var_export(): void
    {
        $config = [
            'database' => 'mysql',
            'host' => 'localhost',
            'port' => 3306,
        ];

        // var_export - format như PHP code
        $formatted = var_export($config, true);
        dump('PHP code format:', $formatted);

        $this->assertTrue(true);
    }

    /**
     * Ví dụ 8: Debug với emoji để dễ nhận biết
     */
    public function test_example_8_debug_with_emoji(): void
    {
        $result = ['status' => 'success'];

        dump('✅ Success:', $result);
        dump('❌ Error:', ['status' => 'failed']);
        dump('⚠️ Warning:', ['message' => 'Low stock']);
        dump('ℹ️ Info:', ['message' => 'Processing']);

        $this->assertTrue(true);
    }

    /**
     * Ví dụ 9: So sánh giá trị trước và sau
     */
    public function test_example_9_compare_before_after(): void
    {
        $before = ['count' => 5, 'total' => 100000];
        dump('Before:', $before);

        // Simulate some operation
        $after = ['count' => 3, 'total' => 60000];
        dump('After:', $after);

        // Compare
        dump('Difference:', [
            'count_diff' => $after['count'] - $before['count'],
            'total_diff' => $after['total'] - $before['total'],
        ]);

        $this->assertTrue(true);
    }

    /**
     * Ví dụ 10: Log với các levels khác nhau
     */
    public function test_example_10_different_log_levels(): void
    {
        $data = ['test' => 'data'];

        Log::debug('Debug message', $data);      // Debug level
        Log::info('Info message', $data);        // Info level
        Log::warning('Warning message', $data);  // Warning level
        Log::error('Error message', $data);      // Error level

        dump('Check storage/logs/laravel.log for all log levels');

        $this->assertTrue(true);
    }
}

