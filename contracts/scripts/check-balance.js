const hre = require("hardhat");
const fs = require("fs");

/**
 * Script để kiểm tra số dư LENS token của một địa chỉ ví
 * 
 * Usage:
 *   npx hardhat run scripts/check-balance.js --network sepolia -- --address 0x...
 */

async function main() {
  // Parse command line arguments
  const scriptIndex = process.argv.findIndex(arg => arg.includes('check-balance.js'));
  const args = scriptIndex >= 0 ? process.argv.slice(scriptIndex + 1) : process.argv.slice(2);
  
  // Loại bỏ --network và sepolia nếu có
  const filteredArgs = args.filter(arg => arg !== '--network' && arg !== 'sepolia');
  
  let address = null;

  for (let i = 0; i < filteredArgs.length; i++) {
    if (filteredArgs[i] === "--address" && filteredArgs[i + 1]) {
      address = filteredArgs[i + 1];
      i++;
    }
  }

  // Nếu không tìm thấy từ args, thử từ environment variables
  if (!address) {
    address = process.env.CHECK_BALANCE_ADDRESS;
  }

  if (!address) {
    console.error("\n✗ ERROR: Địa chỉ ví không được cung cấp!");
    console.error("\n📝 Usage:");
    console.error("   npx hardhat run scripts/check-balance.js --network sepolia -- --address 0x...");
    process.exit(1);
  }

  // Validate address
  if (!hre.ethers.isAddress(address)) {
    console.error("\n✗ ERROR: Địa chỉ ví không hợp lệ:", address);
    process.exit(1);
  }

  const network = hre.network.name;
  const deploymentsFile = `./deployments/${network}.json`;

  if (!fs.existsSync(deploymentsFile)) {
    console.error(`\n✗ ERROR: Không tìm thấy file deployment: ${deploymentsFile}`);
    process.exit(1);
  }

  const deploymentInfo = JSON.parse(fs.readFileSync(deploymentsFile, "utf8"));

  console.log("\n=== Checking LENS Token Balance ===");
  console.log("Network:", network);
  console.log("Address:", address);
  console.log("LENSToken Contract:", deploymentInfo.contracts.LENSToken);

  try {
    // Get LENSToken contract instance
    const LENSToken = await hre.ethers.getContractAt("LENSToken", deploymentInfo.contracts.LENSToken);

    // Check token balance
    const tokenBalance = await LENSToken.balanceOf(address);
    const tokenBalanceFormatted = hre.ethers.formatEther(tokenBalance);

    // Output JSON for API usage
    console.log(JSON.stringify({
      success: true,
      address: address,
      balance: tokenBalanceFormatted,
      balance_raw: tokenBalance.toString(),
      network: network,
      lens_token_address: deploymentInfo.contracts.LENSToken
    }));

  } catch (error) {
    console.error(JSON.stringify({
      success: false,
      error: error.message
    }));
    process.exit(1);
  }
}

main()
  .then(() => process.exit(0))
  .catch((error) => {
    console.error(JSON.stringify({
      success: false,
      error: error.message
    }));
    process.exit(1);
  });

