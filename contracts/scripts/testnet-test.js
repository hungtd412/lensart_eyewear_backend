const hre = require("hardhat");
const fs = require("fs");

async function main() {
  const network = hre.network.name;
  const deploymentsFile = `./deployments/${network}.json`;

  if (!fs.existsSync(deploymentsFile)) {
    console.error(`Deployment file not found: ${deploymentsFile}`);
    console.error("Please deploy contracts first using: npx hardhat run scripts/deploy.js --network", network);
    process.exit(1);
  }

  const deploymentInfo = JSON.parse(fs.readFileSync(deploymentsFile, "utf8"));
  const [deployer, customer] = await hre.ethers.getSigners();

  console.log("Testing contracts on", network);
  console.log("Deployer:", deployer.address);
  console.log("Customer:", customer.address);
  console.log("\nContract Addresses:");
  console.log("- LENSToken:", deploymentInfo.contracts.LENSToken);
  console.log("- LensArtPayment:", deploymentInfo.contracts.LensArtPayment);
  console.log("- LensArtOrderNFT:", deploymentInfo.contracts.LensArtOrderNFT);

  // Get contract instances
  const LENSToken = await hre.ethers.getContractAt("LENSToken", deploymentInfo.contracts.LENSToken);
  const PaymentContract = await hre.ethers.getContractAt("LensArtPayment", deploymentInfo.contracts.LensArtPayment);
  const NFTContract = await hre.ethers.getContractAt("LensArtOrderNFT", deploymentInfo.contracts.LensArtOrderNFT);

  console.log("\n=== Test 1: Check Token Balance ===");
  const deployerBalance = await LENSToken.balanceOf(deployer.address);
  console.log("Deployer token balance:", hre.ethers.formatEther(deployerBalance), "LENS");

  console.log("\n=== Test 2: Transfer Tokens to Customer ===");
  const transferAmount = hre.ethers.parseEther("1000");
  const tx1 = await LENSToken.transfer(customer.address, transferAmount);
  await tx1.wait();
  console.log("Transferred", hre.ethers.formatEther(transferAmount), "LENS to customer");
  
  const customerBalance = await LENSToken.balanceOf(customer.address);
  console.log("Customer token balance:", hre.ethers.formatEther(customerBalance), "LENS");

  console.log("\n=== Test 3: Initiate Payment ===");
  const paymentAmount = hre.ethers.parseEther("100");
  const orderId = 1;
  const ipfsHash = "QmTestHash123";

  // Approve payment contract
  const tx2 = await LENSToken.connect(customer).approve(deploymentInfo.contracts.LensArtPayment, paymentAmount);
  await tx2.wait();
  console.log("Approved", hre.ethers.formatEther(paymentAmount), "LENS for payment contract");

  // Initiate payment
  const tx3 = await PaymentContract.connect(customer).initiatePayment(orderId, paymentAmount, ipfsHash);
  await tx3.wait();
  console.log("Payment initiated for order ID:", orderId);

  // Check payment info
  const payment = await PaymentContract.getPayment(orderId);
  console.log("Payment info:", {
    orderId: payment.orderId.toString(),
    customer: payment.customer,
    amount: hre.ethers.formatEther(payment.amount),
    completed: payment.completed,
    refunded: payment.refunded
  });

  console.log("\n=== Test 4: Confirm Payment ===");
  const customerBalanceBefore = await LENSToken.balanceOf(customer.address);
  const contractBalanceBefore = await LENSToken.balanceOf(deploymentInfo.contracts.LensArtPayment);
  const feeRecipientBalanceBefore = await LENSToken.balanceOf(deploymentInfo.contracts.FeeRecipient);

  const tx4 = await PaymentContract.connect(deployer).confirmPayment(orderId);
  await tx4.wait();
  console.log("Payment confirmed!");

  const customerBalanceAfter = await LENSToken.balanceOf(customer.address);
  const contractBalanceAfter = await LENSToken.balanceOf(deploymentInfo.contracts.LensArtPayment);
  const feeRecipientBalanceAfter = await LENSToken.balanceOf(deploymentInfo.contracts.FeeRecipient);

  console.log("Customer balance change:", hre.ethers.formatEther(customerBalanceBefore - customerBalanceAfter), "LENS");
  console.log("Contract balance:", hre.ethers.formatEther(contractBalanceAfter), "LENS");
  console.log("Fee recipient received:", hre.ethers.formatEther(feeRecipientBalanceAfter - feeRecipientBalanceBefore), "LENS");

  console.log("\n=== Test 5: Mint NFT for Order ===");
  const tx5 = await NFTContract.connect(deployer).mintOrderNFT(customer.address, orderId, ipfsHash);
  await tx5.wait();
  console.log("NFT minted for order ID:", orderId);

  const tokenId = await NFTContract.getTokenIdByOrder(orderId);
  console.log("Token ID:", tokenId.toString());
  
  const nftOwner = await NFTContract.ownerOf(tokenId);
  console.log("NFT Owner:", nftOwner);
  console.log("Expected owner:", customer.address);
  console.log("Owner matches:", nftOwner.toLowerCase() === customer.address.toLowerCase());

  const orderNFT = await NFTContract.getOrderNFT(tokenId);
  console.log("NFT Info:", {
    orderId: orderNFT.orderId.toString(),
    ipfsHash: orderNFT.ipfsHash,
    mintedAt: new Date(Number(orderNFT.mintedAt) * 1000).toISOString()
  });

  console.log("\n=== All Tests Passed! ===");
}

main()
  .then(() => process.exit(0))
  .catch((error) => {
    console.error(error);
    process.exit(1);
  });

