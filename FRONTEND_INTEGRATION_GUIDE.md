# Frontend Integration Guide - Web3 DApp, IPFS, NFT & Token

## Tổng quan

Tài liệu này hướng dẫn tích hợp frontend với backend API để thực hiện:
1. **Tương tác với Blockchain** - Gửi giao dịch, đọc dữ liệu, hiển thị trạng thái
2. **Tích hợp IPFS** - Upload và retrieve files (PDF, ảnh, JSON)
3. **Token ERC-20 & NFT** - Quản lý token, mint NFT, xác thực

## Lưu ý quan trọng

⚠️ **KHÔNG DÙNG METAMASK** - Frontend tự quản lý wallet và ký transaction
⚠️ **KHÔNG GỬI PRIVATE KEY LÊN BACKEND** - Private key chỉ xử lý trên frontend
⚠️ **MÃ HÓA DỮ LIỆU NHẠY CẢM** - Mã hóa private key trước khi lưu localStorage

---

## 1. Wallet Management

### 1.1. Tạo Wallet mới

**Frontend Code (ethers.js):**
```javascript
import { ethers } from 'ethers';

// Tạo wallet mới
const wallet = ethers.Wallet.createRandom();

// Lưu thông tin (KHÔNG lưu private key trực tiếp)
const walletData = {
  address: wallet.address,
  // Mã hóa private key trước khi lưu
  encryptedPrivateKey: encryptPrivateKey(wallet.privateKey, password),
  mnemonic: wallet.mnemonic.phrase // Lưu an toàn
};

// Lưu vào localStorage (đã mã hóa)
localStorage.setItem('wallet', JSON.stringify(walletData));
```

### 1.2. Import Wallet từ Private Key

```javascript
import { ethers } from 'ethers';

// Import từ private key
const privateKey = '0x...'; // User nhập
const wallet = new ethers.Wallet(privateKey);

// Lưu wallet (đã mã hóa)
const walletData = {
  address: wallet.address,
  encryptedPrivateKey: encryptPrivateKey(privateKey, password)
};

localStorage.setItem('wallet', JSON.stringify(walletData));
```

### 1.3. Import Wallet từ Mnemonic

```javascript
import { ethers } from 'ethers';

// Import từ mnemonic
const mnemonic = 'word1 word2 ... word12';
const wallet = ethers.Wallet.fromPhrase(mnemonic);

// Lưu wallet
const walletData = {
  address: wallet.address,
  encryptedPrivateKey: encryptPrivateKey(wallet.privateKey, password),
  mnemonic: mnemonic
};

localStorage.setItem('wallet', JSON.stringify(walletData));
```

### 1.4. Kiểm tra số dư

**API Endpoint:**
```
GET /api/wallet/balance?address=0x...&network=sepolia
```

**Frontend Code:**
```javascript
async function getWalletBalance(address, network = 'sepolia') {
  const response = await fetch(
    `http://127.0.0.1:8000/api/wallet/balance?address=${address}&network=${network}`
  );
  const result = await response.json();
  
  if (result.success) {
    console.log('ETH Balance:', result.data.balances.eth);
    console.log('LENS Balance:', result.data.balances.lens);
    return result.data;
  }
  
  throw new Error(result.message);
}

// Sử dụng
const balance = await getWalletBalance('0x...', 'sepolia');
```

### 1.5. Lấy Contract Addresses & ABIs

**API Endpoint:**
```
GET /api/wallet/contracts?network=sepolia
GET /api/wallet/abis?network=sepolia
```

**Frontend Code:**
```javascript
async function getContractInfo(network = 'sepolia') {
  // Lấy addresses
  const addressesResponse = await fetch(
    `http://127.0.0.1:8000/api/wallet/contracts?network=${network}`
  );
  const addresses = await addressesResponse.json();
  
  // Lấy ABIs
  const abisResponse = await fetch(
    `http://127.0.0.1:8000/api/wallet/abis?network=${network}`
  );
  const abis = await abisResponse.json();
  
  return {
    contracts: addresses.data,
    abis: abis.data
  };
}

// Sử dụng
const contractInfo = await getContractInfo('sepolia');
const lensTokenAddress = contractInfo.contracts.LENSToken;
const lensTokenABI = contractInfo.abis.LENSToken;
```

---

## 2. Transaction Management

### 2.1. Approve Token

**Flow:**
1. Backend prepare transaction data
2. Frontend ký transaction
3. Frontend gửi signed transaction lên backend
4. Backend gửi transaction lên blockchain

**API Endpoints:**
```
POST /api/transaction/prepare/approve
POST /api/transaction/send
GET /api/transaction/status/{txHash}
```

**Frontend Code:**
```javascript
import { ethers } from 'ethers';

async function approveToken(
  tokenContractAddress,
  spenderAddress,
  amount,
  fromAddress,
  privateKey,
  network = 'sepolia'
) {
  // 1. Prepare transaction
  const prepareResponse = await fetch(
    'http://127.0.0.1:8000/api/transaction/prepare/approve',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        token_contract: tokenContractAddress,
        spender_address: spenderAddress,
        amount: amount,
        from_address: fromAddress,
        network: network
      })
    }
  );
  
  const prepareResult = await prepareResponse.json();
  if (!prepareResult.success) {
    throw new Error(prepareResult.message);
  }
  
  const transactionData = prepareResult.data.transaction;
  
  // 2. Sign transaction trên frontend
  const wallet = new ethers.Wallet(privateKey);
  const provider = new ethers.JsonRpcProvider(
    'https://sepolia.infura.io/v3/YOUR_KEY' // Hoặc RPC URL từ config
  );
  const connectedWallet = wallet.connect(provider);
  
  // Ký transaction
  const signedTx = await connectedWallet.signTransaction(transactionData);
  
  // 3. Gửi signed transaction lên backend
  const sendResponse = await fetch(
    'http://127.0.0.1:8000/api/transaction/send',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        signed_transaction: signedTx,
        network: network
      })
    }
  );
  
  const sendResult = await sendResponse.json();
  if (!sendResult.success) {
    throw new Error(sendResult.message);
  }
  
  return sendResult.data.transaction_hash;
}

// Sử dụng
const txHash = await approveToken(
  lensTokenAddress,
  paymentContractAddress,
  '100.0', // amount in LENS
  walletAddress,
  privateKey,
  'sepolia'
);

console.log('Transaction Hash:', txHash);
```

### 2.2. Initiate Payment

**Frontend Code:**
```javascript
import { ethers } from 'ethers';

async function initiatePayment(
  paymentContractAddress,
  orderId,
  amount,
  ipfsHash,
  fromAddress,
  privateKey,
  network = 'sepolia'
) {
  // 1. Lấy contract ABI
  const contractInfo = await getContractInfo(network);
  const paymentABI = contractInfo.abis.LensArtPayment;
  
  // 2. Tạo contract instance
  const provider = new ethers.JsonRpcProvider(/* RPC URL */);
  const wallet = new ethers.Wallet(privateKey, provider);
  const paymentContract = new ethers.Contract(
    paymentContractAddress,
    paymentABI,
    wallet
  );
  
  // 3. Encode function call
  const amountWei = ethers.parseEther(amount);
  const txData = paymentContract.interface.encodeFunctionData(
    'initiatePayment',
    [orderId, amountWei, ipfsHash]
  );
  
  // 4. Prepare transaction
  const prepareResponse = await fetch(
    'http://127.0.0.1:8000/api/transaction/prepare/payment',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        payment_contract: paymentContractAddress,
        order_id: orderId,
        amount: amount,
        ipfs_hash: ipfsHash,
        from_address: fromAddress,
        network: network
      })
    }
  );
  
  const prepareResult = await prepareResponse.json();
  const transactionData = prepareResult.data.transaction;
  
  // 5. Thêm encoded data
  transactionData.data = txData;
  
  // 6. Sign và gửi
  const signedTx = await wallet.signTransaction(transactionData);
  
  const sendResponse = await fetch(
    'http://127.0.0.1:8000/api/transaction/send',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        signed_transaction: signedTx,
        network: network
      })
    }
  );
  
  const sendResult = await sendResponse.json();
  return sendResult.data.transaction_hash;
}
```

### 2.3. Kiểm tra trạng thái Transaction

**Frontend Code:**
```javascript
async function getTransactionStatus(txHash, network = 'sepolia') {
  const response = await fetch(
    `http://127.0.0.1:8000/api/transaction/status/${txHash}?network=${network}`
  );
  const result = await response.json();
  
  if (result.success) {
    const status = result.data.status;
    // status: 'pending', 'confirmed', 'failed', 'not_found'
    return result.data;
  }
  
  throw new Error(result.message);
}

// Sử dụng
const status = await getTransactionStatus(txHash, 'sepolia');
console.log('Status:', status.status);
console.log('Block Number:', status.block_number);
```

### 2.4. Đọc dữ liệu từ Contract

**Frontend Code:**
```javascript
import { ethers } from 'ethers';

async function readContractData(
  contractAddress,
  functionName,
  params,
  network = 'sepolia'
) {
  // Lấy ABI
  const contractInfo = await getContractInfo(network);
  const abi = contractInfo.abis[/* contract name */];
  
  // Tạo contract instance (read-only)
  const provider = new ethers.JsonRpcProvider(/* RPC URL */);
  const contract = new ethers.Contract(contractAddress, abi, provider);
  
  // Gọi function
  const result = await contract[functionName](...params);
  
  return result;
}

// Ví dụ: Đọc payment info
const paymentInfo = await readContractData(
  paymentContractAddress,
  'getPayment',
  [orderId],
  'sepolia'
);

console.log('Payment:', paymentInfo);
```

---

## 3. IPFS Integration

### 3.1. Upload File (PDF, Image)

**API Endpoint:**
```
POST /api/ipfs/upload
```

**Frontend Code:**
```javascript
async function uploadFileToIPFS(file, metadata = {}) {
  const formData = new FormData();
  formData.append('file', file);
  formData.append('name', file.name);
  formData.append('metadata', JSON.stringify(metadata));
  
  const response = await fetch(
    'http://127.0.0.1:8000/api/ipfs/upload',
    {
      method: 'POST',
      body: formData
    }
  );
  
  const result = await response.json();
  
  if (result.success) {
    return {
      ipfsHash: result.data.ipfs_hash,
      ipfsUrl: result.data.ipfs_url,
      gatewayUrl: result.data.gateway_url
    };
  }
  
  throw new Error(result.message);
}

// Sử dụng
const fileInput = document.getElementById('fileInput');
const file = fileInput.files[0];

const ipfsResult = await uploadFileToIPFS(file, {
  type: 'order_receipt',
  orderId: 123
});

console.log('IPFS Hash:', ipfsResult.ipfsHash);
```

### 3.2. Upload JSON Metadata

**API Endpoint:**
```
POST /api/ipfs/upload-json
```

**Frontend Code:**
```javascript
async function uploadJSONToIPFS(data, metadata = {}) {
  const response = await fetch(
    'http://127.0.0.1:8000/api/ipfs/upload-json',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        data: data,
        name: metadata.name || 'data.json',
        metadata: metadata
      })
    }
  );
  
  const result = await response.json();
  
  if (result.success) {
    return {
      ipfsHash: result.data.ipfs_hash,
      ipfsUrl: result.data.ipfs_url
    };
  }
  
  throw new Error(result.message);
}

// Sử dụng
const orderMetadata = {
  orderId: 123,
  customerName: 'John Doe',
  items: [
    { id: 1, name: 'Product 1', price: 100 },
    { id: 2, name: 'Product 2', price: 200 }
  ],
  total: 300,
  timestamp: new Date().toISOString()
};

const ipfsResult = await uploadJSONToIPFS(orderMetadata, {
  name: 'order-metadata',
  type: 'order'
});

console.log('IPFS Hash:', ipfsResult.ipfsHash);
```

### 3.3. Retrieve File từ IPFS

**API Endpoint:**
```
GET /api/ipfs/retrieve/{hash}
GET /api/ipfs/retrieve-json/{hash}
```

**Frontend Code:**
```javascript
async function retrieveFileFromIPFS(ipfsHash) {
  const response = await fetch(
    `http://127.0.0.1:8000/api/ipfs/retrieve/${ipfsHash}`
  );
  const result = await response.json();
  
  if (result.success) {
    if (result.data.type === 'json') {
      // JSON data
      return result.data.data;
    } else {
      // File data (base64 encoded)
      const content = atob(result.data.content);
      return {
        content: content,
        contentType: result.data.content_type,
        size: result.data.size
      };
    }
  }
  
  throw new Error(result.message);
}

// Sử dụng
const fileData = await retrieveFileFromIPFS('Qm...');

// Hiển thị file
if (fileData.contentType.startsWith('image/')) {
  const img = document.createElement('img');
  img.src = `data:${fileData.contentType};base64,${btoa(fileData.content)}`;
  document.body.appendChild(img);
}
```

### 3.4. Retrieve JSON từ IPFS

**Frontend Code:**
```javascript
async function retrieveJSONFromIPFS(ipfsHash) {
  const response = await fetch(
    `http://127.0.0.1:8000/api/ipfs/retrieve-json/${ipfsHash}`
  );
  const result = await response.json();
  
  if (result.success) {
    return result.data.data;
  }
  
  throw new Error(result.message);
}

// Sử dụng
const metadata = await retrieveJSONFromIPFS('Qm...');
console.log('Metadata:', metadata);
```

---

## 4. Token ERC-20 Operations

### 4.1. Kiểm tra số dư Token

**API Endpoint:**
```
GET /api/token/balance?address=0x...&network=sepolia
```

**Frontend Code:**
```javascript
async function getTokenBalance(address, network = 'sepolia') {
  const response = await fetch(
    `http://127.0.0.1:8000/api/token/balance?address=${address}&network=${network}`
  );
  const result = await response.json();
  
  if (result.success) {
    return result.data.balance;
  }
  
  throw new Error(result.message);
}

// Sử dụng
const balance = await getTokenBalance('0x...', 'sepolia');
console.log('LENS Balance:', balance);
```

### 4.2. Kiểm tra Allowance

**API Endpoint:**
```
GET /api/token/allowance?owner_address=0x...&spender_address=0x...&network=sepolia
```

**Frontend Code:**
```javascript
async function getTokenAllowance(ownerAddress, spenderAddress, network = 'sepolia') {
  const response = await fetch(
    `http://127.0.0.1:8000/api/token/allowance?owner_address=${ownerAddress}&spender_address=${spenderAddress}&network=${network}`
  );
  const result = await response.json();
  
  if (result.success) {
    return result.data.allowance;
  }
  
  throw new Error(result.message);
}

// Sử dụng
const allowance = await getTokenAllowance(
  walletAddress,
  paymentContractAddress,
  'sepolia'
);
console.log('Allowance:', allowance);
```

### 4.3. Transfer Token

**Frontend Code:**
```javascript
import { ethers } from 'ethers';

async function transferToken(
  toAddress,
  amount,
  fromAddress,
  privateKey,
  network = 'sepolia'
) {
  // 1. Lấy contract info
  const contractInfo = await getContractInfo(network);
  const tokenAddress = contractInfo.contracts.LENSToken;
  const tokenABI = contractInfo.abis.LENSToken;
  
  // 2. Prepare transaction
  const prepareResponse = await fetch(
    'http://127.0.0.1:8000/api/token/prepare-transfer',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        token_contract: tokenAddress,
        to_address: toAddress,
        amount: amount,
        from_address: fromAddress,
        network: network
      })
    }
  );
  
  const prepareResult = await prepareResponse.json();
  const transactionData = prepareResult.data.transaction;
  
  // 3. Sign và gửi
  const wallet = new ethers.Wallet(privateKey);
  const provider = new ethers.JsonRpcProvider(/* RPC URL */);
  const connectedWallet = wallet.connect(provider);
  
  const signedTx = await connectedWallet.signTransaction(transactionData);
  
  const sendResponse = await fetch(
    'http://127.0.0.1:8000/api/transaction/send',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        signed_transaction: signedTx,
        network: network
      })
    }
  );
  
  const sendResult = await sendResponse.json();
  return sendResult.data.transaction_hash;
}
```

---

## 5. NFT Operations

### 5.1. Mint NFT cho Order

**API Endpoint:**
```
POST /api/nft/prepare-mint
```

**Frontend Code:**
```javascript
import { ethers } from 'ethers';

async function mintNFT(
  toAddress,
  orderId,
  ipfsHash,
  fromAddress,
  privateKey,
  network = 'sepolia'
) {
  // 1. Lấy contract info
  const contractInfo = await getContractInfo(network);
  const nftAddress = contractInfo.contracts.LensArtOrderNFT;
  const nftABI = contractInfo.abis.LensArtOrderNFT;
  
  // 2. Prepare transaction
  const prepareResponse = await fetch(
    'http://127.0.0.1:8000/api/nft/prepare-mint',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nft_contract: nftAddress,
        to_address: toAddress,
        order_id: orderId,
        ipfs_hash: ipfsHash,
        from_address: fromAddress,
        network: network
      })
    }
  );
  
  const prepareResult = await prepareResponse.json();
  const transactionData = prepareResult.data.transaction;
  
  // 3. Encode function call
  const provider = new ethers.JsonRpcProvider(/* RPC URL */);
  const wallet = new ethers.Wallet(privateKey, provider);
  const nftContract = new ethers.Contract(nftAddress, nftABI, wallet);
  
  const txData = nftContract.interface.encodeFunctionData(
    'mintOrderNFT',
    [toAddress, orderId, ipfsHash]
  );
  
  transactionData.data = txData;
  
  // 4. Sign và gửi
  const signedTx = await wallet.signTransaction(transactionData);
  
  const sendResponse = await fetch(
    'http://127.0.0.1:8000/api/transaction/send',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        signed_transaction: signedTx,
        network: network
      })
    }
  );
  
  const sendResult = await sendResponse.json();
  return sendResult.data.transaction_hash;
}
```

### 5.2. Lấy thông tin NFT

**Frontend Code:**
```javascript
import { ethers } from 'ethers';

async function getNFTInfo(tokenId, network = 'sepolia') {
  // Lấy contract info
  const contractInfo = await getContractInfo(network);
  const nftAddress = contractInfo.contracts.LensArtOrderNFT;
  const nftABI = contractInfo.abis.LensArtOrderNFT;
  
  // Tạo contract instance
  const provider = new ethers.JsonRpcProvider(/* RPC URL */);
  const nftContract = new ethers.Contract(nftAddress, nftABI, provider);
  
  // Gọi function
  const nftInfo = await nftContract.getOrderNFT(tokenId);
  
  return {
    tokenId: tokenId,
    orderId: nftInfo.orderId.toString(),
    ipfsHash: nftInfo.ipfsHash,
    mintedAt: new Date(nftInfo.mintedAt.toNumber() * 1000)
  };
}

// Sử dụng
const nftInfo = await getNFTInfo(1, 'sepolia');
console.log('NFT Info:', nftInfo);
```

### 5.3. Lấy Token ID theo Order ID

**Frontend Code:**
```javascript
async function getTokenIdByOrder(orderId, network = 'sepolia') {
  const contractInfo = await getContractInfo(network);
  const nftAddress = contractInfo.contracts.LensArtOrderNFT;
  const nftABI = contractInfo.abis.LensArtOrderNFT;
  
  const provider = new ethers.JsonRpcProvider(/* RPC URL */);
  const nftContract = new ethers.Contract(nftAddress, nftABI, provider);
  
  const tokenId = await nftContract.getTokenIdByOrder(orderId);
  
  return tokenId.toString();
}

// Sử dụng
const tokenId = await getTokenIdByOrder(123, 'sepolia');
console.log('Token ID:', tokenId);
```

---

## 6. Complete Payment Flow

**Ví dụ flow thanh toán hoàn chỉnh:**

```javascript
import { ethers } from 'ethers';

async function completePaymentFlow(orderId, amount, walletAddress, privateKey) {
  try {
    // 1. Lấy payment info từ backend
    const paymentInfoResponse = await fetch(
      `http://127.0.0.1:8000/api/crypto-payment/${orderId}/info?network=sepolia`
    );
    const paymentInfo = await paymentInfoResponse.json();
    
    const lensTokenAddress = paymentInfo.data.contracts.LENSToken;
    const paymentContractAddress = paymentInfo.data.contracts.LensArtPayment;
    const amountLENS = paymentInfo.data.total_price_lens;
    
    // 2. Upload order metadata lên IPFS
    const orderMetadata = {
      orderId: orderId,
      amount: amount,
      timestamp: new Date().toISOString()
    };
    const ipfsResult = await uploadJSONToIPFS(orderMetadata);
    const ipfsHash = ipfsResult.ipfsHash;
    
    // 3. Kiểm tra số dư
    const balance = await getTokenBalance(walletAddress, 'sepolia');
    if (parseFloat(balance) < parseFloat(amountLENS)) {
      throw new Error('Insufficient balance');
    }
    
    // 4. Kiểm tra allowance
    const allowance = await getTokenAllowance(
      walletAddress,
      paymentContractAddress,
      'sepolia'
    );
    
    // 5. Approve token (nếu cần)
    if (parseFloat(allowance) < parseFloat(amountLENS)) {
      const approveTxHash = await approveToken(
        lensTokenAddress,
        paymentContractAddress,
        amountLENS,
        walletAddress,
        privateKey,
        'sepolia'
      );
      
      // Đợi approve transaction được confirm
      let approveStatus = 'pending';
      while (approveStatus === 'pending') {
        await new Promise(resolve => setTimeout(resolve, 3000));
        const status = await getTransactionStatus(approveTxHash, 'sepolia');
        approveStatus = status.status;
      }
      
      if (approveStatus !== 'confirmed') {
        throw new Error('Approve transaction failed');
      }
    }
    
    // 6. Initiate payment
    const paymentTxHash = await initiatePayment(
      paymentContractAddress,
      orderId,
      amountLENS,
      ipfsHash,
      walletAddress,
      privateKey,
      'sepolia'
    );
    
    // 7. Đợi payment transaction được confirm
    let paymentStatus = 'pending';
    while (paymentStatus === 'pending') {
      await new Promise(resolve => setTimeout(resolve, 3000));
      const status = await getTransactionStatus(paymentTxHash, 'sepolia');
      paymentStatus = status.status;
    }
    
    if (paymentStatus !== 'confirmed') {
      throw new Error('Payment transaction failed');
    }
    
    // 8. Mint NFT cho order (nếu cần)
    // Chỉ owner mới có thể mint NFT
    // const nftTxHash = await mintNFT(
    //   walletAddress,
    //   orderId,
    //   ipfsHash,
    //   ownerAddress,
    //   ownerPrivateKey,
    //   'sepolia'
    // );
    
    return {
      success: true,
      paymentTxHash: paymentTxHash,
      ipfsHash: ipfsHash
    };
    
  } catch (error) {
    console.error('Payment flow error:', error);
    throw error;
  }
}

// Sử dụng
const result = await completePaymentFlow(123, 100, walletAddress, privateKey);
console.log('Payment completed:', result);
```

---

## 7. Security Best Practices

### 7.1. Mã hóa Private Key

```javascript
import CryptoJS from 'crypto-js';

function encryptPrivateKey(privateKey, password) {
  return CryptoJS.AES.encrypt(privateKey, password).toString();
}

function decryptPrivateKey(encryptedKey, password) {
  const bytes = CryptoJS.AES.decrypt(encryptedKey, password);
  return bytes.toString(CryptoJS.enc.Utf8);
}

// Sử dụng
const encrypted = encryptPrivateKey(privateKey, userPassword);
localStorage.setItem('encryptedPrivateKey', encrypted);

// Giải mã khi cần
const decrypted = decryptPrivateKey(encrypted, userPassword);
```

### 7.2. Validation

```javascript
// Validate Ethereum address
function isValidAddress(address) {
  return /^0x[a-fA-F0-9]{40}$/.test(address);
}

// Validate private key
function isValidPrivateKey(privateKey) {
  const cleaned = privateKey.replace('0x', '');
  return /^[a-fA-F0-9]{64}$/.test(cleaned);
}
```

### 7.3. Error Handling

```javascript
async function safeTransaction(txFunction) {
  try {
    const result = await txFunction();
    return { success: true, data: result };
  } catch (error) {
    console.error('Transaction error:', error);
    return {
      success: false,
      error: error.message,
      code: error.code
    };
  }
}
```

---

## 8. API Reference

### Wallet APIs
- `GET /api/wallet/info` - Get wallet info structure
- `POST /api/wallet/validate-address` - Validate address
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

---

## 9. Testing

### 9.1. Test Wallet Creation

```javascript
// Test tạo wallet
const wallet = ethers.Wallet.createRandom();
console.log('Address:', wallet.address);
console.log('Private Key:', wallet.privateKey);
```

### 9.2. Test Balance Check

```javascript
// Test kiểm tra số dư
const balance = await getWalletBalance('0x...', 'sepolia');
console.log('Balance:', balance);
```

### 9.3. Test IPFS Upload

```javascript
// Test upload file
const file = new File(['test content'], 'test.txt', { type: 'text/plain' });
const result = await uploadFileToIPFS(file);
console.log('IPFS Hash:', result.ipfsHash);
```

---

## 10. Troubleshooting

### Lỗi thường gặp:

1. **Transaction failed**: Kiểm tra số dư ETH (để trả gas), số dư token, allowance
2. **IPFS upload failed**: Kiểm tra API keys, file size, network connection
3. **Contract call failed**: Kiểm tra contract address, ABI, network
4. **Private key invalid**: Kiểm tra format (64 hex characters)

---

## 11. Demo Checklist

- [ ] Tạo wallet mới
- [ ] Import wallet từ private key
- [ ] Kiểm tra số dư (ETH + LENS)
- [ ] Approve token
- [ ] Initiate payment
- [ ] Upload file lên IPFS (PDF, Image)
- [ ] Upload JSON metadata lên IPFS
- [ ] Retrieve file từ IPFS
- [ ] Retrieve JSON từ IPFS
- [ ] Transfer token
- [ ] Mint NFT
- [ ] Xem NFT info
- [ ] Kiểm tra transaction status

---

**Lưu ý:** Tài liệu này cung cấp hướng dẫn tích hợp frontend với backend. Đảm bảo cài đặt các dependencies cần thiết:
- `ethers` (v6)
- `crypto-js` (cho mã hóa)

Và cấu hình các biến môi trường cần thiết trong file `.env` của backend.

