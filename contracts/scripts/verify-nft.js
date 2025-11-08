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
  const address = deploymentInfo.contracts.LensArtOrderNFT;
  const constructorArgs = [deploymentInfo.deployer];

  console.log("Verifying LensArtOrderNFT:");
  console.log("Address:", address);
  console.log("Constructor args:", constructorArgs);

  try {
    await hre.run("verify:verify", {
      address: address,
      constructorArguments: constructorArgs,
    });
    console.log("✓ LensArtOrderNFT verified successfully");
  } catch (error) {
    if (error.message.includes("Already Verified")) {
      console.log("✓ LensArtOrderNFT already verified");
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

