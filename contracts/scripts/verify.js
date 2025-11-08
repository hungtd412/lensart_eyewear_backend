const hre = require("hardhat");
const fs = require("fs");

async function main() {
  const network = hre.network.name;
  const deploymentsFile = `./deployments/${network}.json`;

  if (!fs.existsSync(deploymentsFile)) {
    console.error(`Deployment file not found: ${deploymentsFile}`);
    process.exit(1);
  }

  const deploymentInfo = JSON.parse(fs.readFileSync(deploymentsFile, "utf8"));
  console.log("Verifying contracts on", network);

  // Verify LENSToken
  console.log("\nVerifying LENSToken:", deploymentInfo.contracts.LENSToken);
  try {
    await hre.run("verify:verify", {
      address: deploymentInfo.contracts.LENSToken,
      constructorArguments: [deploymentInfo.deployer],
    });
    console.log("✓ LENSToken verified");
  } catch (error) {
    if (error.message.includes("Already Verified")) {
      console.log("✓ LENSToken already verified");
    } else {
      console.error("✗ LENSToken:", error.message);
    }
  }

  // Verify LensArtPayment
  console.log("\nVerifying LensArtPayment:", deploymentInfo.contracts.LensArtPayment);
  try {
    await hre.run("verify:verify", {
      address: deploymentInfo.contracts.LensArtPayment,
      constructorArguments: [
        deploymentInfo.contracts.LENSToken,
        deploymentInfo.contracts.FeeRecipient,
        deploymentInfo.deployer
      ],
    });
    console.log("✓ LensArtPayment verified");
  } catch (error) {
    if (error.message.includes("Already Verified")) {
      console.log("✓ LensArtPayment already verified");
    } else {
      console.error("✗ LensArtPayment:", error.message);
    }
  }

  // Verify LensArtOrderNFT
  console.log("\nVerifying LensArtOrderNFT:", deploymentInfo.contracts.LensArtOrderNFT);
  try {
    await hre.run("verify:verify", {
      address: deploymentInfo.contracts.LensArtOrderNFT,
      constructorArguments: [deploymentInfo.deployer],
    });
    console.log("✓ LensArtOrderNFT verified");
  } catch (error) {
    if (error.message.includes("Already Verified")) {
      console.log("✓ LensArtOrderNFT already verified");
    } else {
      console.error("✗ LensArtOrderNFT:", error.message);
    }
  }

  console.log("\nVerification complete");
}

main()
  .then(() => {
    setTimeout(() => process.exit(0), 1000);
  })
  .catch((error) => {
    console.error("Error:", error.message);
    setTimeout(() => process.exit(1), 1000);
  });
