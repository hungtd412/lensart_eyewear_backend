# Hướng Dẫn Verify Contracts

## Verify Tất Cả Contracts

```bash
npm run verify:sepolia
```

## Verify Từng Contract Riêng Lẻ

### Verify LENSToken
```bash
npm run verify:token
```

### Verify LensArtPayment
```bash
npm run verify:payment
```

### Verify LensArtOrderNFT
```bash
npm run verify:nft
```

## Lưu Ý

- Đảm bảo đã có `ETHERSCAN_API_KEY` trong file `.env`
- Nếu báo lỗi "does not have bytecode", đợi 1-2 phút rồi chạy lại
- Contracts đã verify có thể xem trên [Sepolia Etherscan](https://sepolia.etherscan.io/)

## Contract Addresses

- **LENSToken**: `0xB7Dd8a419D96aB1864C244663a050d5B5D6856d4`
- **LensArtPayment**: `0x368df4D959d2b583EBC9dD569e00e632f9f1DB8b`
- **LensArtOrderNFT**: `0xff9160e0c3dE589fc713d9FB4fcf58FD9cBF8DC3`

