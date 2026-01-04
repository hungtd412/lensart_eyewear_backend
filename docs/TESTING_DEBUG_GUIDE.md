# Hướng dẫn Debug và Log trong Unit Test Laravel

## Các cách hiển thị giá trị biến trong Unit Test

### 1. Sử dụng `dump()` - Hiển thị và tiếp tục chạy

```php
public function test_example(): void
{
    $result = $this->checkoutService->initiateCheckout();
    
    // Hiển thị giá trị trong console output
    dump('Result:', $result);
    dump('Status:', $result['status']);
    
    // Có thể dump nhiều biến
    dump([
        'result' => $result,
        'status' => $result['status'],
        'session' => Session::get('checkoutSession'),
    ]);
    
    $this->assertEquals('success', $result['status']);
}
```

**Kết quả:** Hiển thị trong console khi chạy test, test vẫn tiếp tục chạy.

---

### 2. Sử dụng `dd()` - Dump and Die (dừng test)

```php
public function test_example(): void
{
    $result = $this->checkoutService->initiateCheckout();
    
    // Dừng test tại đây và hiển thị giá trị
    dd('Result:', $result);
    
    // Code sau dd() sẽ không chạy
    $this->assertEquals('success', $result['status']);
}
```

**Kết quả:** Hiển thị giá trị và dừng test ngay lập tức. Hữu ích khi muốn kiểm tra giá trị tại một điểm cụ thể.

---

### 3. Sử dụng `Log` Facade - Ghi vào log file

```php
use Illuminate\Support\Facades\Log;

public function test_example(): void
{
    $result = $this->checkoutService->initiateCheckout();
    
    // Ghi vào log file (storage/logs/laravel.log)
    Log::info('Checkout initiated', ['result' => $result]);
    Log::debug('Session value', ['session' => Session::get('checkoutSession')]);
    Log::warning('Warning message', ['data' => $data]);
    Log::error('Error occurred', ['error' => $error]);
    
    // Sử dụng channel cụ thể
    Log::channel('single')->info('Test message', ['data' => $result]);
    
    $this->assertEquals('success', $result['status']);
}
```

**Kết quả:** Ghi vào file `storage/logs/laravel.log`. Xem log bằng:
```bash
tail -f storage/logs/laravel.log
```

**Các log levels:**
- `Log::debug()` - Debug information
- `Log::info()` - Informational messages
- `Log::warning()` - Warning messages
- `Log::error()` - Error messages
- `Log::critical()` - Critical errors

---

### 4. Sử dụng `var_dump()` hoặc `print_r()`

```php
public function test_example(): void
{
    $result = $this->checkoutService->initiateCheckout();
    
    // var_dump - hiển thị với type information
    var_dump($result);
    
    // print_r - format đẹp hơn
    print_r($result);
    
    // print_r return as string
    $output = print_r($result, true);
    echo "Result: " . $output . "\n";
    
    $this->assertEquals('success', $result['status']);
}
```

**Kết quả:** Hiển thị trong console output.

---

### 5. Sử dụng `var_export()` - Format PHP code

```php
public function test_example(): void
{
    $result = $this->checkoutService->initiateCheckout();
    
    // var_export - format như PHP code
    echo var_export($result, true) . "\n";
    
    // Hoặc lưu vào biến
    $formatted = var_export($result, true);
    dump($formatted);
    
    $this->assertEquals('success', $result['status']);
}
```

**Kết quả:** Hiển thị dạng PHP code, dễ copy để dùng lại.

---

### 6. Sử dụng `json_encode()` - Format JSON

```php
public function test_example(): void
{
    $result = $this->checkoutService->initiateCheckout();
    
    // Format JSON đẹp
    echo json_encode($result, JSON_PRETTY_PRINT) . "\n";
    
    // Hoặc với dump
    dump(json_encode($result, JSON_PRETTY_PRINT));
    
    $this->assertEquals('success', $result['status']);
}
```

**Kết quả:** Hiển thị dạng JSON, dễ đọc.

---

### 7. Log trong Loop

```php
public function test_example(): void
{
    $items = [
        ['id' => 1, 'price' => 100000],
        ['id' => 2, 'price' => 200000],
    ];

    foreach ($items as $index => $item) {
        // Log từng iteration
        Log::debug("Processing item {$index}", [
            'item' => $item,
            'total' => array_sum(array_column($items, 'price')),
        ]);
        
        // Hoặc dump
        dump("Item {$index}:", $item);
    }
    
    $this->assertCount(2, $items);
}
```

---

### 8. Log với Context phong phú

```php
public function test_example(): void
{
    $userId = 1;
    $cartDetails = collect([...]);
    $result = $this->checkoutService->reviewCartGroupedByBranch($userId);
    
    // Log với nhiều context
    Log::info('Review cart completed', [
        'userId' => $userId,
        'cartDetailsCount' => $cartDetails->count(),
        'resultStatus' => $result['status'],
        'groupsCount' => count($result['data'] ?? []),
        'fullResult' => $result,
        'timestamp' => now()->toDateTimeString(),
    ]);
    
    // Dump với label rõ ràng
    dump('Review cart result:', [
        'status' => $result['status'],
        'validation_result' => $result['validation_result'],
        'groups' => $result['data'],
    ]);
    
    $this->assertEquals('success', $result['status']);
}
```

---

### 9. Debug trong Mock Callbacks

```php
public function test_example(): void
{
    $this->orderService
        ->shouldReceive('store')
        ->once()
        ->with(Mockery::on(function ($arg) {
            // Debug trong callback
            dump('Order data received:', $arg);
            Log::debug('Order creation', ['orderData' => $arg]);
            
            return $arg['branch_id'] === 1;
        }))
        ->andReturn($orderResponse);
}
```

---

### 10. Sử dụng PHPUnit Output Methods

```php
public function test_example(): void
{
    $result = $this->checkoutService->initiateCheckout();
    
    // Sử dụng expectOutputString để test output
    $this->expectOutputString('');
    echo "Result: " . json_encode($result) . "\n";
    
    // Hoặc chỉ echo
    echo "Test output: " . $result['status'] . "\n";
    
    $this->assertEquals('success', $result['status']);
}
```

---

## So sánh các phương pháp

| Phương pháp | Hiển thị ở đâu | Test có tiếp tục? | Khi nào dùng |
|------------|----------------|-------------------|--------------|
| `dump()` | Console | ✅ Có | Debug thông thường |
| `dd()` | Console | ❌ Không | Debug và dừng ngay |
| `Log::info()` | Log file | ✅ Có | Ghi log để xem sau |
| `var_dump()` | Console | ✅ Có | Debug với type info |
| `print_r()` | Console | ✅ Có | Format đẹp hơn |
| `var_export()` | Console | ✅ Có | Format PHP code |
| `json_encode()` | Console | ✅ Có | Format JSON |

---

## Best Practices

1. **Sử dụng `dump()` cho debug nhanh** - Hiển thị ngay trong console
2. **Sử dụng `Log::info()` cho log quan trọng** - Lưu lại để xem sau
3. **Sử dụng `dd()` khi cần dừng test** - Kiểm tra giá trị tại điểm cụ thể
4. **Xóa log/debug code trước khi commit** - Tránh làm rối code
5. **Sử dụng context phong phú** - Giúp dễ debug hơn

---

## Ví dụ thực tế

Xem file `tests/Unit/CheckoutServiceTest.php` để xem các ví dụ cụ thể về cách sử dụng logging trong tests.

---

## Chạy test và xem output

```bash
# Chạy test và xem output
php artisan test --filter CheckoutServiceTest

# Chạy test với verbose output
php artisan test --filter CheckoutServiceTest --verbose

# Xem log file real-time
tail -f storage/logs/laravel.log
```

---

## Tips

1. **Sử dụng emoji trong dump để dễ nhận biết:**
   ```php
   dump('✅ Validation successful:', $result);
   dump('❌ Validation failed:', $result);
   ```

2. **Format đẹp với array:**
   ```php
   dump([
       'status' => $result['status'],
       'data' => $result['data'],
   ]);
   ```

3. **Log với timestamp:**
   ```php
   Log::info('Test executed', [
       'time' => now()->toDateTimeString(),
       'data' => $result,
   ]);
   ```

