# Contract Exports

Thư mục này chứa các file export của smart contracts để frontend sử dụng.

## Files

- `frontend-config-{network}.json` - File cấu hình đầy đủ cho frontend (addresses + ABIs)
- `contracts-{network}.json` - File đầy đủ với tất cả thông tin deployment
- `{ContractName}-{network}.json` - File riêng lẻ cho từng contract

## Cách sử dụng

### 1. Export contracts

```bash
cd contracts
npm run export:sepolia  # Export cho network Sepolia
npm run export:local    # Export cho network local/hardhat
```

### 2. Frontend có thể lấy thông tin qua API

```javascript
// Lấy thông tin tất cả contracts
const response = await fetch('http://your-backend-url/api/blockchain/contracts?network=sepolia');
const data = await response.json();

// Sử dụng trong frontend
const { contracts, abis, chainId, rpcUrl } = data.data;
```

### 3. Hoặc import trực tiếp từ file JSON

```javascript
import contractConfig from './contracts/exports/frontend-config-sepolia.json';

const { contracts, abis } = contractConfig;
```

## Networks

- `sepolia` - Sepolia testnet (Chain ID: 11155111)
- `hardhat` - Local Hardhat network (Chain ID: 1337)

## Contract Addresses (Sepolia)

- **LENSToken**: 0xB7Dd8a419D96aB1864C244663a050d5B5D6856d4
- **LensArtPayment**: 0x368df4D959d2b583EBC9dD569e00e632f9f1DB8b
- **LensArtOrderNFT**: 0xff9160e0c3dE589fc713d9FB4fcf58FD9cBF8DC3

