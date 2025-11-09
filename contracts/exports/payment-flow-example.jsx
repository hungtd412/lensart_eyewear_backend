/**
 * Example: Payment Flow với LENS Token
 * File này minh họa cách thực hiện thanh toán bằng LENS token
 */

import React, { useState, useEffect } from 'react';
import { ethers } from 'ethers';

const BACKEND_URL = 'http://127.0.0.1:8000';
const NETWORK = 'sepolia';
const CHAIN_ID = 11155111; // Sepolia

function CryptoPaymentFlow({ orderId, amount }) {
  const [account, setAccount] = useState(null);
  const [contractInfo, setContractInfo] = useState(null);
  const [balance, setBalance] = useState(null);
  const [allowance, setAllowance] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [step, setStep] = useState(1); // 1: Approve, 2: Pay, 3: Verify

  // 1. Load contract info từ backend
  useEffect(() => {
    async function loadContractInfo() {
      try {
        const response = await fetch(
          `${BACKEND_URL}/api/blockchain/contracts?network=${NETWORK}`
        );
        const result = await response.json();
        
        if (result.success) {
          setContractInfo(result.data);
        } else {
          throw new Error(result.message || 'Failed to load contract info');
        }
      } catch (error) {
        console.error('Error loading contract info:', error);
        setError(error.message);
      }
    }
    
    loadContractInfo();
  }, []);

  // 2. Connect wallet
  const connectWallet = async () => {
    try {
      if (typeof window.ethereum === 'undefined') {
        throw new Error('MetaMask is not installed');
      }

      const provider = new ethers.BrowserProvider(window.ethereum);
      const signer = await provider.getSigner();
      const address = await signer.getAddress();
      
      setAccount(address);
      return address;
    } catch (error) {
      console.error('Error connecting wallet:', error);
      setError(error.message);
      throw error;
    }
  };

  // 3. Check network
  const checkNetwork = async () => {
    try {
      if (typeof window.ethereum === 'undefined') {
        throw new Error('MetaMask is not installed');
      }

      const provider = new ethers.BrowserProvider(window.ethereum);
      const network = await provider.getNetwork();
      
      if (Number(network.chainId) !== CHAIN_ID) {
        // Switch to Sepolia
        await window.ethereum.request({
          method: 'wallet_switchEthereumChain',
          params: [{ chainId: `0x${CHAIN_ID.toString(16)}` }],
        });
      }
    } catch (switchError) {
      if (switchError.code === 4902) {
        // Add Sepolia network
        await window.ethereum.request({
          method: 'wallet_addEthereumChain',
          params: [{
            chainId: `0x${CHAIN_ID.toString(16)}`,
            chainName: 'Sepolia Test Network',
            nativeCurrency: {
              name: 'SepoliaETH',
              symbol: 'SEP',
              decimals: 18
            },
            rpcUrls: ['https://sepolia.infura.io/v3/YOUR_KEY'],
            blockExplorerUrls: ['https://dashboard.tenderly.co/trinhhhh453543/crypto']
          }]
        });
      } else {
        throw switchError;
      }
    }
  };

  // 4. Load balance
  const loadBalance = async () => {
    if (!account || !contractInfo) return;
    
    try {
      setLoading(true);
      
      // Thử lấy từ contract trước
      try {
        const provider = new ethers.BrowserProvider(window.ethereum);
        const tokenContract = new ethers.Contract(
          contractInfo.contracts.LENSToken,
          contractInfo.abis.LENSToken,
          provider
        );
        
        const balance = await tokenContract.balanceOf(account);
        setBalance(ethers.formatEther(balance));
      } catch (contractError) {
        console.warn('Cannot get balance from contract, using API...', contractError);
        
        // Fallback: Lấy từ API
        const response = await fetch(
          `${BACKEND_URL}/api/blockchain/balance/lens?address=${account}&network=${NETWORK}`
        );
        const result = await response.json();
        
        if (result.success) {
          setBalance(result.data.balance);
        }
      }
    } catch (error) {
      console.error('Error loading balance:', error);
      setError(error.message);
    } finally {
      setLoading(false);
    }
  };

  // 5. Load allowance
  const loadAllowance = async () => {
    if (!account || !contractInfo) return;
    
    try {
      setLoading(true);
      
      const provider = new ethers.BrowserProvider(window.ethereum);
      const signer = await provider.getSigner();
      
      const tokenContract = new ethers.Contract(
        contractInfo.contracts.LENSToken,
        contractInfo.abis.LENSToken,
        signer
      );
      
      const paymentContractAddress = contractInfo.contracts.LensArtPayment;
      const allowance = await tokenContract.allowance(account, paymentContractAddress);
      
      setAllowance(ethers.formatEther(allowance));
    } catch (error) {
      console.error('Error loading allowance:', error);
      setError(error.message);
    } finally {
      setLoading(false);
    }
  };

  // 6. Approve token - QUAN TRỌNG: Gọi trên LENS Token contract
  const handleApprove = async () => {
    try {
      if (!account || !contractInfo) {
        throw new Error('Account or contract info not loaded');
      }

      await checkNetwork();
      
      const provider = new ethers.BrowserProvider(window.ethereum);
      const signer = await provider.getSigner();
      
      // QUAN TRỌNG: Tạo contract instance cho LENS Token
      const tokenContract = new ethers.Contract(
        contractInfo.contracts.LENSToken, // LENS Token address
        contractInfo.abis.LENSToken,      // LENS Token ABI
        signer                             // Signer để gửi transaction
      );
      
      const paymentContractAddress = contractInfo.contracts.LensArtPayment;
      const amountWei = ethers.parseEther(amount.toString());
      
      // QUAN TRỌNG: Gọi approve trên LENS Token contract
      // KHÔNG gửi ETH transaction!
      console.log('Calling approve on LENS Token contract...');
      console.log('Token Contract:', contractInfo.contracts.LENSToken);
      console.log('Spender (Payment Contract):', paymentContractAddress);
      console.log('Amount:', amount, 'LENS');
      
      const tx = await tokenContract.approve(paymentContractAddress, amountWei);
      
      console.log('Approve transaction sent:', tx.hash);
      setLoading(true);
      
      // Đợi transaction được confirm
      const receipt = await tx.wait();
      console.log('Approve transaction confirmed:', receipt);
      
      // Reload allowance
      await loadAllowance();
      
      // Chuyển sang bước 2
      setStep(2);
      
    } catch (error) {
      console.error('Error approving token:', error);
      setError(error.message);
      alert('Lỗi khi approve token: ' + error.message);
    } finally {
      setLoading(false);
    }
  };

  // 7. Initiate payment
  const handlePay = async () => {
    try {
      if (!account || !contractInfo) {
        throw new Error('Account or contract info not loaded');
      }

      await checkNetwork();
      
      const provider = new ethers.BrowserProvider(window.ethereum);
      const signer = await provider.getSigner();
      
      // Tạo contract instance cho Payment contract
      const paymentContract = new ethers.Contract(
        contractInfo.contracts.LensArtPayment,
        contractInfo.abis.LensArtPayment,
        signer
      );
      
      const amountWei = ethers.parseEther(amount.toString());
      const ipfsHash = ''; // Có thể lấy từ backend
      
      // Gọi initiatePayment
      console.log('Calling initiatePayment on Payment contract...');
      console.log('Order ID:', orderId);
      console.log('Amount:', amount, 'LENS');
      
      const tx = await paymentContract.initiatePayment(
        orderId,
        amountWei,
        ipfsHash
      );
      
      console.log('Payment transaction sent:', tx.hash);
      setLoading(true);
      
      // Đợi transaction được confirm
      const receipt = await tx.wait();
      console.log('Payment transaction confirmed:', receipt);
      
      // Chuyển sang bước 3
      setStep(3);
      
    } catch (error) {
      console.error('Error initiating payment:', error);
      setError(error.message);
      alert('Lỗi khi thanh toán: ' + error.message);
    } finally {
      setLoading(false);
    }
  };

  // Auto load data when account or contractInfo changes
  useEffect(() => {
    if (account && contractInfo) {
      loadBalance();
      loadAllowance();
    }
  }, [account, contractInfo]);

  if (!contractInfo) {
    return <div>Loading contract info...</div>;
  }

  return (
    <div className="crypto-payment-flow">
      <h2>Thanh toán bằng Crypto</h2>
      
      {!account ? (
        <button onClick={connectWallet}>Connect Wallet</button>
      ) : (
        <>
          <div className="payment-info">
            <p>Order ID: #{orderId}</p>
            <p>Số tiền: {amount} LENS</p>
            <p>Số dư hiện tại: {balance || '0'} LENS</p>
            <p>Allowance: {allowance || '0'} LENS</p>
            <p>Địa chỉ ví: {account}</p>
            <p>Network: Sepolia ✓</p>
          </div>

          {error && (
            <div className="error">
              {error}
            </div>
          )}

          <div className="payment-steps">
            <div className={step >= 1 ? 'active' : ''}>1 Approve</div>
            <div className={step >= 2 ? 'active' : ''}>2 Pay</div>
            <div className={step >= 3 ? 'active' : ''}>3 Verify</div>
          </div>

          {step === 1 && (
            <button 
              onClick={handleApprove} 
              disabled={loading || parseFloat(allowance || 0) >= parseFloat(amount)}
            >
              {loading ? 'Đang xử lý...' : 'Approve Token'}
            </button>
          )}

          {step === 2 && (
            <button 
              onClick={handlePay} 
              disabled={loading}
            >
              {loading ? 'Đang xử lý...' : 'Pay'}
            </button>
          )}

          {step === 3 && (
            <div>
              <p>✓ Thanh toán thành công!</p>
            </div>
          )}
        </>
      )}
    </div>
  );
}

export default CryptoPaymentFlow;

