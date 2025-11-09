# Frontend RPC Proxy Guide - Fix CORS Issues

## Vấn đề

Frontend đang gặp lỗi CORS khi kết nối trực tiếp với RPC endpoint:
```
Access to fetch at 'https://rpc.sepolia.org/' from origin 'http://localhost:5173' 
has been blocked by CORS policy
```

## Giải pháp

Backend đã tạo RPC Proxy endpoint để tránh lỗi CORS. Frontend sẽ gọi backend API thay vì kết nối trực tiếp với RPC.

## Cách sử dụng

### 1. Lấy Proxy URL từ Backend

```javascript
async function getRpcProxyUrl(network = 'sepolia') {
  const response = await fetch(
    `http://127.0.0.1:8000/api/rpc-proxy/rpc-url?network=${network}`
  );
  const result = await response.json();
  
  if (result.success) {
    return result.data.proxy_url; // http://127.0.0.1:8000/api/rpc-proxy/proxy
  }
  
  throw new Error(result.message);
}
```

### 2. Tạo Custom Provider với Proxy

**Option 1: Sử dụng ethers.js với Custom Provider**

```javascript
import { ethers } from 'ethers';

class ProxyJsonRpcProvider extends ethers.JsonRpcProvider {
  async _send(payload) {
    // Gọi backend proxy thay vì RPC trực tiếp
    const response = await fetch('http://127.0.0.1:8000/api/rpc-proxy/proxy', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        method: payload.method,
        params: payload.params || [],
        network: 'sepolia', // hoặc 'mainnet'
        id: payload.id || 1
      })
    });
    
    const result = await response.json();
    
    if (result.error) {
      throw new Error(result.error.message);
    }
    
    return result.result;
  }
}

// Sử dụng
const provider = new ProxyJsonRpcProvider('http://127.0.0.1:8000/api/rpc-proxy/proxy');
const wallet = new ethers.Wallet(privateKey, provider);
```

**Option 2: Tạo Helper Function**

```javascript
import { ethers } from 'ethers';

// Helper function để gọi RPC qua proxy
async function callRPC(method, params = [], network = 'sepolia') {
  const response = await fetch('http://127.0.0.1:8000/api/rpc-proxy/proxy', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      method: method,
      params: params,
      network: network,
      id: 1
    })
  });
  
  const result = await response.json();
  
  if (result.error) {
    throw new Error(result.error.message);
  }
  
  return result.result;
}

// Tạo custom provider
class ProxyProvider {
  async send(method, params) {
    return await callRPC(method, params, 'sepolia');
  }
}

// Sử dụng với ethers.js
const provider = new ethers.Provider(ProxyProvider);
```

**Option 3: Sử dụng StaticJsonRpcProvider với Proxy URL**

```javascript
import { ethers } from 'ethers';

// Tạo provider với backend proxy URL
const proxyUrl = 'http://127.0.0.1:8000/api/rpc-proxy/proxy';
const provider = new ethers.JsonRpcProvider(proxyUrl);

// Override _send method để thêm network parameter
const originalSend = provider._send.bind(provider);
provider._send = async function(payload) {
  const response = await fetch(proxyUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      ...payload,
      network: 'sepolia'
    })
  });
  
  return response.json();
};

// Sử dụng
const wallet = new ethers.Wallet(privateKey, provider);
```

### 3. Cập nhật Web3Service

**Cập nhật `web3Service.js`:**

```javascript
import { ethers } from 'ethers';

const BACKEND_URL = 'http://127.0.0.1:8000';
const NETWORK = 'sepolia';

class Web3Service {
  constructor() {
    this.provider = null;
    this.network = NETWORK;
  }

  // Lấy proxy URL từ backend
  async getProxyUrl() {
    const response = await fetch(
      `${BACKEND_URL}/api/rpc-proxy/rpc-url?network=${this.network}`
    );
    const result = await response.json();
    
    if (result.success) {
      return result.data.proxy_url;
    }
    
    throw new Error(result.message);
  }

  // Tạo provider với proxy
  async createProvider() {
    const proxyUrl = await this.getProxyUrl();
    
    // Tạo custom provider
    this.provider = new ethers.JsonRpcProvider(proxyUrl);
    
    // Override _send để thêm network parameter
    const originalSend = this.provider._send.bind(this.provider);
    this.provider._send = async (payload) => {
      const response = await fetch(proxyUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          method: payload.method,
          params: payload.params || [],
          network: this.network,
          id: payload.id || 1
        })
      });
      
      const result = await response.json();
      
      if (result.error) {
        throw new Error(result.error.message);
      }
      
      return result.result;
    };
    
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

### 4. Cập nhật WalletConnection Component

```javascript
import { useState } from 'react';
import web3Service from './services/web3Service';

function WalletConnection() {
  const [wallet, setWallet] = useState(null);
  const [balance, setBalance] = useState(null);
  const [error, setError] = useState(null);

  const handleConnect = async (privateKey) => {
    try {
      setError(null);
      
      // Connect wallet qua proxy
      const connectedWallet = await web3Service.connectWallet(privateKey);
      setWallet(connectedWallet);
      
      // Get balance
      const balance = await web3Service.getBalance(connectedWallet.address);
      setBalance(balance);
      
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

## API Endpoints

### 1. Get RPC Proxy URL
```
GET /api/rpc-proxy/rpc-url?network=sepolia
```

**Response:**
```json
{
  "success": true,
  "data": {
    "network": "sepolia",
    "rpc_url": "https://rpc.sepolia.org/",
    "proxy_url": "http://127.0.0.1:8000/api/rpc-proxy/proxy",
    "chain_id": 11155111
  }
}
```

### 2. Proxy RPC Request
```
POST /api/rpc-proxy/proxy
```

**Request Body:**
```json
{
  "method": "eth_getBalance",
  "params": ["0x...", "latest"],
  "network": "sepolia",
  "id": 1
}
```

**Response:**
```json
{
  "jsonrpc": "2.0",
  "result": "0x1bc16d674ec80000",
  "id": 1
}
```

## Ví dụ sử dụng

### Get Balance
```javascript
const response = await fetch('http://127.0.0.1:8000/api/rpc-proxy/proxy', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    method: 'eth_getBalance',
    params: ['0x...', 'latest'],
    network: 'sepolia'
  })
});

const result = await response.json();
const balance = result.result; // Hex string
```

### Get Transaction Count (Nonce)
```javascript
const response = await fetch('http://127.0.0.1:8000/api/rpc-proxy/proxy', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    method: 'eth_getTransactionCount',
    params: ['0x...', 'latest'],
    network: 'sepolia'
  })
});

const result = await response.json();
const nonce = parseInt(result.result, 16);
```

### Call Contract Function
```javascript
const response = await fetch('http://127.0.0.1:8000/api/rpc-proxy/proxy', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    method: 'eth_call',
    params: [{
      to: '0x...',
      data: '0x...'
    }, 'latest'],
    network: 'sepolia'
  })
});

const result = await response.json();
const data = result.result;
```

## Lưu ý

1. **Network Parameter**: Luôn cung cấp `network` parameter trong request để backend biết sử dụng RPC URL nào.

2. **Error Handling**: Kiểm tra `result.error` trong response và xử lý lỗi phù hợp.

3. **CORS**: Backend proxy đã được cấu hình CORS để cho phép requests từ frontend.

4. **Performance**: Proxy có thể thêm một chút latency, nhưng sẽ tránh được lỗi CORS.

## Testing

Test proxy endpoint:

```bash
curl -X POST "http://127.0.0.1:8000/api/rpc-proxy/proxy" \
  -H "Content-Type: application/json" \
  -d '{
    "method": "eth_getBalance",
    "params": ["0xEe5585a285c91afe74ae9f56d754CBC6eFe8Cef0", "latest"],
    "network": "sepolia",
    "id": 1
  }'
```

## Kết luận

Sử dụng RPC Proxy endpoint từ backend thay vì kết nối trực tiếp với RPC sẽ giải quyết vấn đề CORS và cho phép frontend hoạt động bình thường.

