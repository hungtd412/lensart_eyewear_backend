# Quick Start - Deploy và Test trên Sepolia Testnet

## Bước 1: Tạo file .env

Tạo file `.env` trong thư mục `contracts`:

```env
SEPOLIA_RPC_URL=https://rpc.sepolia.org
PRIVATE_KEY=your_64_character_private_key_without_0x
ETHERSCAN_API_KEY=your_etherscan_api_key
```

## Bước 2: Lấy Sepolia ETH

1. Truy cập [Sepolia Faucet](https://sepoliafaucet.com/)
2. Connect wallet và request Sepolia ETH
3. Đợi vài phút để nhận ETH

## Bước 3: Deploy Contracts

```bash
npm run deploy:sepolia
```

## Bước 4: Verify Contracts

```bash
npm run verify:sepolia
```

## Bước 5: Test trên Testnet

```bash
npm run testnet-test
```

## Lưu Ý

- Private key phải có đúng 64 ký tự (không có prefix `0x`)
- Cần có Sepolia ETH để trả gas fee
- Etherscan API key để verify contracts (optional nhưng recommended)

Xem chi tiết tại [DEPLOY.md](./DEPLOY.md)

