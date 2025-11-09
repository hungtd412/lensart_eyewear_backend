require("@nomicfoundation/hardhat-toolbox");
require("@nomicfoundation/hardhat-verify");
// Tắt tự động verify trên Tenderly (chỉ dùng Tenderly RPC)
// require("@tenderly/hardhat-tenderly");
require("dotenv").config();

function getPrivateKey() {
  const pk = process.env.PRIVATE_KEY;
  if (!pk) return null;
  const cleaned = pk.startsWith('0x') ? pk.slice(2) : pk;
  return cleaned.length === 64 ? pk : null;
}

const privateKey = getPrivateKey();
const networks = { hardhat: { chainId: 1337 } };

if (process.env.TENDERLY_VIRTUAL_MAINNET_RPC) {
  networks.virtualMainnet = {
    url: process.env.TENDERLY_VIRTUAL_MAINNET_RPC,
    accounts: privateKey ? [privateKey] : []
  };
}

if (privateKey) {
  // Tenderly RPC URL mặc định cho Sepolia
  const defaultSepoliaRpc = "https://virtual.rpc.tenderly.co/trinhhhh453543/project/public/crypto";
  
  networks.sepolia = {
    url: process.env.SEPOLIA_RPC_URL || defaultSepoliaRpc,
    accounts: [privateKey],
    chainId: 11155111
  };
}

module.exports = {
  solidity: {
    version: "0.8.20",
    settings: { optimizer: { enabled: true, runs: 200 } }
  },
  networks,
  etherscan: { apiKey: process.env.ETHERSCAN_API_KEY },
  // Tắt cấu hình Tenderly (chỉ dùng RPC, không verify)
  // tenderly: {
  //   project: process.env.TENDERLY_PROJECT || "leansart",
  //   username: process.env.TENDERLY_USERNAME || "trinhhhh453543",
  // },
  paths: {
    sources: "./contracts",
    tests: "./test",
    cache: "./cache",
    artifacts: "./artifacts"
  }
};
