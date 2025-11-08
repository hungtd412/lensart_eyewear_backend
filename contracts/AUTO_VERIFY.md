# Bật Automatic Verification trên Tenderly

## Bước 1: Enable Automatic Verification

```bash
npm run enable:auto-verify
```

Lệnh này sẽ tự động thêm `TENDERLY_AUTOMATIC_VERIFICATION=true` vào file `.env`

## Bước 2: (Tùy chọn) Thêm Access Token

Để verification hoạt động tốt hơn, thêm `TENDERLY_ACCESS_TOKEN` vào `.env`:

1. Lấy token từ: https://dashboard.tenderly.co/settings/account/authorization
2. Thêm vào `.env`:
   ```
   TENDERLY_ACCESS_TOKEN=your_token_here
   ```

## Bước 3: Deploy

```bash
npm run deploy:tenderly
```

Contracts sẽ được tự động verify sau khi deploy thành công.

## Lưu ý

- Nếu verification có lỗi (400 Bad Request), deploy vẫn thành công
- Bạn có thể verify thủ công trên Tenderly Dashboard
- Lỗi verification sẽ không làm deploy fail


