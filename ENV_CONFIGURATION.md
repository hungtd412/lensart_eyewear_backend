# Environment Variables Configuration

## Cấu hình các biến môi trường cần thiết

Thêm các biến sau vào file `.env`:

```env
# ============================================
# Web3 Configuration
# ============================================

# Network mặc định (sepolia hoặc mainnet)
WEB3_DEFAULT_NETWORK=sepolia

# Sepolia Test Network RPC URL
# Sử dụng Infura, Alchemy, hoặc Tenderly
SEPOLIA_RPC_URL=https://sepolia.infura.io/v3/YOUR_INFURA_PROJECT_ID
# Hoặc
# SEPOLIA_RPC_URL=https://eth-sepolia.g.alchemy.com/v2/YOUR_ALCHEMY_KEY
# Hoặc (Tenderly)
# SEPOLIA_RPC_URL=https://rpc.tenderly.co/fork/YOUR_FORK_ID

# Sepolia Explorer URL
SEPOLIA_EXPLORER_URL=https://sepolia.etherscan.io

# Mainnet RPC URL (nếu cần)
MAINNET_RPC_URL=https://mainnet.infura.io/v3/YOUR_INFURA_PROJECT_ID
MAINNET_EXPLORER_URL=https://etherscan.io

# Contract Addresses (optional - sẽ load từ contracts/exports)
SEPOLIA_LENS_TOKEN=
SEPOLIA_PAYMENT_CONTRACT=
SEPOLIA_NFT_CONTRACT=

# Transaction Settings
WEB3_TRANSACTION_TIMEOUT=30
WEB3_CONFIRMATIONS=1
WEB3_GAS_LIMIT=100000

# ============================================
# IPFS Configuration
# ============================================

# IPFS Provider: pinata, infura, hoặc local
IPFS_PROVIDER=pinata

# Pinata Configuration (khuyến nghị)
IPFS_API_URL=https://api.pinata.cloud
IPFS_API_KEY=your_pinata_api_key
IPFS_API_SECRET=your_pinata_api_secret
IPFS_GATEWAY_URL=https://gateway.pinata.cloud/ipfs/

# Infura IPFS Configuration (nếu dùng Infura)
# IPFS_INFURA_PROJECT_ID=your_infura_project_id
# IPFS_INFURA_PROJECT_SECRET=your_infura_project_secret
# IPFS_INFURA_ENDPOINT=https://ipfs.infura.io:5001

# Local IPFS Node Configuration (nếu dùng local node)
# IPFS_LOCAL_HOST=127.0.0.1
# IPFS_LOCAL_PORT=5001
# IPFS_LOCAL_PROTOCOL=http

# File Upload Limits
IPFS_MAX_FILE_SIZE=10240
```

## Cách lấy API Keys

### 1. Infura (RPC URL)

1. Truy cập https://infura.io
2. Đăng ký/Đăng nhập
3. Tạo project mới
4. Copy Project ID
5. Thêm vào `SEPOLIA_RPC_URL`: `https://sepolia.infura.io/v3/YOUR_PROJECT_ID`

### 2. Alchemy (RPC URL - Alternative)

1. Truy cập https://www.alchemy.com
2. Đăng ký/Đăng nhập
3. Tạo app mới
4. Copy API Key
5. Thêm vào `SEPOLIA_RPC_URL`: `https://eth-sepolia.g.alchemy.com/v2/YOUR_API_KEY`

### 3. Pinata (IPFS)

1. Truy cập https://www.pinata.cloud
2. Đăng ký/Đăng nhập
3. Tạo API Key mới
4. Copy API Key và Secret
5. Thêm vào `IPFS_API_KEY` và `IPFS_API_SECRET`

### 4. Tenderly (RPC URL - Development)

1. Truy cập https://tenderly.co
2. Đăng ký/Đăng nhập
3. Tạo Fork mới
4. Copy Fork RPC URL
5. Thêm vào `SEPOLIA_RPC_URL`

## Kiểm tra cấu hình

Sau khi cấu hình, test các endpoints:

```bash
# Test Wallet Balance
curl "http://127.0.0.1:8000/api/wallet/balance?address=0xEe5585a285c91afe74ae9f56d754CBC6eFe8Cef0&network=sepolia"

# Test IPFS Upload
curl -X POST "http://127.0.0.1:8000/api/ipfs/upload" \
  -F "file=@test.txt" \
  -F "name=test.txt"

# Test Token Balance
curl "http://127.0.0.1:8000/api/token/balance?address=0xEe5585a285c91afe74ae9f56d754CBC6eFe8Cef0&network=sepolia"
```

## Lưu ý

1. **Không commit file `.env`** vào git
2. **Bảo mật API keys** - không chia sẻ công khai
3. **Sử dụng testnet** (Sepolia) cho development
4. **Kiểm tra rate limits** của các service (Infura, Pinata, etc.)

## Troubleshooting

### Lỗi RPC connection
- Kiểm tra RPC URL có đúng không
- Kiểm tra API key có hợp lệ không
- Kiểm tra network có đúng không (sepolia/mainnet)

### Lỗi IPFS upload
- Kiểm tra Pinata API keys
- Kiểm tra file size (phải < 10MB)
- Kiểm tra network connection

### Lỗi Contract address not found
- Đảm bảo file `contracts/exports/frontend-config-{network}.json` tồn tại
- Chạy script export contracts: `cd contracts && npm run export:contracts`

