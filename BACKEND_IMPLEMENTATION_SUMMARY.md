# Backend Implementation Summary

## Tổng quan

Đã triển khai đầy đủ backend cho 3 yêu cầu:
1. **Tương tác với Blockchain** - Web3 Service, Wallet Service, Transaction Service
2. **Tích hợp IPFS** - IPFS Service với upload/retrieve files
3. **Token ERC-20 & NFT** - Token Controller, NFT Controller

## Files đã tạo

### Services
- `app/Services/Web3Service.php` - Quản lý kết nối Web3, RPC calls, encoding/decoding
- `app/Services/WalletService.php` - Quản lý wallet, validation
- `app/Services/TransactionService.php` - Xử lý giao dịch blockchain
- `app/Services/IPFSService.php` - Upload và retrieve files từ IPFS

### Controllers
- `app/Http/Controllers/WalletController.php` - API quản lý wallet
- `app/Http/Controllers/TransactionController.php` - API gửi transaction
- `app/Http/Controllers/IPFSController.php` - API IPFS
- `app/Http/Controllers/NFTController.php` - API NFT
- `app/Http/Controllers/TokenController.php` - API Token

### Routes
- `routes/wallet.api.php` - Wallet routes
- `routes/transaction.api.php` - Transaction routes
- `routes/ipfs.api.php` - IPFS routes
- `routes/nft.api.php` - NFT routes
- `routes/token.api.php` - Token routes

### Config
- `config/web3.php` - Web3 configuration
- `config/ipfs.php` - IPFS configuration

### Migrations
- `database/migrations/2025_01_15_000001_create_ipfs_files_table.php` - IPFS files table

### Documentation
- `FRONTEND_INTEGRATION_GUIDE.md` - Hướng dẫn tích hợp frontend

## Cài đặt

### 1. Cài đặt Dependencies

Không cần cài thêm package PHP vì đã sử dụng HTTP client của Laravel để gọi RPC và IPFS API.

### 2. Cấu hình Environment Variables

Thêm vào file `.env`:

```env
# Web3 Configuration
WEB3_DEFAULT_NETWORK=sepolia
SEPOLIA_RPC_URL=https://sepolia.infura.io/v3/YOUR_INFURA_KEY
SEPOLIA_EXPLORER_URL=https://sepolia.etherscan.io
MAINNET_RPC_URL=https://mainnet.infura.io/v3/YOUR_INFURA_KEY
MAINNET_EXPLORER_URL=https://etherscan.io

# IPFS Configuration
IPFS_PROVIDER=pinata
IPFS_API_URL=https://api.pinata.cloud
IPFS_API_KEY=your_pinata_api_key
IPFS_API_SECRET=your_pinata_api_secret
IPFS_GATEWAY_URL=https://gateway.pinata.cloud/ipfs/
IPFS_MAX_FILE_SIZE=10240
```

### 3. Chạy Migrations

```bash
php artisan migrate
```

### 4. Kiểm tra Routes

```bash
php artisan route:list | grep -E "wallet|transaction|ipfs|nft|token"
```

## API Endpoints

### Wallet APIs
- `GET /api/wallet/info` - Get wallet info structure
- `POST /api/wallet/validate-address` - Validate address
- `POST /api/wallet/validate-private-key` - Validate private key
- `GET /api/wallet/balance` - Get wallet balance (ETH + LENS)
- `GET /api/wallet/contracts` - Get contract addresses
- `GET /api/wallet/abis` - Get contract ABIs

### Transaction APIs
- `POST /api/transaction/prepare/approve` - Prepare approve transaction
- `POST /api/transaction/prepare/payment` - Prepare payment transaction
- `POST /api/transaction/send` - Send signed transaction
- `GET /api/transaction/status/{txHash}` - Get transaction status
- `POST /api/transaction/read-contract` - Read contract data

### IPFS APIs
- `POST /api/ipfs/upload` - Upload file to IPFS
- `POST /api/ipfs/upload-json` - Upload JSON to IPFS
- `GET /api/ipfs/retrieve/{hash}` - Retrieve file from IPFS
- `GET /api/ipfs/retrieve-json/{hash}` - Retrieve JSON from IPFS
- `GET /api/ipfs/gateway/{hash}` - Get IPFS gateway URL
- `POST /api/ipfs/pin` - Pin file to IPFS

### Token APIs
- `GET /api/token/balance` - Get token balance
- `GET /api/token/allowance` - Get token allowance
- `POST /api/token/prepare-transfer` - Prepare transfer transaction
- `GET /api/token/contract` - Get token contract info

### NFT APIs
- `GET /api/nft/contract` - Get NFT contract info
- `POST /api/nft/prepare-mint` - Prepare mint NFT transaction
- `GET /api/nft/info/{tokenId}` - Get NFT info
- `GET /api/nft/owner` - Get NFTs by owner
- `GET /api/nft/order/{orderId}` - Get token ID by order ID

## Testing

### Test Wallet Balance

```bash
curl "http://127.0.0.1:8000/api/wallet/balance?address=0xEe5585a285c91afe74ae9f56d754CBC6eFe8Cef0&network=sepolia"
```

### Test IPFS Upload

```bash
curl -X POST "http://127.0.0.1:8000/api/ipfs/upload" \
  -F "file=@test.txt" \
  -F "name=test.txt"
```

### Test Token Balance

```bash
curl "http://127.0.0.1:8000/api/token/balance?address=0xEe5585a285c91afe74ae9f56d754CBC6eFe8Cef0&network=sepolia"
```

## Lưu ý quan trọng

1. **Private Key Security**: Private key KHÔNG được gửi lên backend. Frontend tự ký transaction và gửi signed transaction lên backend.

2. **IPFS Configuration**: Cần cấu hình Pinata hoặc Infura IPFS API keys để upload files. Nếu không có, sẽ trả về mock hash cho testing.

3. **RPC URL**: Cần cấu hình RPC URL (Infura, Alchemy, hoặc Tenderly) để kết nối với blockchain.

4. **Contract Addresses**: Contract addresses được load từ file `contracts/exports/frontend-config-{network}.json`. Đảm bảo file này tồn tại.

## Next Steps

1. Cấu hình IPFS API keys (Pinata hoặc Infura)
2. Cấu hình RPC URL (Infura, Alchemy, hoặc Tenderly)
3. Test các API endpoints
4. Tích hợp với frontend theo hướng dẫn trong `FRONTEND_INTEGRATION_GUIDE.md`

## Frontend Integration

Xem file `FRONTEND_INTEGRATION_GUIDE.md` để biết cách tích hợp frontend với backend APIs.

## Support

Nếu có vấn đề, kiểm tra:
1. Logs: `storage/logs/laravel.log`
2. Routes: `php artisan route:list`
3. Config: `config/web3.php`, `config/ipfs.php`
4. Environment variables: `.env`

