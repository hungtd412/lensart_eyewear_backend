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
  const address = deploymentInfo.contracts.LensArtPayment;
  const constructorArgs = [
    deploymentInfo.contracts.LENSToken,
    deploymentInfo.contracts.FeeRecipient,
    deploymentInfo.deployer
  ];

  console.log("Verifying LensArtPayment:");
  console.log("Address:", address);
  console.log("Constructor args:", constructorArgs);

  try {
    await hre.run("verify:verify", {
      address: address,
      constructorArguments: constructorArgs,
    });
    console.log("✓ LensArtPayment verified successfully");
  } catch (error) {
    if (error.message.includes("Already Verified")) {
      console.log("✓ LensArtPayment already verified");
    } else {
      console.error("✗ Error:", error.message);
      process.exit(1);
    }
  }
}

main()
  .then(() => {
    setTimeout(() => process.exit(0), 1000);
  })
  .catch((error) => {
    console.error("Error:", error.message);
    setTimeout(() => process.exit(1), 1000);
  });

