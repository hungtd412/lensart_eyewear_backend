// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/access/Ownable.sol";
import "@openzeppelin/contracts/utils/ReentrancyGuard.sol";
import "./LENSToken.sol";

contract LensArtPayment is Ownable, ReentrancyGuard {
    LENSToken public lensToken;
    
    struct Payment {
        uint256 orderId;
        address customer;
        uint256 amount;
        string ipfsHash;
        uint256 timestamp;
        bool completed;
        bool refunded;
    }

    mapping(uint256 => Payment) public payments;
    mapping(address => uint256[]) public userPayments;
    
    uint256 public paymentFee = 50; // 0.5% fee (50/10000)
    address public feeRecipient;
    
    event PaymentInitiated(
        uint256 indexed orderId,
        address indexed customer,
        uint256 amount,
        string ipfsHash
    );
    
    event PaymentCompleted(
        uint256 indexed orderId,
        address indexed customer,
        uint256 amount
    );
    
    event PaymentRefunded(
        uint256 indexed orderId,
        address indexed customer,
        uint256 amount
    );

    constructor(address _lensToken, address _feeRecipient, address initialOwner) Ownable(initialOwner) {
        lensToken = LENSToken(_lensToken);
        feeRecipient = _feeRecipient;
    }

    // Ràng buộc: Chỉ owner mới được thay đổi fee recipient
    function setFeeRecipient(address _feeRecipient) public onlyOwner {
        require(_feeRecipient != address(0), "Invalid address");
        feeRecipient = _feeRecipient;
    }

    // Ràng buộc: Kiểm tra số dư token trước khi thanh toán
    function initiatePayment(
        uint256 _orderId,
        uint256 _amount,
        string memory _ipfsHash
    ) public nonReentrant {
        require(_amount > 0, "Amount must be greater than 0");
        require(payments[_orderId].orderId == 0, "Payment already exists");
        require(
            lensToken.balanceOf(msg.sender) >= _amount,
            "Insufficient token balance"
        );
        require(
            lensToken.allowance(msg.sender, address(this)) >= _amount,
            "Token allowance insufficient"
        );

        payments[_orderId] = Payment({
            orderId: _orderId,
            customer: msg.sender,
            amount: _amount,
            ipfsHash: _ipfsHash,
            timestamp: block.timestamp,
            completed: false,
            refunded: false
        });

        userPayments[msg.sender].push(_orderId);

        emit PaymentInitiated(_orderId, msg.sender, _amount, _ipfsHash);
    }

    // Chỉ owner mới được xác nhận thanh toán
    function confirmPayment(uint256 _orderId) public onlyOwner {
        Payment storage payment = payments[_orderId];
        require(payment.orderId != 0, "Payment does not exist");
        require(!payment.completed, "Payment already completed");
        require(!payment.refunded, "Payment was refunded");

        uint256 feeAmount = (payment.amount * paymentFee) / 10000;

        // Transfer token từ customer đến contract
        require(
            lensToken.transferFrom(payment.customer, address(this), payment.amount),
            "Token transfer failed"
        );

        // Transfer fee cho fee recipient
        if (feeAmount > 0) {
            require(
                lensToken.transfer(feeRecipient, feeAmount),
                "Fee transfer failed"
            );
        }

        payment.completed = true;

        emit PaymentCompleted(_orderId, payment.customer, payment.amount);
    }

    // Refund payment (chỉ owner)
    function refundPayment(uint256 _orderId) public onlyOwner {
        Payment storage payment = payments[_orderId];
        require(payment.orderId != 0, "Payment does not exist");
        require(!payment.refunded, "Already refunded");
        require(!payment.completed, "Cannot refund completed payment");

        payment.refunded = true;

        emit PaymentRefunded(_orderId, payment.customer, payment.amount);
    }

    // Đọc thông tin payment
    function getPayment(uint256 _orderId) public view returns (Payment memory) {
        return payments[_orderId];
    }

    // Lấy danh sách payments của user
    function getUserPayments(address _user) public view returns (uint256[] memory) {
        return userPayments[_user];
    }
}