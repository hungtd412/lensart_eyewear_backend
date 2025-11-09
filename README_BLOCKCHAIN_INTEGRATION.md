# Blockchain Integration - Backend Implementation

## 📋 Tổng quan

Backend đã được triển khai đầy đủ cho 3 yêu cầu:

1. ✅ **Tương tác với Blockchain** - Web3 Service, Wallet Service, Transaction Service
2. ✅ **Tích hợp IPFS** - IPFS Service với upload/retrieve files (PDF, ảnh, JSON)
3. ✅ **Token ERC-20 & NFT** - Token Controller, NFT Controller với các nghiệp vụ: cấp, chuyển, xác thực

## 🚀 Quick Start

### 1. Cài đặt

```bash
# Không cần cài thêm package, đã sử dụng HTTP client của Laravel
```

### 2. Cấu hình Environment

Xem file `ENV_CONFIGURATION.md` để biết cách cấu hình các biến môi trường.

Các biến cần thiết:
- `SEPOLIA_RPC_URL` - RPC URL cho Sepolia testnet
- `IPFS_API_KEY` và `IPFS_API_SECRET` - Pinata API keys
- `IPFS_GATEWAY_URL` - IPFS Gateway URL

### 3. Chạy Migrations

```bash
php artisan migrate
```

### 4. Kiểm tra Routes

```bash
php artisan route:list | grep -E "wallet|transaction|ipfs|nft|token"
```

## 📁 Cấu trúc Files

```
app/
├── Services/
│   ├── Web3Service.php           # Web3 RPC calls, encoding/decoding
│   ├── WalletService.php         # Wallet management, validation
│   ├── TransactionService.php    # Transaction handling
│   └── IPFSService.php           # IPFS upload/retrieve
├── Http/Controllers/
│   ├── WalletController.php      # Wallet APIs
│   ├── TransactionController.php # Transaction APIs
│   ├── IPFSController.php        # IPFS APIs
│   ├── NFTController.php         # NFT APIs
│   └── TokenController.php       # Token APIs
routes/
├── wallet.api.php                # Wallet routes
├── transaction.api.php           # Transaction routes
├── ipfs.api.php                  # IPFS routes
├── nft.api.php                   # NFT routes
└── token.api.php                 # Token routes
config/
├── web3.php                      # Web3 configuration
└── ipfs.php                      # IPFS configuration
database/migrations/
└── 2025_01_15_000001_create_ipfs_files_table.php
```

## 🔌 API Endpoints

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

## 🧪 Testing

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

## 📚 Documentation

### Backend Documentation
- `BACKEND_IMPLEMENTATION_SUMMARY.md` - Tóm tắt implementation
- `ENV_CONFIGURATION.md` - Hướng dẫn cấu hình environment variables

### Frontend Documentation
- `FRONTEND_INTEGRATION_GUIDE.md` - Hướng dẫn tích hợp frontend với backend

## 🔐 Security

### Lưu ý quan trọng:

1. **Private Key Security**
   - ⚠️ Private key KHÔNG được gửi lên backend
   - Frontend tự ký transaction và gửi signed transaction lên backend
   - Private key chỉ xử lý trên frontend

2. **API Keys**
   - Không commit API keys vào git
   - Sử dụng environment variables
   - Bảo mật API keys

3. **Network Security**
   - Sử dụng HTTPS trong production
   - Validate input từ frontend
   - Rate limiting cho APIs

## 🎯 Demo Checklist

### Phần 1: Web3 DApp
- [x] Backend API cho wallet management
- [x] Backend API cho transaction preparation
- [x] Backend API cho transaction sending
- [x] Backend API cho reading contract data
- [x] Backend API cho transaction status

### Phần 2: IPFS
- [x] Backend API cho upload file (PDF, Image, JSON)
- [x] Backend API cho retrieve file
- [x] Backend API cho retrieve JSON
- [x] Database migration cho IPFS files

### Phần 3: Token & NFT
- [x] Backend API cho token balance
- [x] Backend API cho token allowance
- [x] Backend API cho token transfer
- [x] Backend API cho NFT mint
- [x] Backend API cho NFT info
- [x] Backend API cho NFT by owner

## 🔄 Flow thanh toán hoàn chỉnh

1. **Frontend**: Tạo/Import wallet
2. **Frontend**: Kiểm tra số dư (ETH + LENS)
3. **Frontend**: Upload order metadata lên IPFS
4. **Frontend**: Approve token (nếu cần)
5. **Frontend**: Initiate payment
6. **Frontend**: Mint NFT cho order (nếu cần)
7. **Frontend**: Hiển thị transaction status

## 📝 Next Steps

1. ✅ Cấu hình IPFS API keys (Pinata hoặc Infura)
2. ✅ Cấu hình RPC URL (Infura, Alchemy, hoặc Tenderly)
3. ✅ Test các API endpoints
4. ⏳ Tích hợp với frontend theo hướng dẫn trong `FRONTEND_INTEGRATION_GUIDE.md`

## 🐛 Troubleshooting

### Lỗi RPC connection
- Kiểm tra RPC URL có đúng không
- Kiểm tra API key có hợp lệ không
- Kiểm tra network có đúng không

### Lỗi IPFS upload
- Kiểm tra Pinata API keys
- Kiểm tra file size (phải < 10MB)
- Kiểm tra network connection

### Lỗi Contract address not found
- Đảm bảo file `contracts/exports/frontend-config-{network}.json` tồn tại
- Chạy script export contracts: `cd contracts && npm run export:contracts`

## 📞 Support

Nếu có vấn đề, kiểm tra:
1. Logs: `storage/logs/laravel.log`
2. Routes: `php artisan route:list`
3. Config: `config/web3.php`, `config/ipfs.php`
4. Environment variables: `.env`

## 📄 License

MIT License

