const hre = require("hardhat");

async function main() {
  const [deployer] = await hre.ethers.getSigners();
  
  console.log("Deploying contracts with the account:", deployer.address);
  
  // Check balance
  const balance = await hre.ethers.provider.getBalance(deployer.address);
  const balanceInEth = hre.ethers.formatEther(balance);
  console.log("Account balance:", balanceInEth, "ETH");
  
  // Check if balance is sufficient
  if (hre.network.name !== "hardhat" && hre.network.name !== "localhost") {
    const minBalance = hre.ethers.parseEther("0.01"); // Minimum 0.01 ETH
    if (balance < minBalance) {
      console.error("\n✗ ERROR: Insufficient Sepolia ETH balance!");
      console.error("   Current balance:", balanceInEth, "ETH");
      console.error("   Required: At least 0.01 ETH for gas fees");
      console.error("\n📝 How to get Sepolia ETH:");
      console.error("   1. Visit: https://sepoliafaucet.com/");
      console.error("   2. Or: https://www.infura.io/faucet/sepolia");
      console.error("   3. Connect your wallet:", deployer.address);
      console.error("   4. Request Sepolia ETH");
      console.error("   5. Wait a few minutes for the ETH to arrive");
      console.error("\n   Then run this command again.\n");
      process.exit(1);
    }
    console.log("✓ Balance sufficient for deployment");
  }

  // Declare variables for contract addresses
  let lensTokenAddress;
  let paymentContractAddress;
  let nftContractAddress;
  let paymentContract;
  let lensTokenDeploymentTx;
  let paymentDeploymentTx;
  let nftDeploymentTx;
  const feeRecipient = deployer.address;

  // Deploy LENSToken
  console.log("\n=== Deploying LENSToken ===");
  try {
    const LENSToken = await hre.ethers.getContractFactory("LENSToken");
    console.log("Deploying...");
    const lensToken = await LENSToken.deploy(deployer.address);
    lensTokenDeploymentTx = lensToken.deploymentTransaction();
    console.log("Waiting for deployment...");
    await lensToken.waitForDeployment();
    lensTokenAddress = await lensToken.getAddress();
    console.log("✓ LENSToken deployed to:", lensTokenAddress);
    if (lensTokenDeploymentTx) {
      console.log("   Transaction hash:", lensTokenDeploymentTx.hash);
    }
  } catch (error) {
    console.error("✗ Error deploying LENSToken:", error.message);
    throw error;
  }

  // Deploy LensArtPayment
  console.log("\n=== Deploying LensArtPayment ===");
  // Fee recipient sẽ là một address khác (có thể là deployer hoặc một address khác)
  // Trong trường hợp này, chúng ta dùng deployer làm fee recipient
  
  try {
    const LensArtPayment = await hre.ethers.getContractFactory("LensArtPayment");
    console.log("Deploying with parameters:");
    console.log("  - Token address:", lensTokenAddress);
    console.log("  - Fee recipient:", feeRecipient);
    console.log("  - Owner:", deployer.address);
    paymentContract = await LensArtPayment.deploy(
      lensTokenAddress,
      feeRecipient,
      deployer.address
    );
    paymentDeploymentTx = paymentContract.deploymentTransaction();
    console.log("Waiting for deployment...");
    await paymentContract.waitForDeployment();
    paymentContractAddress = await paymentContract.getAddress();
    console.log("✓ LensArtPayment deployed to:", paymentContractAddress);
    if (paymentDeploymentTx) {
      console.log("   Transaction hash:", paymentDeploymentTx.hash);
    }
  } catch (error) {
    console.error("✗ Error deploying LensArtPayment:", error.message);
    throw error;
  }

  // Deploy LensArtOrderNFT
  console.log("\n=== Deploying LensArtOrderNFT ===");
  try {
    const LensArtOrderNFT = await hre.ethers.getContractFactory("LensArtOrderNFT");
    console.log("Deploying with owner:", deployer.address);
    const nftContract = await LensArtOrderNFT.deploy(deployer.address);
    nftDeploymentTx = nftContract.deploymentTransaction();
    console.log("Waiting for deployment...");
    await nftContract.waitForDeployment();
    nftContractAddress = await nftContract.getAddress();
    console.log("✓ LensArtOrderNFT deployed to:", nftContractAddress);
    if (nftDeploymentTx) {
      console.log("   Transaction hash:", nftDeploymentTx.hash);
    }
  } catch (error) {
    console.error("✗ Error deploying LensArtOrderNFT:", error.message);
    throw error;
  }

  // Save deployment info
  console.log("\n=== Deployment Summary ===");
  console.log("Network:", hre.network.name);
  console.log("Deployer:", deployer.address);
  console.log("\nContract Addresses:");
  console.log("- LENSToken:", lensTokenAddress);
  console.log("- LensArtPayment:", paymentContractAddress);
  console.log("- LensArtOrderNFT:", nftContractAddress);
  console.log("- Fee Recipient:", feeRecipient);

  // Save to file for later use
  const fs = require("fs");
  const deploymentInfo = {
    network: hre.network.name,
    deployer: deployer.address,
    contracts: {
      LENSToken: lensTokenAddress,
      LensArtPayment: paymentContractAddress,
      LensArtOrderNFT: nftContractAddress,
      FeeRecipient: feeRecipient
    },
    timestamp: new Date().toISOString()
  };

  const deploymentsDir = "./deployments";
  if (!fs.existsSync(deploymentsDir)) {
    fs.mkdirSync(deploymentsDir, { recursive: true });
  }

  fs.writeFileSync(
    `${deploymentsDir}/${hre.network.name}.json`,
    JSON.stringify(deploymentInfo, null, 2)
  );

  console.log(`\nDeployment info saved to: ${deploymentsDir}/${hre.network.name}.json`);

  // Wait for block confirmations
  if (hre.network.name !== "hardhat" && hre.network.name !== "localhost") {
    console.log("\n⏳ Waiting for block confirmations...");
    try {
      if (lensTokenDeploymentTx) await lensTokenDeploymentTx.wait(5);
      if (paymentDeploymentTx) await paymentDeploymentTx.wait(5);
      if (nftDeploymentTx) await nftDeploymentTx.wait(5);
      console.log("✓ All contracts confirmed!");
    } catch (error) {
      console.warn("⚠️  Could not wait for confirmations:", error.message);
    }
  }
}

main()
  .then(() => {
    console.log("\n✓ Deployment completed successfully!");
    process.exit(0);
  })
  .catch((error) => {
    console.error("\n✗ Deployment failed!");
    console.error("Error details:", error.message);
    
    // Check for common errors and provide helpful messages
    if (error.message.includes("insufficient funds")) {
      console.error("\n💡 Solution:");
      console.error("   You need Sepolia ETH to pay for gas fees.");
      console.error("   Get Sepolia ETH from: https://sepoliafaucet.com/");
    } else if (error.message.includes("nonce")) {
      console.error("\n💡 Solution:");
      console.error("   Transaction nonce error. Wait a moment and try again.");
    } else if (error.message.includes("timeout") || error.message.includes("TIMEOUT")) {
      console.error("\n💡 Solution:");
      console.error("   RPC connection timeout. Check your internet connection");
      console.error("   or try updating SEPOLIA_RPC_URL in .env file.");
    }
    
    if (error.transaction) {
      console.error("Transaction hash:", error.transaction.hash);
    }
    if (error.reason) {
      console.error("Reason:", error.reason);
    }
    process.exit(1);
  });

