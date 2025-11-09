const hre = require("ethers");

/**
 * Script test để kiểm tra logic confirmations
 * Chạy: node scripts/test-confirmations.js
 */

async function testConfirmations() {
  console.log("Testing confirmation logic...");
  
  // Mock receipt
  const mockReceipt = {
    blockNumber: 1000,
    transactionHash: "0x123..."
  };
  
  // Mock provider
  let currentBlock = 1001;
  const mockProvider = {
    getBlockNumber: async () => {
      console.log(`Getting block number: ${currentBlock}`);
      return currentBlock;
    },
    waitForTransaction: async (hash, confirmations) => {
      console.log(`Waiting for transaction ${hash} with ${confirmations} confirmations`);
      // Simulate waiting
      await new Promise(resolve => setTimeout(resolve, 1000));
      return mockReceipt;
    }
  };
  
  console.log("Mock receipt:", mockReceipt);
  console.log("Current block:", await mockProvider.getBlockNumber());
  
  // Test confirmation calculation
  const confirmations = currentBlock - mockReceipt.blockNumber + 1;
  console.log(`Confirmations: ${confirmations}`);
  
  console.log("Test completed!");
}

// Chạy test
testConfirmations().catch(console.error);

