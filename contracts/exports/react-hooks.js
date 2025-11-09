/**
 * React Hooks cho Web3 Integration
 * Sử dụng với React và ethers.js
 */

import { useState, useEffect, useCallback } from 'react';
import { connectWallet, checkNetwork, switchNetwork, fetchContractInfo } from './web3-helpers';

/**
 * Hook để kết nối wallet và quản lý state
 */
export function useWallet() {
  const [account, setAccount] = useState(null);
  const [provider, setProvider] = useState(null);
  const [signer, setSigner] = useState(null);
  const [isConnecting, setIsConnecting] = useState(false);
  const [error, setError] = useState(null);

  const connect = useCallback(async () => {
    setIsConnecting(true);
    setError(null);
    try {
      const { provider: prov, signer: sig, address } = await connectWallet();
      setProvider(prov);
      setSigner(sig);
      setAccount(address);
    } catch (err) {
      setError(err.message);
      console.error('Error connecting wallet:', err);
    } finally {
      setIsConnecting(false);
    }
  }, []);

  const disconnect = useCallback(() => {
    setAccount(null);
    setProvider(null);
    setSigner(null);
    setError(null);
  }, []);

  // Listen for account changes
  useEffect(() => {
    if (typeof window.ethereum === 'undefined') return;

    const handleAccountsChanged = (accounts) => {
      if (accounts.length === 0) {
        disconnect();
      } else {
        setAccount(accounts[0]);
      }
    };

    window.ethereum.on('accountsChanged', handleAccountsChanged);
    
    return () => {
      window.ethereum?.removeListener('accountsChanged', handleAccountsChanged);
    };
  }, [disconnect]);

  return {
    account,
    provider,
    signer,
    isConnecting,
    error,
    connect,
    disconnect,
    isConnected: !!account
  };
}

/**
 * Hook để load contract info từ backend
 */
export function useContractInfo(backendUrl, network = 'sepolia') {
  const [contractInfo, setContractInfo] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    async function loadContractInfo() {
      try {
        setLoading(true);
        const info = await fetchContractInfo(backendUrl, network);
        setContractInfo(info);
        setError(null);
      } catch (err) {
        setError(err.message);
        console.error('Error loading contract info:', err);
      } finally {
        setLoading(false);
      }
    }

    if (backendUrl) {
      loadContractInfo();
    }
  }, [backendUrl, network]);

  return { contractInfo, loading, error };
}

/**
 * Hook để kiểm tra và chuyển network
 */
export function useNetwork(expectedChainId) {
  const [isCorrectNetwork, setIsCorrectNetwork] = useState(false);
  const [isChecking, setIsChecking] = useState(false);
  const [error, setError] = useState(null);

  const check = useCallback(async (provider) => {
    if (!provider) return;
    
    setIsChecking(true);
    setError(null);
    try {
      const isCorrect = await checkNetwork(provider, expectedChainId);
      setIsCorrectNetwork(isCorrect);
      if (!isCorrect) {
        setError(`Please switch to the correct network (Chain ID: ${expectedChainId})`);
      }
    } catch (err) {
      setError(err.message);
      setIsCorrectNetwork(false);
    } finally {
      setIsChecking(false);
    }
  }, [expectedChainId]);

  const switchToNetwork = useCallback(async () => {
    try {
      await switchNetwork(expectedChainId);
      setError(null);
    } catch (err) {
      setError(err.message);
      throw err;
    }
  }, [expectedChainId]);

  return {
    isCorrectNetwork,
    isChecking,
    error,
    check,
    switchToNetwork
  };
}

/**
 * Hook để tương tác với contract
 */
export function useContract(contractAddress, abi, signer) {
  const [contract, setContract] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!contractAddress || !abi || !signer) {
      setContract(null);
      return;
    }

    try {
      const { ethers } = require('ethers');
      const contractInstance = new ethers.Contract(contractAddress, abi, signer);
      setContract(contractInstance);
      setError(null);
    } catch (err) {
      setError(err.message);
      console.error('Error creating contract instance:', err);
      setContract(null);
    }
  }, [contractAddress, abi, signer]);

  return { contract, loading, error };
}

/**
 * Hook để đọc dữ liệu từ contract (view functions)
 */
export function useContractRead(contract, functionName, args = [], dependencies = []) {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!contract || !functionName) return;

    async function readData() {
      setLoading(true);
      setError(null);
      try {
        const result = await contract[functionName](...args);
        setData(result);
      } catch (err) {
        setError(err.message);
        console.error(`Error reading ${functionName}:`, err);
      } finally {
        setLoading(false);
      }
    }

    readData();
  }, [contract, functionName, JSON.stringify(args), ...dependencies]);

  return { data, loading, error, refetch: () => {} };
}

/**
 * Hook để gửi transaction (write functions)
 */
export function useContractWrite(contract, functionName) {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [txHash, setTxHash] = useState(null);
  const [receipt, setReceipt] = useState(null);

  const write = useCallback(async (...args) => {
    if (!contract || !functionName) {
      throw new Error('Contract or function name not provided');
    }

    setLoading(true);
    setError(null);
    setTxHash(null);
    setReceipt(null);

    try {
      const tx = await contract[functionName](...args);
      setTxHash(tx.hash);
      
      // Wait for transaction confirmation
      const receipt = await tx.wait();
      setReceipt(receipt);
      
      return receipt;
    } catch (err) {
      const errorMessage = handleTransactionError(err);
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  }, [contract, functionName]);

  return {
    write,
    loading,
    error,
    txHash,
    receipt
  };
}

// Import handleTransactionError từ web3-helpers
function handleTransactionError(error) {
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

