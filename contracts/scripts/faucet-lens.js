const hre = require("hardhat");
const fs = require("fs");

/**
 * Script để transfer LENS tokens cho user (faucet)
 * 
 * Usage:
 *   npx hardhat run scripts/faucet-lens.js --network sepolia -- --address 0x... --amount 1000
 * 
 * Options:
 *   --address: Địa chỉ ví của user cần nhận tokens (bắt buộc)
 *   --amount: Số lượng LENS tokens cần transfer (mặc định: 1000)
 */
async function main() {
  // Parse command line arguments
  // Hardhat sẽ pass arguments sau -- vào process.argv
  // Tìm index của script name và lấy arguments sau đó
  const scriptIndex = process.argv.findIndex(arg => arg.includes('faucet-lens.js'));
  const args = scriptIndex >= 0 ? process.argv.slice(scriptIndex + 1) : process.argv.slice(2);
  
  // Loại bỏ --network và sepolia nếu có
  const filteredArgs = args.filter(arg => arg !== '--network' && arg !== 'sepolia');
  
  let recipientAddress = null;
  let amount = "1000"; // Default: 1000 LENS

  for (let i = 0; i < filteredArgs.length; i++) {
    if (filteredArgs[i] === "--address" && filteredArgs[i + 1]) {
      recipientAddress = filteredArgs[i + 1];
      i++;
    } else if (filteredArgs[i] === "--amount" && filteredArgs[i + 1]) {
      amount = filteredArgs[i + 1];
      i++;
    }
  }

  // Nếu không tìm thấy từ args, thử từ environment variables
  if (!recipientAddress) {
    recipientAddress = process.env.FAUCET_ADDRESS;
  }
  if (amount === "1000" && process.env.FAUCET_AMOUNT) {
    amount = process.env.FAUCET_AMOUNT;
  }

  if (!recipientAddress) {
    console.error("\n✗ ERROR: Địa chỉ ví không được cung cấp!");
    console.error("\n📝 Usage:");
    console.error("   npm run faucet:lens -- --address 0x... [--amount 1000]");
    console.error("   hoặc:");
    console.error("   npx hardhat run scripts/faucet-lens.js --network sepolia -- --address 0x... [--amount 1000]");
    console.error("\n📝 Ví dụ:");
    console.error("   npm run faucet:lens -- --address 0xEe5585a285c91afe74ae9f56d754CBC6eFe8Cef0 --amount 1000");
    process.exit(1);
  }

  // Validate address
  if (!hre.ethers.isAddress(recipientAddress)) {
    console.error("\n✗ ERROR: Địa chỉ ví không hợp lệ:", recipientAddress);
    process.exit(1);
  }

  const network = hre.network.name;
  const deploymentsFile = `./deployments/${network}.json`;

  if (!fs.existsSync(deploymentsFile)) {
    console.error(`\n✗ ERROR: Không tìm thấy file deployment: ${deploymentsFile}`);
    console.error("   Vui lòng deploy contracts trước: npm run deploy:sepolia");
    process.exit(1);
  }

  const deploymentInfo = JSON.parse(fs.readFileSync(deploymentsFile, "utf8"));
  const [deployer] = await hre.ethers.getSigners();

  console.log("\n=== LENS Token Faucet ===");
  console.log("Network:", network);
  console.log("Deployer:", deployer.address);
  console.log("Recipient:", recipientAddress);
  console.log("Amount:", amount, "LENS");

  // Check deployer balance
  const balance = await hre.ethers.provider.getBalance(deployer.address);
  const balanceInEth = hre.ethers.formatEther(balance);
  console.log("\nDeployer ETH balance:", balanceInEth, "ETH");

  if (network !== "hardhat" && network !== "localhost") {
    const minBalance = hre.ethers.parseEther("0.001");
    if (balance < minBalance) {
      console.error("\n✗ ERROR: Không đủ ETH để trả gas fee!");
      console.error("   Cần ít nhất 0.001 ETH để transfer tokens");
      process.exit(1);
    }
  }

  // Get LENSToken contract instance
  const LENSToken = await hre.ethers.getContractAt("LENSToken", deploymentInfo.contracts.LENSToken);

  // Check deployer token balance
  const deployerTokenBalance = await LENSToken.balanceOf(deployer.address);
  const deployerTokenBalanceFormatted = hre.ethers.formatEther(deployerTokenBalance);
  console.log("Deployer LENS balance:", deployerTokenBalanceFormatted, "LENS");

  // Parse amount
  const transferAmount = hre.ethers.parseEther(amount);

  if (deployerTokenBalance < transferAmount) {
    console.error("\n✗ ERROR: Không đủ LENS tokens!");
    console.error("   Deployer có:", deployerTokenBalanceFormatted, "LENS");
    console.error("   Cần transfer:", amount, "LENS");
    console.error("\n💡 Giải pháp:");
    console.error("   Option 1: Mint thêm tokens cho deployer");
    console.error("   Option 2: Transfer số lượng nhỏ hơn");
    process.exit(1);
  }

  // Check recipient current balance
  const recipientBalanceBefore = await LENSToken.balanceOf(recipientAddress);
  const recipientBalanceBeforeFormatted = hre.ethers.formatEther(recipientBalanceBefore);
  console.log("Recipient balance (before):", recipientBalanceBeforeFormatted, "LENS");

  // Transfer tokens
  console.log("\n=== Transferring Tokens ===");
  try {
    console.log(`Transferring ${amount} LENS to ${recipientAddress}...`);
    const tx = await LENSToken.transfer(recipientAddress, transferAmount);
    console.log("Transaction hash:", tx.hash);
    console.log("Waiting for confirmation...");
    
    const receipt = await tx.wait();
    console.log("✓ Transaction confirmed in block:", receipt.blockNumber);

    // Check recipient balance after
    const recipientBalanceAfter = await LENSToken.balanceOf(recipientAddress);
    const recipientBalanceAfterFormatted = hre.ethers.formatEther(recipientBalanceAfter);
    console.log("Recipient balance (after):", recipientBalanceAfterFormatted, "LENS");
    console.log("✓ Transfer successful!");

    // Show transaction details
    if (network !== "hardhat" && network !== "localhost") {
      // Sử dụng Tenderly Dashboard
      const tenderlyUsername = process.env.TENDERLY_USERNAME || "trinhhhh453543";
      const tenderlyProject = process.env.TENDERLY_PROJECT || "crypto";
      const explorerUrl = `https://dashboard.tenderly.co/${tenderlyUsername}/${tenderlyProject}/tx/${network}/${tx.hash}`;
      console.log("\n📄 View transaction on Tenderly Dashboard:");
      console.log("  ", explorerUrl);
    }

  } catch (error) {
    console.error("\n✗ ERROR: Transfer failed!");
    console.error("Error:", error.message);
    
    if (error.reason) {
      console.error("Reason:", error.reason);
    }
    process.exit(1);
  }

  console.log("\n✓ Faucet completed successfully!");
}

main()
  .then(() => process.exit(0))
  .catch((error) => {
    console.error("\n✗ Faucet failed!");
    console.error("Error:", error.message);
    process.exit(1);
  });

