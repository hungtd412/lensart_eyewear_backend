/**
 * Web3 Helper Functions cho Frontend
 * Sử dụng với ethers.js hoặc web3.js
 */

/**
 * Khởi tạo contract instance với ethers.js
 * @param {Object} provider - Ethers provider (Web3Provider từ MetaMask)
 * @param {string} contractAddress - Địa chỉ contract
 * @param {Array} abi - ABI của contract
 * @returns {Object} Contract instance
 */
export function getContract(provider, contractAddress, abi) {
  const { ethers } = require('ethers');
  return new ethers.Contract(contractAddress, abi, provider);
}

/**
 * Lấy contract info từ API backend
 * @param {string} backendUrl - URL của backend API
 * @param {string} network - Network name (sepolia, hardhat, etc.)
 * @returns {Promise<Object>} Contract configuration
 */
export async function fetchContractInfo(backendUrl, network = 'sepolia') {
  try {
    const response = await fetch(`${backendUrl}/api/blockchain/contracts?network=${network}`);
    const result = await response.json();
    
    if (!result.success) {
      throw new Error(result.message || 'Failed to fetch contract info');
    }
    
    return result.data;
  } catch (error) {
    console.error('Error fetching contract info:', error);
    throw error;
  }
}

/**
 * Kết nối MetaMask và lấy provider
 * @returns {Promise<Object>} Ethers Web3Provider
 */
export async function connectWallet() {
  if (typeof window.ethereum === 'undefined') {
    throw new Error('MetaMask is not installed. Please install MetaMask extension.');
  }

  try {
    // Request account access
    await window.ethereum.request({ method: 'eth_requestAccounts' });
    
    const { ethers } = require('ethers');
    const provider = new ethers.BrowserProvider(window.ethereum);
    
    // Get signer
    const signer = await provider.getSigner();
    const address = await signer.getAddress();
    
    return { provider, signer, address };
  } catch (error) {
    console.error('Error connecting wallet:', error);
    throw error;
  }
}

/**
 * Kiểm tra network có đúng không
 * @param {Object} provider - Ethers provider
 * @param {number} expectedChainId - Chain ID mong đợi
 * @returns {Promise<boolean>} True nếu network đúng
 */
export async function checkNetwork(provider, expectedChainId) {
  try {
    const network = await provider.getNetwork();
    return Number(network.chainId) === expectedChainId;
  } catch (error) {
    console.error('Error checking network:', error);
    return false;
  }
}

/**
 * Chuyển đổi network nếu cần
 * @param {number} chainId - Chain ID cần chuyển
 * @returns {Promise<boolean>} True nếu chuyển thành công
 */
export async function switchNetwork(chainId) {
  if (typeof window.ethereum === 'undefined') {
    throw new Error('MetaMask is not installed');
  }

  try {
    await window.ethereum.request({
      method: 'wallet_switchEthereumChain',
      params: [{ chainId: `0x${chainId.toString(16)}` }],
    });
    return true;
  } catch (switchError) {
    // Network chưa tồn tại, cần add network
    if (switchError.code === 4902) {
      throw new Error('Network not found. Please add network to MetaMask.');
    }
    throw switchError;
  }
}

/**
 * Format số lượng token (wei -> ether)
 * @param {string|BigNumber} amount - Số lượng trong wei
 * @param {number} decimals - Số thập phân (default: 18)
 * @returns {string} Số lượng đã format
 */
export function formatTokenAmount(amount, decimals = 18) {
  const { ethers } = require('ethers');
  return ethers.formatUnits(amount, decimals);
}

/**
 * Parse số lượng token (ether -> wei)
 * @param {string} amount - Số lượng trong ether
 * @param {number} decimals - Số thập phân (default: 18)
 * @returns {BigNumber} Số lượng trong wei
 */
export function parseTokenAmount(amount, decimals = 18) {
  const { ethers } = require('ethers');
  return ethers.parseUnits(amount, decimals);
}

/**
 * Xử lý lỗi transaction
 * @param {Error} error - Error object
 * @returns {string} Error message dễ hiểu
 */
export function handleTransactionError(error) {
  if (error.code === 4001) {
    return 'User rejected the transaction';
  }
  if (error.code === -32603) {
    return 'Transaction failed. Please check your balance and try again.';
  }
  if (error.message.includes('insufficient funds')) {
    return 'Insufficient funds for transaction';
  }
  if (error.message.includes('user rejected')) {
    return 'Transaction was rejected';
  }
  return error.message || 'Transaction failed';
}

/**
 * Đợi transaction được confirm
 * @param {Object} tx - Transaction object
 * @param {number} confirmations - Số confirmations cần đợi (default: 1)
 * @returns {Promise<Object>} Transaction receipt
 */
export async function waitForTransaction(tx, confirmations = 1) {
  try {
    const receipt = await tx.wait(confirmations);
    return receipt;
  } catch (error) {
    console.error('Error waiting for transaction:', error);
    throw error;
  }
}

