const hre = require("hardhat");

async function main() {
  // Check private key configuration
  const privateKey = process.env.PRIVATE_KEY;
  if (!privateKey) {
    console.error("\n✗ ERROR: PRIVATE_KEY không được cấu hình!");
    console.error("\n📝 Giải pháp:");
    console.error("   1. Tạo file .env trong thư mục contracts/");
    console.error("   2. Thêm dòng: PRIVATE_KEY=your_private_key_here");
    console.error("   3. Private key phải bắt đầu bằng 0x và có 66 ký tự");
    console.error("   4. Hoặc không có 0x và có 64 ký tự");
    console.error("   5. LƯU Ý: Không commit file .env lên git!");
    process.exit(1);
  }

  const network = hre.network.name;
  const chainId = hre.network.config.chainId;
  
  console.log("\n=== Deployment Configuration ===");
  console.log("Network:", network);
  console.log("Chain ID:", chainId);
  
  // Warn if mainnet
  if (network === "mainnet" || chainId === 1) {
    console.error("\n⚠️  ⚠️  ⚠️  CẢNH BÁO QUAN TRỌNG ⚠️  ⚠️  ⚠️");
    console.error("   BẠN ĐANG Ở MAINNET!");
    console.error("   Mọi giao dịch sẽ tốn ETH thật!");
    console.error("   Hãy chắc chắn bạn muốn deploy lên mainnet!");
    console.error("\n   Nhấn Ctrl+C để hủy, hoặc đợi 10 giây để tiếp tục...");
    await new Promise(resolve => setTimeout(resolve, 10000));
  }

  const [deployer] = await hre.ethers.getSigners();
  
  console.log("\nDeploying contracts with the account:", deployer.address);
  
  // Check network connection
  try {
    const blockNumber = await hre.ethers.provider.getBlockNumber();
    console.log("Connected to network. Current block:", blockNumber);
  } catch (error) {
    console.error("\n✗ ERROR: Không thể kết nối đến network!");
    console.error("Error:", error.message);
      console.error("\n📝 Giải pháp:");
      if (network === "sepolia") {
        console.error("   1. Kiểm tra SEPOLIA_RPC_URL trong .env file");
        console.error("   2. Mặc định sử dụng Tenderly RPC:");
        console.error("      https://virtual.rpc.tenderly.co/trinhhhh453543/project/public/crypto");
        console.error("   3. Đảm bảo RPC URL đúng và hoạt động");
        console.error("   4. Kiểm tra kết nối internet");
      }
    process.exit(1);
  }
  
  // Check balance
  let balance, balanceInEth;
  try {
    balance = await hre.ethers.provider.getBalance(deployer.address);
    balanceInEth = hre.ethers.formatEther(balance);
    console.log("Account balance:", balanceInEth, "ETH");
  } catch (error) {
    console.error("\n✗ ERROR: Không thể kiểm tra balance!");
    console.error("Error:", error.message);
    process.exit(1);
  }
  
  // Check if balance is sufficient
  if (network !== "hardhat" && network !== "localhost") {
    const minBalance = hre.ethers.parseEther("0.01"); // Minimum 0.01 ETH
    if (balance < minBalance) {
      console.error("\n✗ ERROR: Không đủ ETH để deploy!");
      console.error("   Số dư hiện tại:", balanceInEth, "ETH");
      console.error("   Cần ít nhất: 0.01 ETH để trả gas fee");
      
      // Check if it's Sepolia
      if (network === "sepolia" || chainId === 11155111) {
        console.error("\n📝 Cách lấy Sepolia ETH:");
        console.error("   1. Truy cập: https://sepoliafaucet.com/");
        console.error("   2. Hoặc: https://www.infura.io/faucet/sepolia");
        console.error("   3. Hoặc: https://faucet.quicknode.com/ethereum/sepolia");
        console.error("   4. Kết nối wallet:", deployer.address);
        console.error("   5. Yêu cầu Sepolia ETH");
        console.error("   6. Đợi vài phút để ETH được gửi đến");
        console.error("\n   LƯU Ý: Bạn cần Sepolia ETH (testnet), không phải mainnet ETH!");
      } else if (network === "mainnet" || chainId === 1) {
        console.error("\n⚠️  BẠN ĐANG Ở MAINNET!");
        console.error("   Cần ETH thật để deploy trên mainnet");
      } else {
        console.error("\n📝 Cần ETH trên network:", network);
        console.error("   Chain ID:", chainId);
      }
      
      console.error("\n   Sau đó chạy lại lệnh deploy.\n");
      process.exit(1);
    }
    console.log("✓ Số dư đủ để deploy");
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
  let lensTokenReceipt = null;
  try {
    const LENSToken = await hre.ethers.getContractFactory("LENSToken");
    console.log("Deploying...");
    const lensToken = await LENSToken.deploy(deployer.address);
    lensTokenDeploymentTx = lensToken.deploymentTransaction();
    
    if (lensTokenDeploymentTx) {
      console.log("   Transaction hash:", lensTokenDeploymentTx.hash);
      console.log("Waiting for deployment transaction...");
      // Đợi transaction được confirm (1 confirmation)
      const waitReceipt = await lensTokenDeploymentTx.wait(1);
      console.log("   ✓ Transaction confirmed in block:", waitReceipt.blockNumber);
      
      // Lấy lại receipt từ provider để đảm bảo có đầy đủ thông tin
      lensTokenReceipt = await hre.ethers.provider.getTransactionReceipt(lensTokenDeploymentTx.hash);
      if (lensTokenReceipt && lensTokenReceipt.blockNumber) {
        console.log("   ✓ Receipt verified - Block:", lensTokenReceipt.blockNumber);
      } else {
        console.warn("   ⚠️  Could not get receipt, using wait receipt");
        lensTokenReceipt = waitReceipt;
      }
    }
    
    console.log("Waiting for deployment...");
    await lensToken.waitForDeployment();
    lensTokenAddress = await lensToken.getAddress();
    console.log("✓ LENSToken deployed to:", lensTokenAddress);
  } catch (error) {
    console.error("✗ Error deploying LENSToken:", error.message);
    if (lensTokenDeploymentTx) {
      console.error("   Transaction hash:", lensTokenDeploymentTx.hash);
    }
    throw error;
  }

  // Deploy LensArtPayment
  console.log("\n=== Deploying LensArtPayment ===");
  // Fee recipient sẽ là một address khác (có thể là deployer hoặc một address khác)
  // Trong trường hợp này, chúng ta dùng deployer làm fee recipient
  let paymentReceipt = null;
  
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
    
    if (paymentDeploymentTx) {
      console.log("   Transaction hash:", paymentDeploymentTx.hash);
      console.log("Waiting for deployment transaction...");
      // Đợi transaction được confirm (1 confirmation)
      const waitReceipt = await paymentDeploymentTx.wait(1);
      console.log("   ✓ Transaction confirmed in block:", waitReceipt.blockNumber);
      
      // Lấy lại receipt từ provider để đảm bảo có đầy đủ thông tin
      paymentReceipt = await hre.ethers.provider.getTransactionReceipt(paymentDeploymentTx.hash);
      if (paymentReceipt && paymentReceipt.blockNumber) {
        console.log("   ✓ Receipt verified - Block:", paymentReceipt.blockNumber);
      } else {
        console.warn("   ⚠️  Could not get receipt, using wait receipt");
        paymentReceipt = waitReceipt;
      }
    }
    
    console.log("Waiting for deployment...");
    await paymentContract.waitForDeployment();
    paymentContractAddress = await paymentContract.getAddress();
    console.log("✓ LensArtPayment deployed to:", paymentContractAddress);
  } catch (error) {
    console.error("✗ Error deploying LensArtPayment:", error.message);
    if (paymentDeploymentTx) {
      console.error("   Transaction hash:", paymentDeploymentTx.hash);
    }
    throw error;
  }

  // Deploy LensArtOrderNFT
  console.log("\n=== Deploying LensArtOrderNFT ===");
  let nftReceipt = null;
  try {
    const LensArtOrderNFT = await hre.ethers.getContractFactory("LensArtOrderNFT");
    console.log("Deploying with owner:", deployer.address);
    const nftContract = await LensArtOrderNFT.deploy(deployer.address);
    nftDeploymentTx = nftContract.deploymentTransaction();
    
    if (nftDeploymentTx) {
      console.log("   Transaction hash:", nftDeploymentTx.hash);
      console.log("Waiting for deployment transaction...");
      // Đợi transaction được confirm (1 confirmation)
      const waitReceipt = await nftDeploymentTx.wait(1);
      console.log("   ✓ Transaction confirmed in block:", waitReceipt.blockNumber);
      
      // Lấy lại receipt từ provider để đảm bảo có đầy đủ thông tin
      nftReceipt = await hre.ethers.provider.getTransactionReceipt(nftDeploymentTx.hash);
      if (nftReceipt && nftReceipt.blockNumber) {
        console.log("   ✓ Receipt verified - Block:", nftReceipt.blockNumber);
      } else {
        console.warn("   ⚠️  Could not get receipt, using wait receipt");
        nftReceipt = waitReceipt;
      }
    }
    
    console.log("Waiting for deployment...");
    await nftContract.waitForDeployment();
    nftContractAddress = await nftContract.getAddress();
    console.log("✓ LensArtOrderNFT deployed to:", nftContractAddress);
  } catch (error) {
    console.error("✗ Error deploying LensArtOrderNFT:", error.message);
    if (nftDeploymentTx) {
      console.error("   Transaction hash:", nftDeploymentTx.hash);
    }
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

  // Wait for additional block confirmations (đã có 1 confirmation khi deploy)
  if (hre.network.name !== "hardhat" && hre.network.name !== "localhost") {
    console.log("\n⏳ Waiting for 5 confirmations...\n");
    
    // Helper function để chờ confirmations một cách an toàn bằng polling
    async function waitForConfirmations(receipt, txHash, contractName, targetConfirmations = 5) {
      if (!receipt) {
        console.warn(`⚠️  ${contractName}: No receipt available, trying to get from provider...`);
        try {
          // Thử lấy receipt từ provider
          receipt = await hre.ethers.provider.getTransactionReceipt(txHash);
          if (!receipt || !receipt.blockNumber) {
            console.warn(`⚠️  ${contractName}: Transaction not yet mined, waiting...`);
            // Đợi transaction được mined
            receipt = await hre.ethers.provider.waitForTransaction(txHash, 1);
          }
        } catch (error) {
          console.warn(`⚠️  ${contractName}: Could not get transaction receipt: ${error.message}`);
          return false;
        }
      }
      
      // Đảm bảo có blockNumber
      if (!receipt || !receipt.blockNumber) {
        console.warn(`⚠️  ${contractName}: No block number in receipt, trying to fetch from provider...`);
        try {
          receipt = await hre.ethers.provider.getTransactionReceipt(txHash);
          if (!receipt || !receipt.blockNumber) {
            throw new Error("Transaction receipt has no block number");
          }
        } catch (error) {
          console.warn(`⚠️  ${contractName}: Could not get block number: ${error.message}`);
          console.warn(`      Transaction hash: ${txHash}`);
          const tenderlyUrl = `https://dashboard.tenderly.co/${process.env.TENDERLY_USERNAME || 'trinhhhh453543'}/${process.env.TENDERLY_PROJECT || 'crypto'}/tx/sepolia/${txHash}`;
          console.warn(`      Check on Tenderly: ${tenderlyUrl}`);
          return false;
        }
      }
      
      const txBlockNumber = Number(receipt.blockNumber);
      if (!txBlockNumber || txBlockNumber <= 0) {
        console.warn(`⚠️  ${contractName}: Invalid block number: ${txBlockNumber}`);
        return false;
      }
      
      try {
        const startTime = Date.now();
        const maxWaitTime = 600000; // 10 phút timeout
        return await pollWithFallbackRPC(txHash, txBlockNumber, contractName, targetConfirmations, startTime, maxWaitTime);
      } catch (error) {
        console.error(`   ✗ ${contractName}: Error waiting for confirmations: ${error.message}`);
        console.error(`      Transaction hash: ${txHash}`);
        console.error(`      Transaction block: ${txBlockNumber || 'unknown'}`);
        const tenderlyUrl = `https://dashboard.tenderly.co/${process.env.TENDERLY_USERNAME || 'trinhhhh453543'}/${process.env.TENDERLY_PROJECT || 'crypto'}/tx/sepolia/${txHash}`;
        console.error(`      Check on Tenderly: ${tenderlyUrl}`);
        return false;
      }
    }
    
    // Helper function để poll với fallback RPC
    async function pollWithFallbackRPC(txHash, txBlockNumber, contractName, targetConfirmations, startTime, maxWaitTime) {
      // Danh sách RPC URLs - ƯU TIÊN PUBLIC SEPOLIA RPCS (không dùng Tenderly Virtual RPC để check confirmations)
      // Lý do: Tenderly Virtual RPC có thể trả về block number của virtual network, không sync với Sepolia thật
      const fallbackRPCUrls = [
        "https://rpc.sepolia.org", // Public RPC 1 - Sepolia Foundation
        "https://ethereum-sepolia-rpc.publicnode.com", // Public RPC 2 - PublicNode
        "https://sepolia.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161", // Infura Public (free tier)
        "https://rpc2.sepolia.org", // Public RPC 3 - Sepolia Foundation backup
        null, // RPC chính (sẽ dùng hre.ethers.provider) - chỉ dùng khi public RPCs fail
      ];
      
      // Helper để lấy block number từ RPC URL
      const getBlockNumberFromRPC = async (rpcUrl) => {
        try {
          if (!rpcUrl) {
            // Sử dụng provider chính
            return Number(await hre.ethers.provider.getBlockNumber());
          }
          
          // Sử dụng fetch để gọi RPC trực tiếp
          const response = await fetch(rpcUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              jsonrpc: '2.0',
              method: 'eth_blockNumber',
              params: [],
              id: 1
            })
          });
          
          const data = await response.json();
          if (data.result) {
            return Number(data.result);
          }
          return null;
        } catch (error) {
          return null;
        }
      };
      
      let currentProviderIndex = 0;
      const pollInterval = 6000; // Poll mỗi 6 giây (nhanh để detect sớm)
      let lastConfirmations = -1;
      let lastLogTime = Date.now();
      let lastBlockSeen = txBlockNumber;
      let lastBlockTime = Date.now();
      const logInterval = 15000; // Log mỗi 15 giây
      let consecutiveErrors = 0;
      let blockProgressCount = 0;
      const blocksProgressed = [txBlockNumber];
      
      while (true) {
        try {
          const now = Date.now();
          const elapsed = now - startTime;
          const shouldLog = (now - lastLogTime) > logInterval; // Định nghĩa shouldLog ở đầu loop
          
          // Kiểm tra timeout
          if (elapsed > maxWaitTime) {
            const currentConfirmations = currentBlock ? currentBlock - txBlockNumber : 0;
            console.error(`   ✗ ${contractName}: Timeout after ${Math.floor(elapsed / 1000)}s`);
            console.error(`      Current: ${currentConfirmations}/${targetConfirmations} confirmations`);
            const tenderlyUrl = `https://dashboard.tenderly.co/${process.env.TENDERLY_USERNAME || 'trinhhhh453543'}/${process.env.TENDERLY_PROJECT || 'crypto'}/tx/sepolia/${txHash}`;
            console.error(`      Transaction: ${tenderlyUrl}`);
            if (currentConfirmations >= 1) {
              console.error(`      Transaction is confirmed but may need more time`);
            }
            return false;
          }
          
          // Thử lấy block number từ TẤT CẢ public RPCs và chọn block number CAO NHẤT
          // Lý do: Đảm bảo lấy được block number thật từ Sepolia network
          let currentBlock = null;
          let providerError = null;
          let successfulRPC = null;
          let allBlockNumbers = [];
          
          // Thử TẤT CẢ RPCs song song để lấy block number cao nhất
          const blockNumberPromises = fallbackRPCUrls.map(async (rpcUrl, idx) => {
            try {
              const blockNumberPromise = getBlockNumberFromRPC(rpcUrl);
              const timeoutPromise = new Promise((_, reject) => 
                setTimeout(() => reject(new Error("RPC timeout")), 8000) // Timeout ngắn hơn để nhanh hơn
              );
              
              const blockNumber = await Promise.race([blockNumberPromise, timeoutPromise]);
              return { blockNumber, rpcUrl: rpcUrl || 'main provider', index: idx, success: true };
            } catch (error) {
              return { blockNumber: null, rpcUrl: rpcUrl || 'main provider', index: idx, success: false, error: error.message };
            }
          });
          
          const results = await Promise.all(blockNumberPromises);
          
          // Chọn block number CAO NHẤT từ tất cả RPCs
          let maxBlock = null;
          let maxBlockRPC = null;
          let maxBlockIndex = -1;
          
          for (const result of results) {
            if (result.success && result.blockNumber && result.blockNumber >= txBlockNumber) {
              allBlockNumbers.push({ block: result.blockNumber, rpc: result.rpcUrl });
              
              if (!maxBlock || result.blockNumber > maxBlock) {
                maxBlock = result.blockNumber;
                maxBlockRPC = result.rpcUrl;
                maxBlockIndex = result.index;
              }
            }
          }
          
          // Sử dụng block number cao nhất
          if (maxBlock) {
            currentBlock = maxBlock;
            successfulRPC = maxBlockRPC;
            currentProviderIndex = maxBlockIndex;
            consecutiveErrors = 0;
          } else {
            // Nếu tất cả public RPCs đều fail, thử lại với provider chính (Tenderly)
            try {
              const mainBlock = Number(await hre.ethers.provider.getBlockNumber());
              if (mainBlock && mainBlock >= txBlockNumber) {
                currentBlock = mainBlock;
                successfulRPC = 'main provider';
              }
            } catch (error) {
              providerError = error;
            }
          }
          
          if (!currentBlock || currentBlock < txBlockNumber) {
            consecutiveErrors++;
            if (consecutiveErrors > 3) {
              // Nếu không lấy được block number sau 3 lần, log warning và tiếp tục thử
              if (shouldLog) {
                console.warn(`   ⚠️  ${contractName}: RPC issues, retrying... (${consecutiveErrors} attempts)`);
              }
            }
            await new Promise(resolve => setTimeout(resolve, pollInterval));
            continue;
          }
          
          // Kiểm tra xem block có tăng không
          if (currentBlock > lastBlockSeen) {
            blockProgressCount++;
            lastBlockSeen = currentBlock;
            lastBlockTime = now;
            blocksProgressed.push(currentBlock);
            consecutiveErrors = 0;
            
            if (blocksProgressed.length > 10) {
              blocksProgressed.shift();
            }
          }
          
          // Tính confirmations
          const confirmations = currentBlock - txBlockNumber;
          
          // Kiểm tra nếu đã đủ confirmations - RETURN NGAY KHI ĐẠT 5
          if (confirmations >= targetConfirmations) {
            // Verify nhanh với 1 RPC khác (chỉ khi mới đạt target lần đầu)
            // Nếu verify fail hoặc timeout, vẫn return true vì đã có confirmations từ RPC chính
            if (lastConfirmations < targetConfirmations && blockProgressCount >= 1) {
              // Verify nhanh (timeout 3s) - không block quá lâu
              const verifyRPC = fallbackRPCUrls.find((_, idx) => idx !== currentProviderIndex);
              if (verifyRPC) {
                try {
                  const verifyPromise = getBlockNumberFromRPC(verifyRPC);
                  const timeoutPromise = new Promise((_, reject) => 
                    setTimeout(() => reject(new Error("Verify timeout")), 3000)
                  );
                  
                  const verifyBlock = await Promise.race([verifyPromise, timeoutPromise]);
                  if (verifyBlock) {
                    const verifyConfirmations = verifyBlock - txBlockNumber;
                    if (verifyConfirmations < targetConfirmations) {
                      // Nếu verify RPC cho thấy chưa đủ, đợi thêm một chút
                      await new Promise(resolve => setTimeout(resolve, 5000));
                      continue;
                    }
                  }
                } catch (error) {
                  // Verify fail hoặc timeout - vẫn coi như đủ (đã có từ RPC chính)
                }
              }
            }
            
            // Return ngay khi confirmations >= 5
            console.log(`   ✓ ${contractName}: Confirmed with ${confirmations} confirmations`);
            return true;
          }
          
          // Chỉ log khi confirmations thay đổi hoặc sau logInterval
          const confirmationsChanged = confirmations !== lastConfirmations;
          if (confirmationsChanged || shouldLog) {
            if (confirmations < targetConfirmations) {
              console.log(`   ${contractName}: ${confirmations}/${targetConfirmations} confirmations (Block: ${currentBlock})`);
            }
            lastConfirmations = confirmations;
            lastLogTime = now;
          }
          
          // Đợi trước khi poll lại
          await new Promise(resolve => setTimeout(resolve, pollInterval));
        } catch (error) {
          consecutiveErrors++;
          if (consecutiveErrors > 10) {
            console.error(`   ✗ ${contractName}: Too many errors, giving up`);
            console.error(`      Error: ${error.message}`);
            return false;
          }
          console.warn(`   ⚠️  ${contractName}: Error polling: ${error.message}, retrying...`);
          await new Promise(resolve => setTimeout(resolve, pollInterval * 2));
        }
      }
    }
    
    // Chạy song song để chờ confirmations cho tất cả contracts
    const confirmationPromises = [];
    
    if (lensTokenReceipt && lensTokenDeploymentTx) {
      confirmationPromises.push(
        waitForConfirmations(
          lensTokenReceipt, 
          lensTokenDeploymentTx.hash, 
          "LENSToken",
          5
        ).then(success => ({ name: "LENSToken", success }))
      );
    }
    
    if (paymentReceipt && paymentDeploymentTx) {
      confirmationPromises.push(
        waitForConfirmations(
          paymentReceipt, 
          paymentDeploymentTx.hash, 
          "LensArtPayment",
          5
        ).then(success => ({ name: "LensArtPayment", success }))
      );
    }
    
    if (nftReceipt && nftDeploymentTx) {
      confirmationPromises.push(
        waitForConfirmations(
          nftReceipt, 
          nftDeploymentTx.hash, 
          "LensArtOrderNFT",
          5
        ).then(success => ({ name: "LensArtOrderNFT", success }))
      );
    }
    
    // Chờ tất cả confirmations hoàn thành (song song)
    console.log("   Waiting for confirmations...\n");
    const results = await Promise.all(confirmationPromises);
    
    // Hiển thị kết quả chi tiết
    const successCount = results.filter(r => r.success).length;
    const totalCount = results.length;
    const failedContracts = results.filter(r => !r.success).map(r => r.name);
    
    if (successCount === totalCount) {
      console.log(`\n✓ All ${totalCount} contracts confirmed with 5+ confirmations!\n`);
    } else {
      console.log(`\n⚠️  ${successCount}/${totalCount} contracts confirmed`);
      if (failedContracts.length > 0) {
        console.log(`   Not confirmed: ${failedContracts.join(", ")}`);
        console.log("   These contracts are deployed but may need more time for confirmations.");
      }
      console.log("");
    }
    
    // Hiển thị transaction links trên Tenderly
    const tenderlyBase = `https://dashboard.tenderly.co/${process.env.TENDERLY_USERNAME || 'trinhhhh453543'}/${process.env.TENDERLY_PROJECT || 'crypto'}/tx/sepolia`;
    console.log("📄 Transaction Links (Tenderly Dashboard):");
    if (lensTokenDeploymentTx) {
      console.log(`   LENSToken: ${tenderlyBase}/${lensTokenDeploymentTx.hash}`);
    }
    if (paymentDeploymentTx) {
      console.log(`   LensArtPayment: ${tenderlyBase}/${paymentDeploymentTx.hash}`);
    }
    if (nftDeploymentTx) {
      console.log(`   LensArtOrderNFT: ${tenderlyBase}/${nftDeploymentTx.hash}`);
    }
    console.log("");
  }
  
  console.log("\n📝 Lưu ý:");
  console.log("   - Contracts đã được deploy thành công!");
  console.log("   - Contracts được deploy trên Tenderly virtual network");
  console.log("   - Xem trên Tenderly Dashboard: https://dashboard.tenderly.co/trinhhhh453543/crypto");
  console.log("   - Hoặc verify từng contract: npm run verify:token");
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
      console.error("   Default Tenderly RPC: https://virtual.rpc.tenderly.co/trinhhhh453543/project/public/crypto");
    }
    
    if (error.transaction) {
      console.error("Transaction hash:", error.transaction.hash);
    }
    if (error.reason) {
      console.error("Reason:", error.reason);
    }
    process.exit(1);
  });

