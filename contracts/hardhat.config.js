require("@nomicfoundation/hardhat-toolbox");
require("@nomicfoundation/hardhat-verify");
require("@tenderly/hardhat-tenderly");
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
  networks.sepolia = {
    url: process.env.SEPOLIA_RPC_URL || "https://ethereum-sepolia-rpc.publicnode.com",
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
  tenderly: {
    project: process.env.TENDERLY_PROJECT || "leansart",
    username: process.env.TENDERLY_USERNAME || "trinhhhh453543",
  },
  paths: {
    sources: "./contracts",
    tests: "./test",
    cache: "./cache",
    artifacts: "./artifacts"
  }
};
