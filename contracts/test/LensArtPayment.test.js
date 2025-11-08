const { expect } = require("chai");
const { ethers } = require("hardhat");

describe("LensArt Payment System", function () {
  let lensToken, paymentContract, owner, customer, feeRecipient;
  const INITIAL_SUPPLY = ethers.parseEther("1000000");
  const PAYMENT_AMOUNT = ethers.parseEther("100");

  beforeEach(async function () {
    [owner, customer, feeRecipient] = await ethers.getSigners();

    // Deploy LENSToken
    const LENSToken = await ethers.getContractFactory("LENSToken");
    lensToken = await LENSToken.deploy(owner.address);
    await lensToken.waitForDeployment();

    // Deploy Payment Contract
    const LensArtPayment = await ethers.getContractFactory("LensArtPayment");
    paymentContract = await LensArtPayment.deploy(
      await lensToken.getAddress(),
      feeRecipient.address,
      owner.address
    );
    await paymentContract.waitForDeployment();

    // Transfer tokens to customer
    await lensToken.transfer(customer.address, INITIAL_SUPPLY);
  });

  describe("Token Operations", function () {
    it("Should transfer tokens to customer", async function () {
      const balance = await lensToken.balanceOf(customer.address);
      expect(balance).to.equal(INITIAL_SUPPLY);
    });
  });

  describe("Payment Operations", function () {
    it("Should initiate payment", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await expect(
        paymentContract
          .connect(customer)
          .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash")
      )
        .to.emit(paymentContract, "PaymentInitiated")
        .withArgs(1, customer.address, PAYMENT_AMOUNT, "QmTestHash");
    });

    it("Should reject payment with insufficient balance", async function () {
      const largeAmount = ethers.parseEther("2000000");
      await expect(
        paymentContract
          .connect(customer)
          .initiatePayment(1, largeAmount, "QmTestHash")
      ).to.be.revertedWith("Insufficient token balance");
    });

    it("Should confirm payment (only owner)", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash");

      await expect(
        paymentContract.connect(owner).confirmPayment(1)
      )
        .to.emit(paymentContract, "PaymentCompleted")
        .withArgs(1, customer.address, PAYMENT_AMOUNT);
    });

    it("Should reject confirm payment from non-owner", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash");

      await expect(
        paymentContract.connect(customer).confirmPayment(1)
      ).to.be.revertedWithCustomError(paymentContract, "OwnableUnauthorizedAccount");
    });

    it("Should reject payment with insufficient allowance", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT / 2n
      );

      await expect(
        paymentContract
          .connect(customer)
          .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash")
      ).to.be.revertedWith("Token allowance insufficient");
    });

    it("Should reject payment with zero amount", async function () {
      await expect(
        paymentContract
          .connect(customer)
          .initiatePayment(1, 0, "QmTestHash")
      ).to.be.revertedWith("Amount must be greater than 0");
    });

    it("Should reject duplicate payment for same orderId", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT * 2n
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash");

      await expect(
        paymentContract
          .connect(customer)
          .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash2")
      ).to.be.revertedWith("Payment already exists");
    });

    it("Should transfer tokens correctly on payment confirmation", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash");

      const customerBalanceBefore = await lensToken.balanceOf(customer.address);
      const contractBalanceBefore = await lensToken.balanceOf(await paymentContract.getAddress());
      const feeRecipientBalanceBefore = await lensToken.balanceOf(feeRecipient.address);

      await paymentContract.connect(owner).confirmPayment(1);

      const customerBalanceAfter = await lensToken.balanceOf(customer.address);
      const contractBalanceAfter = await lensToken.balanceOf(await paymentContract.getAddress());
      const feeRecipientBalanceAfter = await lensToken.balanceOf(feeRecipient.address);

      // Customer should lose the full payment amount
      expect(customerBalanceAfter).to.equal(customerBalanceBefore - PAYMENT_AMOUNT);
      
      // Contract should receive net amount (amount - fee)
      const expectedFee = (PAYMENT_AMOUNT * 50n) / 10000n;
      const expectedNetAmount = PAYMENT_AMOUNT - expectedFee;
      expect(contractBalanceAfter).to.equal(contractBalanceBefore + expectedNetAmount);
      
      // Fee recipient should receive the fee
      expect(feeRecipientBalanceAfter).to.equal(feeRecipientBalanceBefore + expectedFee);
    });

    it("Should calculate fee correctly (0.5%)", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash");

      const feeRecipientBalanceBefore = await lensToken.balanceOf(feeRecipient.address);
      
      await paymentContract.connect(owner).confirmPayment(1);

      const feeRecipientBalanceAfter = await lensToken.balanceOf(feeRecipient.address);
      const expectedFee = (PAYMENT_AMOUNT * 50n) / 10000n; // 0.5%
      
      expect(feeRecipientBalanceAfter - feeRecipientBalanceBefore).to.equal(expectedFee);
    });

    it("Should reject confirming non-existent payment", async function () {
      await expect(
        paymentContract.connect(owner).confirmPayment(999)
      ).to.be.revertedWith("Payment does not exist");
    });

    it("Should reject confirming already completed payment", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash");

      await paymentContract.connect(owner).confirmPayment(1);

      await expect(
        paymentContract.connect(owner).confirmPayment(1)
      ).to.be.revertedWith("Payment already completed");
    });

    it("Should reject confirming refunded payment", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash");

      await paymentContract.connect(owner).refundPayment(1);

      await expect(
        paymentContract.connect(owner).confirmPayment(1)
      ).to.be.revertedWith("Payment was refunded");
    });
  });

  describe("Refund Operations", function () {
    beforeEach(async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash");
    });

    it("Should refund payment (only owner)", async function () {
      await expect(
        paymentContract.connect(owner).refundPayment(1)
      )
        .to.emit(paymentContract, "PaymentRefunded")
        .withArgs(1, customer.address, PAYMENT_AMOUNT);

      const payment = await paymentContract.getPayment(1);
      expect(payment.refunded).to.be.true;
    });

    it("Should reject refund from non-owner", async function () {
      await expect(
        paymentContract.connect(customer).refundPayment(1)
      ).to.be.revertedWithCustomError(paymentContract, "OwnableUnauthorizedAccount");
    });

    it("Should reject refunding non-existent payment", async function () {
      await expect(
        paymentContract.connect(owner).refundPayment(999)
      ).to.be.revertedWith("Payment does not exist");
    });

    it("Should reject refunding already refunded payment", async function () {
      await paymentContract.connect(owner).refundPayment(1);

      await expect(
        paymentContract.connect(owner).refundPayment(1)
      ).to.be.revertedWith("Already refunded");
    });

    it("Should reject refunding completed payment", async function () {
      await paymentContract.connect(owner).confirmPayment(1);

      await expect(
        paymentContract.connect(owner).refundPayment(1)
      ).to.be.revertedWith("Cannot refund completed payment");
    });
  });

  describe("Fee Management", function () {
    it("Should get default payment fee (0.5%)", async function () {
      const fee = await paymentContract.paymentFee();
      expect(fee).to.equal(50); // 50/10000 = 0.5%
    });

    it("Should get fee recipient address", async function () {
      const recipient = await paymentContract.feeRecipient();
      expect(recipient).to.equal(feeRecipient.address);
    });

    it("Should update fee recipient (only owner)", async function () {
      const [newRecipient] = await ethers.getSigners();
      
      await expect(
        paymentContract.connect(owner).setFeeRecipient(newRecipient.address)
      ).to.not.be.reverted;

      const recipient = await paymentContract.feeRecipient();
      expect(recipient).to.equal(newRecipient.address);
    });

    it("Should reject setting fee recipient from non-owner", async function () {
      const [newRecipient] = await ethers.getSigners();
      
      await expect(
        paymentContract.connect(customer).setFeeRecipient(newRecipient.address)
      ).to.be.revertedWithCustomError(paymentContract, "OwnableUnauthorizedAccount");
    });

    it("Should reject setting fee recipient to zero address", async function () {
      await expect(
        paymentContract.connect(owner).setFeeRecipient(ethers.ZeroAddress)
      ).to.be.revertedWith("Invalid address");
    });
  });

  describe("Payment Information", function () {
    beforeEach(async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(1, PAYMENT_AMOUNT, "QmTestHash");
    });

    it("Should get payment information", async function () {
      const payment = await paymentContract.getPayment(1);
      
      expect(payment.orderId).to.equal(1);
      expect(payment.customer).to.equal(customer.address);
      expect(payment.amount).to.equal(PAYMENT_AMOUNT);
      expect(payment.ipfsHash).to.equal("QmTestHash");
      expect(payment.completed).to.be.false;
      expect(payment.refunded).to.be.false;
      expect(payment.timestamp).to.be.greaterThan(0);
    });

    it("Should get user payments list", async function () {
      const userPayments = await paymentContract.getUserPayments(customer.address);
      expect(userPayments.length).to.equal(1);
      expect(userPayments[0]).to.equal(1);
    });

    it("Should add multiple payments to user payments list", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT * 2n
      );

      await paymentContract
        .connect(customer)
        .initiatePayment(2, PAYMENT_AMOUNT, "QmTestHash2");

      const userPayments = await paymentContract.getUserPayments(customer.address);
      expect(userPayments.length).to.equal(2);
      expect(userPayments[0]).to.equal(1);
      expect(userPayments[1]).to.equal(2);
    });

    it("Should return empty array for user with no payments", async function () {
      const [newUser] = await ethers.getSigners();
      const userPayments = await paymentContract.getUserPayments(newUser.address);
      expect(userPayments.length).to.equal(0);
    });

    it("Should update payment status after confirmation", async function () {
      await paymentContract.connect(owner).confirmPayment(1);
      
      const payment = await paymentContract.getPayment(1);
      expect(payment.completed).to.be.true;
      expect(payment.refunded).to.be.false;
    });

    it("Should update payment status after refund", async function () {
      await paymentContract.connect(owner).refundPayment(1);
      
      const payment = await paymentContract.getPayment(1);
      expect(payment.completed).to.be.false;
      expect(payment.refunded).to.be.true;
    });
  });

  describe("Edge Cases", function () {
    it("Should handle payment with minimum amount", async function () {
      const minAmount = 1n;
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        minAmount
      );

      await expect(
        paymentContract
          .connect(customer)
          .initiatePayment(1, minAmount, "QmTestHash")
      )
        .to.emit(paymentContract, "PaymentInitiated")
        .withArgs(1, customer.address, minAmount, "QmTestHash");
    });

    it("Should handle payment with large amount", async function () {
      const largeAmount = ethers.parseEther("500000");
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        largeAmount
      );

      await expect(
        paymentContract
          .connect(customer)
          .initiatePayment(1, largeAmount, "QmTestHash")
      )
        .to.emit(paymentContract, "PaymentInitiated")
        .withArgs(1, customer.address, largeAmount, "QmTestHash");
    });

    it("Should handle payment with empty IPFS hash", async function () {
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await expect(
        paymentContract
          .connect(customer)
          .initiatePayment(1, PAYMENT_AMOUNT, "")
      )
        .to.emit(paymentContract, "PaymentInitiated")
        .withArgs(1, customer.address, PAYMENT_AMOUNT, "");
    });

    it("Should handle payment with very long IPFS hash", async function () {
      const longHash = "Qm" + "a".repeat(100);
      await lensToken.connect(customer).approve(
        await paymentContract.getAddress(),
        PAYMENT_AMOUNT
      );

      await expect(
        paymentContract
          .connect(customer)
          .initiatePayment(1, PAYMENT_AMOUNT, longHash)
      )
        .to.emit(paymentContract, "PaymentInitiated")
        .withArgs(1, customer.address, PAYMENT_AMOUNT, longHash);
    });
  });
});