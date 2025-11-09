/**
 * Ví dụ hoàn chỉnh sử dụng Web3 với React
 * File này minh họa 3 chức năng tương tác on-chain:
 * 1. Gửi giao dịch (Send Transaction)
 * 2. Đọc dữ liệu (Read Data)
 * 3. Hiển thị trạng thái (Display Status)
 */

import React, { useState, useEffect } from 'react';
import { useWallet, useContractInfo, useContract, useContractRead, useContractWrite, useNetwork } from './react-hooks';
import { parseTokenAmount, formatTokenAmount, handleTransactionError } from './web3-helpers';

const BACKEND_URL = 'http://localhost:8000';
const NETWORK = 'sepolia';
const CHAIN_ID = 11155111; // Sepolia

function LensArtDApp() {
  // 1. Kết nối wallet
  const { account, connect, disconnect, isConnected, signer } = useWallet();
  
  // 2. Load contract info từ backend
  const { contractInfo, loading: loadingContracts } = useContractInfo(BACKEND_URL, NETWORK);
  
  // 3. Kiểm tra network
  const { isCorrectNetwork, check: checkNetwork, switchToNetwork } = useNetwork(CHAIN_ID);
  
  // 4. Tạo contract instances
  const { contract: tokenContract } = useContract(
    contractInfo?.contracts.LENSToken,
    contractInfo?.abis.LENSToken,
    signer
  );
  
  const { contract: paymentContract } = useContract(
    contractInfo?.contracts.LensArtPayment,
    contractInfo?.abis.LensArtPayment,
    signer
  );

  // 5. Đọc dữ liệu (Read Data) - Chức năng 2
  const { data: tokenBalance, loading: loadingBalance } = useContractRead(
    tokenContract,
    'balanceOf',
    [account]
  );

  // 6. Gửi transaction (Send Transaction) - Chức năng 1
  const { 
    write: transferTokens, 
    loading: transferring, 
    error: transferError,
    txHash: transferTxHash,
    receipt: transferReceipt
  } = useContractWrite(tokenContract, 'transfer');

  const { 
    write: approveTokens, 
    loading: approving,
    txHash: approveTxHash
  } = useContractWrite(tokenContract, 'approve');

  // State cho form
  const [recipientAddress, setRecipientAddress] = useState('');
  const [transferAmount, setTransferAmount] = useState('');
  const [approveAmount, setApproveAmount] = useState('');

  // Kiểm tra network khi account thay đổi
  useEffect(() => {
    if (signer) {
      checkNetwork(signer.provider);
    }
  }, [signer, checkNetwork]);

  // Handle transfer tokens
  const handleTransfer = async (e) => {
    e.preventDefault();
    if (!tokenContract || !recipientAddress || !transferAmount) return;

    try {
      const amount = parseTokenAmount(transferAmount);
      await transferTokens(recipientAddress, amount);
      setTransferAmount('');
      setRecipientAddress('');
    } catch (error) {
      console.error('Transfer error:', error);
    }
  };

  // Handle approve tokens
  const handleApprove = async (e) => {
    e.preventDefault();
    if (!tokenContract || !paymentContract || !approveAmount) return;

    try {
      const amount = parseTokenAmount(approveAmount);
      await approveTokens(contractInfo.contracts.LensArtPayment, amount);
      setApproveAmount('');
    } catch (error) {
      console.error('Approve error:', error);
    }
  };

  if (loadingContracts) {
    return <div className="loading">Loading contracts...</div>;
  }

  return (
    <div className="lensart-dapp">
      <header>
        <h1>LensArt DApp</h1>
        
        {/* Wallet Connection */}
        {!isConnected ? (
          <button onClick={connect} className="connect-btn">
            Connect Wallet
          </button>
        ) : (
          <div className="wallet-info">
            <p>Connected: {account}</p>
            <button onClick={disconnect}>Disconnect</button>
          </div>
        )}
      </header>

      {isConnected && (
        <div className="main-content">
          {/* Network Status - Chức năng 3: Hiển thị trạng thái */}
          {!isCorrectNetwork ? (
            <div className="network-warning">
              <p>Please switch to Sepolia network</p>
              <button onClick={switchToNetwork}>Switch Network</button>
            </div>
          ) : (
            <div className="network-status">
              ✓ Connected to Sepolia Testnet
            </div>
          )}

          {/* Display Status - Chức năng 3: Hiển thị trạng thái */}
          <section className="status-section">
            <h2>Status</h2>
            
            {/* Token Balance - Read Data */}
            <div className="balance-display">
              <h3>Your LENS Token Balance</h3>
              {loadingBalance ? (
                <p>Loading...</p>
              ) : (
                <p className="balance-amount">
                  {tokenBalance ? formatTokenAmount(tokenBalance) : '0'} LENS
                </p>
              )}
            </div>

            {/* Transaction Status */}
            {transferTxHash && (
              <div className="transaction-status">
                <h3>Last Transfer Transaction</h3>
                <p>Hash: {transferTxHash}</p>
                <p>Status: {transferReceipt ? '✓ Confirmed' : '⏳ Pending'}</p>
                {contractInfo?.explorerUrl && (
                  <a 
                    href={`${contractInfo.explorerUrl}/tx/${transferTxHash}`}
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    View on Etherscan
                  </a>
                )}
              </div>
            )}

            {approveTxHash && (
              <div className="transaction-status">
                <h3>Last Approve Transaction</h3>
                <p>Hash: {approveTxHash}</p>
                <p>Status: ⏳ Pending</p>
              </div>
            )}
          </section>

          {/* Send Transaction - Chức năng 1: Gửi giao dịch */}
          <section className="transaction-section">
            <h2>Send Transaction</h2>
            
            {/* Transfer Tokens Form */}
            <form onSubmit={handleTransfer} className="transaction-form">
              <h3>Transfer LENS Tokens</h3>
              <div>
                <label>Recipient Address:</label>
                <input
                  type="text"
                  value={recipientAddress}
                  onChange={(e) => setRecipientAddress(e.target.value)}
                  placeholder="0x..."
                  required
                />
              </div>
              <div>
                <label>Amount:</label>
                <input
                  type="number"
                  value={transferAmount}
                  onChange={(e) => setTransferAmount(e.target.value)}
                  placeholder="100"
                  step="0.01"
                  required
                />
              </div>
              <button 
                type="submit" 
                disabled={transferring || !isCorrectNetwork}
                className="submit-btn"
              >
                {transferring ? 'Transferring...' : 'Transfer Tokens'}
              </button>
              {transferError && (
                <p className="error">{handleTransactionError(transferError)}</p>
              )}
            </form>

            {/* Approve Tokens Form */}
            <form onSubmit={handleApprove} className="transaction-form">
              <h3>Approve Tokens for Payment</h3>
              <div>
                <label>Amount to Approve:</label>
                <input
                  type="number"
                  value={approveAmount}
                  onChange={(e) => setApproveAmount(e.target.value)}
                  placeholder="1000"
                  step="0.01"
                  required
                />
              </div>
              <button 
                type="submit" 
                disabled={approving || !isCorrectNetwork}
                className="submit-btn"
              >
                {approving ? 'Approving...' : 'Approve Tokens'}
              </button>
            </form>
          </section>

          {/* Read Data - Chức năng 2: Đọc dữ liệu */}
          <section className="read-data-section">
            <h2>Read Data from Contracts</h2>
            
            <div className="data-card">
              <h3>Contract Addresses</h3>
              <p>LENSToken: {contractInfo?.contracts.LENSToken}</p>
              <p>LensArtPayment: {contractInfo?.contracts.LensArtPayment}</p>
              <p>LensArtOrderNFT: {contractInfo?.contracts.LensArtOrderNFT}</p>
            </div>

            <div className="data-card">
              <h3>Network Info</h3>
              <p>Network: {contractInfo?.network}</p>
              <p>Chain ID: {contractInfo?.chainId}</p>
              <p>RPC URL: {contractInfo?.rpcUrl}</p>
            </div>
          </section>
        </div>
      )}
    </div>
  );
}

export default LensArtDApp;

// CSS Styles (có thể tách ra file riêng)
const styles = `
  .lensart-dapp {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  .loading {
    text-align: center;
    padding: 40px;
  }

  .connect-btn {
    padding: 10px 20px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
  }

  .wallet-info {
    display: flex;
    gap: 10px;
    align-items: center;
  }

  .network-warning {
    background: #ff9800;
    color: white;
    padding: 15px;
    border-radius: 5px;
    margin: 20px 0;
  }

  .network-status {
    background: #4CAF50;
    color: white;
    padding: 10px;
    border-radius: 5px;
    margin: 20px 0;
  }

  .balance-display {
    background: #f5f5f5;
    padding: 20px;
    border-radius: 5px;
    margin: 20px 0;
  }

  .balance-amount {
    font-size: 24px;
    font-weight: bold;
    color: #2196F3;
  }

  .transaction-status {
    background: #e3f2fd;
    padding: 15px;
    border-radius: 5px;
    margin: 10px 0;
  }

  .transaction-form {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 5px;
    margin: 20px 0;
  }

  .transaction-form input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  .submit-btn {
    padding: 10px 20px;
    background: #2196F3;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
  }

  .submit-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
  }

  .error {
    color: #f44336;
    margin-top: 10px;
  }

  .data-card {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 5px;
    margin: 10px 0;
  }

  .data-card p {
    margin: 5px 0;
    word-break: break-all;
  }
`;

// Inject styles (trong production nên dùng CSS module hoặc styled-components)
if (typeof document !== 'undefined') {
  const styleSheet = document.createElement('style');
  styleSheet.type = 'text/css';
  styleSheet.innerText = styles;
  document.head.appendChild(styleSheet);
}

