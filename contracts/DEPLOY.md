# Hướng Dẫn Deploy và Test Smart Contracts trên Testnet

## Yêu Cầu

1. Node.js và npm đã được cài đặt
2. Metamask hoặc ví crypto với private key
3. Sepolia ETH để trả gas fee (có thể lấy tại [Sepolia Faucet](https://sepoliafaucet.com/))
4. Etherscan API Key (để verify contracts) - Đăng ký tại [Etherscan](https://etherscan.io/apis)

## Bước 1: Cài Đặt Dependencies

```bash
cd contracts
npm install
```

## Bước 2: Cấu Hình Môi Trường

Tạo file `.env` trong thư mục `contracts` với nội dung sau:

```env
# Network Configuration
SEPOLIA_RPC_URL=https://rpc.sepolia.org
PRIVATE_KEY=your_private_key_here_without_0x_prefix

# Etherscan API Key (for contract verification)
ETHERSCAN_API_KEY=your_etherscan_api_key_here
```

### Lấy Sepolia RPC URL

Bạn có thể sử dụng:
- Public RPC: `https://rpc.sepolia.org`   https://ethereum-sepolia-rpc.publicnode.com
- Alchemy: Tạo project tại [Alchemy](https://www.alchemy.com/) và lấy Sepolia RPC URL
- Infura: Tạo project tại [Infura](https://www.infura.io/) và lấy Sepolia RPC URL

### Lấy Private Key

1. Mở Metamask
2. Vào Settings → Security & Privacy
3. Export Private Key (NHỚ BẢO MẬT PRIVATE KEY!)  c431a178f5c154a3c20fc0aadc30d9afe4eb609642f08a928be3630de14ff00a
4. Copy private key và paste vào `.env` (không có prefix `0x`)

### Lấy Etherscan API Key

1. Đăng ký tài khoản tại [Etherscan](https://etherscan.io/register)
2. Vào [API Keys](https://etherscan.io/myapikey)
3. Tạo API key mới
4. Copy API key vào `.env`

## Bước 3: Kiểm Tra Số Dư Sepolia ETH

Đảm bảo bạn có đủ Sepolia ETH để deploy contracts. Bạn có thể kiểm tra tại:
- [Sepolia Etherscan](https://sepolia.etherscan.io/)

Nếu chưa có, lấy từ các faucet:
- [Alchemy Sepolia Faucet](https://sepoliafaucet.com/)
- [Infura Sepolia Faucet](https://www.infura.io/faucet/sepolia)

## Bước 4: Deploy Contracts

### Compile Contracts

```bash
npm run compile
```

### Deploy lên Sepolia Testnet

```bash
npm run deploy:sepolia
```

Sau khi deploy thành công, bạn sẽ thấy các thông tin:
- Địa chỉ của LENSToken
- Địa chỉ của LensArtPayment
- Địa chỉ của LensArtOrderNFT
- Thông tin deployment được lưu trong `deployments/sepolia.json`

## Bước 5: Verify Contracts trên Etherscan

Sau khi deploy, verify contracts để có thể xem source code trên Etherscan:

```bash
npm run verify:sepolia
```

Sau khi verify thành công, bạn có thể xem contracts trên Etherscan:
- Truy cập [Sepolia Etherscan](https://sepolia.etherscan.io/)
- Tìm địa chỉ contract
- Xem tab "Contract" để thấy verified source code

## Bước 6: Test Contracts trên Testnet

Chạy script test để kiểm tra các functions trên testnet:

```bash
npm run testnet-test
```

Script này sẽ:
1. Kiểm tra token balance
2. Transfer tokens
3. Initiate payment
4. Confirm payment
5. Mint NFT cho order

## Cấu Trúc Deployment

Sau khi deploy, thông tin sẽ được lưu trong file `deployments/sepolia.json`:

```json
{
  "network": "sepolia",
  "deployer": "0x...",
  "contracts": {
    "LENSToken": "0x...",
    "LensArtPayment": "0x...",
    "LensArtOrderNFT": "0x...",
    "FeeRecipient": "0x..."
  },
  "timestamp": "2024-01-01T00:00:00.000Z"
}
```

## Troubleshooting

### Lỗi: Insufficient funds
- **Nguyên nhân**: Không đủ Sepolia ETH để trả gas fee
- **Giải pháp**: Lấy thêm Sepolia ETH từ faucet

### Lỗi: Nonce too high
- **Nguyên nhân**: Transaction nonce không khớp
- **Giải pháp**: Đợi một chút và thử lại, hoặc reset nonce trong Metamask

### Lỗi: Contract verification failed
- **Nguyên nhân**: Constructor arguments không khớp hoặc contract đã được verify
- **Giải pháp**: Kiểm tra lại constructor arguments trong file deployment

### Lỗi: Private key invalid
- **Nguyên nhân**: Private key không đúng format
- **Giải pháp**: Đảm bảo private key không có prefix `0x` và có đủ 64 ký tự

## Tích Hợp với Frontend/Backend

Sau khi deploy, bạn có thể sử dụng các địa chỉ contract trong file `deployments/sepolia.json` để tích hợp với frontend/backend:

```javascript
const deploymentInfo = require('./deployments/sepolia.json');

const lensTokenAddress = deploymentInfo.contracts.LENSToken;
const paymentAddress = deploymentInfo.contracts.LensArtPayment;
const nftAddress = deploymentInfo.contracts.LensArtOrderNFT;
```

## Security Notes

⚠️ **QUAN TRỌNG**:
- **KHÔNG BAO GIỜ** commit file `.env` lên Git
- **KHÔNG BAO GIỜ** chia sẻ private key
- Chỉ sử dụng testnet để test, không dùng mainnet với private key thật
- Sử dụng environment variables riêng cho mỗi môi trường (dev, staging, production)

## Tham Khảo

- [Hardhat Documentation](https://hardhat.org/docs)
- [Sepolia Testnet](https://sepolia.dev/)
- [Etherscan API](https://docs.etherscan.io/)
- [OpenZeppelin Contracts](https://docs.openzeppelin.com/contracts)

