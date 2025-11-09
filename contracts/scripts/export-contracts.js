const hre = require("hardhat");
const fs = require("fs");
const path = require("path");

async function main() {
  const network = hre.network.name;
  const deploymentsFile = `./deployments/${network}.json`;

  if (!fs.existsSync(deploymentsFile)) {
    console.error(`✗ Deployment file not found: ${deploymentsFile}`);
    console.error("Please deploy contracts first using: npm run deploy:sepolia");
    process.exit(1);
  }

  // Read deployment info
  const deploymentInfo = JSON.parse(fs.readFileSync(deploymentsFile, "utf8"));

  // Read ABIs from artifacts
  const artifactsPath = "./artifacts/contracts";
  const contracts = {
    LENSToken: {
      address: deploymentInfo.contracts.LENSToken,
      abi: getABI(artifactsPath, "LENSToken.sol", "LENSToken")
    },
    LensArtPayment: {
      address: deploymentInfo.contracts.LensArtPayment,
      abi: getABI(artifactsPath, "LensArtPayment.sol", "LensArtPayment")
    },
    LensArtOrderNFT: {
      address: deploymentInfo.contracts.LensArtOrderNFT,
      abi: getABI(artifactsPath, "LensArtOrderNFT.sol", "LensArtOrderNFT")
    }
  };

  // Create export data
  const exportData = {
    network: network,
    chainId: getChainId(network),
    deployer: deploymentInfo.deployer,
    feeRecipient: deploymentInfo.contracts.FeeRecipient,
    contracts: contracts,
    rpcUrl: getRpcUrl(network),
    explorerUrl: getExplorerUrl(network),
    timestamp: new Date().toISOString()
  };

  // Create exports directory
  const exportsDir = "./exports";
  if (!fs.existsSync(exportsDir)) {
    fs.mkdirSync(exportsDir, { recursive: true });
  }

  // Save full export
  const exportFile = `${exportsDir}/contracts-${network}.json`;
  fs.writeFileSync(exportFile, JSON.stringify(exportData, null, 2));
  console.log(`✓ Contract info exported to: ${exportFile}`);

  // Save individual contract files for easier import
  Object.keys(contracts).forEach(contractName => {
    const contractFile = `${exportsDir}/${contractName}-${network}.json`;
    fs.writeFileSync(contractFile, JSON.stringify(contracts[contractName], null, 2));
    console.log(`✓ ${contractName} exported to: ${contractFile}`);
  });

  // Create a simplified version for frontend
  const frontendData = {
    network: network,
    chainId: getChainId(network),
    contracts: {
      LENSToken: contracts.LENSToken.address,
      LensArtPayment: contracts.LensArtPayment.address,
      LensArtOrderNFT: contracts.LensArtOrderNFT.address
    },
    abis: {
      LENSToken: contracts.LENSToken.abi,
      LensArtPayment: contracts.LensArtPayment.abi,
      LensArtOrderNFT: contracts.LensArtOrderNFT.abi
    },
    rpcUrl: getRpcUrl(network),
    explorerUrl: getExplorerUrl(network)
  };

  const frontendFile = `${exportsDir}/frontend-config-${network}.json`;
  fs.writeFileSync(frontendFile, JSON.stringify(frontendData, null, 2));
  console.log(`✓ Frontend config exported to: ${frontendFile}`);

  console.log("\n=== Export Summary ===");
  console.log("Network:", network);
  console.log("Chain ID:", getChainId(network));
  console.log("\nContract Addresses:");
  Object.keys(contracts).forEach(name => {
    console.log(`  ${name}: ${contracts[name].address}`);
  });
  console.log(`\n✓ All files exported successfully!`);
}

function getABI(artifactsPath, contractFile, contractName) {
  const artifactPath = path.join(artifactsPath, contractFile, `${contractName}.json`);
  if (!fs.existsSync(artifactPath)) {
    console.error(`✗ Artifact not found: ${artifactPath}`);
    return [];
  }
  const artifact = JSON.parse(fs.readFileSync(artifactPath, "utf8"));
  return artifact.abi || [];
}

function getChainId(network) {
  const chainIds = {
    hardhat: 1337,
    sepolia: 11155111,
    mainnet: 1,
    virtualMainnet: 1
  };
  return chainIds[network] || 1337;
}

function getRpcUrl(network) {
  if (network === "sepolia") {
    // Mặc định sử dụng Tenderly RPC
    return process.env.SEPOLIA_RPC_URL || "https://virtual.rpc.tenderly.co/trinhhhh453543/project/public/crypto";
  }
  if (network === "hardhat") {
    return "http://127.0.0.1:8545";
  }
  if (network === "virtualMainnet") {
    return process.env.TENDERLY_VIRTUAL_MAINNET_RPC || "";
  }
  return "";
}

function getExplorerUrl(network) {
  // Sử dụng Tenderly Dashboard thay vì Etherscan
  const tenderlyUsername = process.env.TENDERLY_USERNAME || "trinhhhh453543";
  const tenderlyProject = process.env.TENDERLY_PROJECT || "crypto";
  
  const explorers = {
    sepolia: `https://dashboard.tenderly.co/${tenderlyUsername}/${tenderlyProject}`,
    virtualMainnet: `https://dashboard.tenderly.co/${tenderlyUsername}/${tenderlyProject}`,
    mainnet: `https://dashboard.tenderly.co/${tenderlyUsername}/${tenderlyProject}`,
    hardhat: ""
  };
  return explorers[network] || "";
}

main()
  .then(() => process.exit(0))
  .catch((error) => {
    console.error("\n✗ Export failed!");
    console.error("Error details:", error.message);
    process.exit(1);
  });

