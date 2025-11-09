# Fix CORS Error - Hướng dẫn nhanh

## Vấn đề

Frontend gặp lỗi CORS khi kết nối trực tiếp với RPC:
```
Access to fetch at 'https://rpc.sepolia.org/' from origin 'http://localhost:5173' 
has been blocked by CORS policy
```

## Giải pháp nhanh

### Cách 1: Sử dụng Backend RPC Proxy (Khuyến nghị)

Backend đã có RPC Proxy endpoint. Frontend chỉ cần thay đổi RPC URL:

**Trước (SAI):**
```javascript
const provider = new ethers.JsonRpcProvider('https://rpc.sepolia.org/');
```

**Sau (ĐÚNG):**
```javascript
// Sử dụng backend proxy URL
const provider = new ethers.JsonRpcProvider(
  'http://127.0.0.1:8000/api/rpc-proxy/proxy?network=sepolia'
);
```

### Cách 2: Sử dụng Infura hoặc Alchemy (Có CORS support)

Thay vì dùng `rpc.sepolia.org`, sử dụng Infura hoặc Alchemy:

```javascript
// Infura
const provider = new ethers.JsonRpcProvider(
  'https://sepolia.infura.io/v3/YOUR_INFURA_KEY'
);

// Hoặc Alchemy
const provider = new ethers.JsonRpcProvider(
  'https://eth-sepolia.g.alchemy.com/v2/YOUR_ALCHEMY_KEY'
);
```

### Cách 3: Cập nhật Web3Service

**File: `web3Service.js`**

```javascript
import { ethers } from 'ethers';

const BACKEND_URL = 'http://127.0.0.1:8000';
const NETWORK = 'sepolia';

class Web3Service {
  constructor() {
    this.provider = null;
    this.network = NETWORK;
  }

  // Tạo provider với backend proxy
  async createProvider() {
    // Option 1: Sử dụng backend proxy
    const proxyUrl = `${BACKEND_URL}/api/rpc-proxy/proxy?network=${this.network}`;
    this.provider = new ethers.JsonRpcProvider(proxyUrl);
    
    // Option 2: Sử dụng Infura (nếu có API key)
    // const infuraUrl = `https://sepolia.infura.io/v3/YOUR_INFURA_KEY`;
    // this.provider = new ethers.JsonRpcProvider(infuraUrl);
    
    return this.provider;
  }

  // Connect wallet
  async connectWallet(privateKey) {
    try {
      if (!this.provider) {
        await this.createProvider();
      }
      
      const wallet = new ethers.Wallet(privateKey, this.provider);
      return wallet;
    } catch (error) {
      console.error('Error connecting wallet:', error);
      throw error;
    }
  }

  // Get balance
  async getBalance(address) {
    try {
      if (!this.provider) {
        await this.createProvider();
      }
      
      const balance = await this.provider.getBalance(address);
      return ethers.formatEther(balance);
    } catch (error) {
      console.error('Error getting balance:', error);
      throw error;
    }
  }
}

export default new Web3Service();
```

## Kiểm tra

### Test Backend Proxy

```bash
curl -X POST "http://127.0.0.1:8000/api/rpc-proxy/proxy?network=sepolia" \
  -H "Content-Type: application/json" \
  -d '{
    "jsonrpc": "2.0",
    "method": "eth_getBalance",
    "params": ["0xEe5585a285c91afe74ae9f56d754CBC6eFe8Cef0", "latest"],
    "id": 1
  }'
```

### Test từ Frontend

```javascript
// Test provider
const provider = new ethers.JsonRpcProvider(
  'http://127.0.0.1:8000/api/rpc-proxy/proxy?network=sepolia'
);

// Test get balance
const balance = await provider.getBalance('0x...');
console.log('Balance:', ethers.formatEther(balance));
```

## Lưu ý

1. **Backend Proxy URL**: `http://127.0.0.1:8000/api/rpc-proxy/proxy?network=sepolia`
2. **Network Parameter**: Luôn thêm `?network=sepolia` vào URL
3. **CORS**: Backend đã cấu hình CORS cho phép requests từ `localhost:5173`

## Xem thêm

- `FRONTEND_RPC_PROXY_GUIDE.md` - Hướng dẫn chi tiết về RPC Proxy
- `FRONTEND_INTEGRATION_GUIDE.md` - Hướng dẫn tích hợp frontend đầy đủ

