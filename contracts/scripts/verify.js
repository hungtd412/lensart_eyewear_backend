const hre = require("hardhat");
const fs = require("fs");

async function main() {
  const network = hre.network.name;
  const deploymentsFile = `./deployments/${network}.json`;

  if (!fs.existsSync(deploymentsFile)) {
    console.error(`✗ Deployment file not found: ${deploymentsFile}`);
    console.error("   Please deploy contracts first: npm run deploy:sepolia");
    process.exit(1);
  }

  const deploymentInfo = JSON.parse(fs.readFileSync(deploymentsFile, "utf8"));
  console.log("\n=== Verifying Contracts ===");
  console.log("Network:", network);
  console.log("Chain ID:", hre.network.config.chainId);
  
  // Note: Contracts deployed on Tenderly virtual network don't need verification
  // Tenderly automatically shows contract source code in dashboard
  console.log("\n💡 Note: Contracts are deployed on Tenderly virtual network");
  console.log("   Tenderly automatically displays contract source code in dashboard");
  console.log("   No manual verification needed like on Etherscan");
  
  const tenderlyUsername = process.env.TENDERLY_USERNAME || "trinhhhh453543";
  const tenderlyProject = process.env.TENDERLY_PROJECT || "crypto";
  const tenderlyDashboard = `https://dashboard.tenderly.co/${tenderlyUsername}/${tenderlyProject}`;
  console.log(`   View contracts on Tenderly: ${tenderlyDashboard}`);

  // Verify LENSToken
  console.log("\n[1/3] Verifying LENSToken...");
  console.log("   Address:", deploymentInfo.contracts.LENSToken);
  try {
    await hre.run("verify:verify", {
      address: deploymentInfo.contracts.LENSToken,
      constructorArguments: [deploymentInfo.deployer],
    });
    console.log("   ✓ LENSToken verified");
  } catch (error) {
    const errorMsg = error.message || error.toString();
    if (errorMsg.includes("Already Verified") || errorMsg.includes("already verified")) {
      console.log("   ✓ LENSToken already verified");
    } else {
      console.error("   ✗ LENSToken verification failed:", errorMsg);
      console.log("   💡 View contract on Tenderly Dashboard:");
      const tenderlyUsername = process.env.TENDERLY_USERNAME || "trinhhhh453543";
      const tenderlyProject = process.env.TENDERLY_PROJECT || "crypto";
      console.log(`      https://dashboard.tenderly.co/${tenderlyUsername}/${tenderlyProject}/contract/${network}/${deploymentInfo.contracts.LENSToken}`);
    }
  }

  // Verify LensArtPayment
  console.log("\n[2/3] Verifying LensArtPayment...");
  console.log("   Address:", deploymentInfo.contracts.LensArtPayment);
  try {
    await hre.run("verify:verify", {
      address: deploymentInfo.contracts.LensArtPayment,
      constructorArguments: [
        deploymentInfo.contracts.LENSToken,
        deploymentInfo.contracts.FeeRecipient,
        deploymentInfo.deployer
      ],
    });
    console.log("   ✓ LensArtPayment verified");
  } catch (error) {
    const errorMsg = error.message || error.toString();
    if (errorMsg.includes("Already Verified") || errorMsg.includes("already verified")) {
      console.log("   ✓ LensArtPayment already verified");
    } else {
      console.error("   ✗ LensArtPayment verification failed:", errorMsg);
      console.log("   💡 View contract on Tenderly Dashboard:");
      const tenderlyUsername = process.env.TENDERLY_USERNAME || "trinhhhh453543";
      const tenderlyProject = process.env.TENDERLY_PROJECT || "crypto";
      console.log(`      https://dashboard.tenderly.co/${tenderlyUsername}/${tenderlyProject}/contract/${network}/${deploymentInfo.contracts.LensArtPayment}`);
    }
  }

  // Verify LensArtOrderNFT
  console.log("\n[3/3] Verifying LensArtOrderNFT...");
  console.log("   Address:", deploymentInfo.contracts.LensArtOrderNFT);
  try {
    await hre.run("verify:verify", {
      address: deploymentInfo.contracts.LensArtOrderNFT,
      constructorArguments: [deploymentInfo.deployer],
    });
    console.log("   ✓ LensArtOrderNFT verified");
  } catch (error) {
    const errorMsg = error.message || error.toString();
    if (errorMsg.includes("Already Verified") || errorMsg.includes("already verified")) {
      console.log("   ✓ LensArtOrderNFT already verified");
    } else {
      console.error("   ✗ LensArtOrderNFT verification failed:", errorMsg);
      console.log("   💡 View contract on Tenderly Dashboard:");
      const tenderlyUsername = process.env.TENDERLY_USERNAME || "trinhhhh453543";
      const tenderlyProject = process.env.TENDERLY_PROJECT || "crypto";
      console.log(`      https://dashboard.tenderly.co/${tenderlyUsername}/${tenderlyProject}/contract/${network}/${deploymentInfo.contracts.LensArtOrderNFT}`);
    }
  }

  console.log("\n=== Verification Summary ===");
  console.log("Contract Addresses:");
  console.log("   LENSToken:", deploymentInfo.contracts.LENSToken);
  console.log("   LensArtPayment:", deploymentInfo.contracts.LensArtPayment);
  console.log("   LensArtOrderNFT:", deploymentInfo.contracts.LensArtOrderNFT);
  const tenderlyUsername = process.env.TENDERLY_USERNAME || "trinhhhh453543";
  const tenderlyProject = process.env.TENDERLY_PROJECT || "crypto";
  const tenderlyDashboard = `https://dashboard.tenderly.co/${tenderlyUsername}/${tenderlyProject}`;
  
  console.log("\n📄 View contracts on Tenderly Dashboard:");
  console.log(`   ${tenderlyDashboard}`);
  console.log("\n💡 Note: Contracts deployed on Tenderly virtual network");
  console.log("   Tenderly automatically displays contract source code - no manual verification needed");
  console.log("\n✓ Verification process complete");
}

main()
  .then(() => {
    setTimeout(() => process.exit(0), 1000);
  })
  .catch((error) => {
    console.error("Error:", error.message);
    setTimeout(() => process.exit(1), 1000);
  });
