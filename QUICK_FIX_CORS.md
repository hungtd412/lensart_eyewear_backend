# Quick Fix CORS Error

## Vấn đề

Frontend gặp lỗi CORS khi kết nối trực tiếp với RPC endpoint `https://rpc.sepolia.org/`.

## Giải pháp

**Thay đổi RPC URL trong frontend từ:**
```javascript
// ❌ SAI - Gây lỗi CORS
const provider = new ethers.JsonRpcProvider('https://rpc.sepolia.org/');
```

**Thành:**
```javascript
// ✅ ĐÚNG - Sử dụng backend proxy
const provider = new ethers.JsonRpcProvider(
  'http://127.0.0.1:8000/api/rpc-proxy/proxy?network=sepolia'
);
```

## Cập nhật Web3Service

**File: `src/services/web3Service.js` hoặc tương tự**

```javascript
import { ethers } from 'ethers';

const BACKEND_URL = 'http://127.0.0.1:8000';
const NETWORK = 'sepolia';

class Web3Service {
  constructor() {
    // Sử dụng backend proxy URL
    this.proxyUrl = `${BACKEND_URL}/api/rpc-proxy/proxy?network=${NETWORK}`;
    this.provider = new ethers.JsonRpcProvider(this.proxyUrl);
  }

  async connectWallet(privateKey) {
    try {
      const wallet = new ethers.Wallet(privateKey, this.provider);
      return wallet;
    } catch (error) {
      console.error('Error connecting wallet:', error);
      throw error;
    }
  }

  async getBalance(address) {
    try {
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

## Cập nhật WalletConnection Component

**File: `src/components/WalletConnection.jsx` hoặc tương tự**

```javascript
import { useState } from 'react';
import { ethers } from 'ethers';

const BACKEND_URL = 'http://127.0.0.1:8000';
const NETWORK = 'sepolia';

function WalletConnection() {
  const [wallet, setWallet] = useState(null);
  const [balance, setBalance] = useState(null);
  const [error, setError] = useState(null);

  const handleConnect = async (privateKey) => {
    try {
      setError(null);
      
      // Sử dụng backend proxy
      const provider = new ethers.JsonRpcProvider(
        `${BACKEND_URL}/api/rpc-proxy/proxy?network=${NETWORK}`
      );
      
      const wallet = new ethers.Wallet(privateKey, provider);
      setWallet(wallet);
      
      // Get balance
      const balance = await provider.getBalance(wallet.address);
      setBalance(ethers.formatEther(balance));
      
    } catch (error) {
      console.error('Connect wallet error:', error);
      setError(error.message);
    }
  };

  return (
    <div>
      {error && <div className="error">{error}</div>}
      {wallet && (
        <div>
          <p>Address: {wallet.address}</p>
          <p>Balance: {balance} ETH</p>
        </div>
      )}
    </div>
  );
}
```

## Test

Sau khi cập nhật, test lại:

```javascript
// Test provider
const provider = new ethers.JsonRpcProvider(
  'http://127.0.0.1:8000/api/rpc-proxy/proxy?network=sepolia'
);

// Test get balance
const balance = await provider.getBalance('0xEe5585a285c91afe74ae9f56d754CBC6eFe8Cef0');
console.log('Balance:', ethers.formatEther(balance));
```

## Lưu ý

1. **Query Parameter**: Luôn thêm `?network=sepolia` vào proxy URL
2. **Backend URL**: Đảm bảo backend đang chạy tại `http://127.0.0.1:8000`
3. **CORS**: Backend đã được cấu hình CORS cho phép requests từ `localhost:5173`

## Alternative: Sử dụng Infura hoặc Alchemy

Nếu không muốn dùng backend proxy, có thể sử dụng Infura hoặc Alchemy (có CORS support):

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

## Xem thêm

- `FRONTEND_FIX_CORS.md` - Hướng dẫn chi tiết
- `FRONTEND_RPC_PROXY_GUIDE.md` - Hướng dẫn đầy đủ về RPC Proxy

