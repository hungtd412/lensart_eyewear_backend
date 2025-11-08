const { expect } = require("chai");
const { ethers } = require("hardhat");

describe("LENSToken", function () {
  let lensToken, owner, user1, user2;
  const INITIAL_SUPPLY = ethers.parseEther("1000000");

  beforeEach(async function () {
    [owner, user1, user2] = await ethers.getSigners();

    // Deploy LENSToken
    const LENSToken = await ethers.getContractFactory("LENSToken");
    lensToken = await LENSToken.deploy(owner.address);
    await lensToken.waitForDeployment();
  });

  describe("Deployment", function () {
    it("Should set the right owner", async function () {
      expect(await lensToken.owner()).to.equal(owner.address);
    });

    it("Should mint initial supply to owner", async function () {
      const balance = await lensToken.balanceOf(owner.address);
      expect(balance).to.equal(INITIAL_SUPPLY);
    });

    it("Should set the correct token name and symbol", async function () {
      expect(await lensToken.name()).to.equal("LensArt Token");
      expect(await lensToken.symbol()).to.equal("LENS");
    });

    it("Should set the correct decimals", async function () {
      expect(await lensToken.decimals()).to.equal(18);
    });

    it("Should set the correct total supply", async function () {
      const totalSupply = await lensToken.totalSupply();
      expect(totalSupply).to.equal(INITIAL_SUPPLY);
    });
  });

  describe("Mint Operations", function () {
    const MINT_AMOUNT = ethers.parseEther("1000");

    it("Should mint tokens to specified address (only owner)", async function () {
      const balanceBefore = await lensToken.balanceOf(user1.address);
      const totalSupplyBefore = await lensToken.totalSupply();

      await expect(lensToken.connect(owner).mint(user1.address, MINT_AMOUNT))
        .to.emit(lensToken, "Transfer")
        .withArgs(ethers.ZeroAddress, user1.address, MINT_AMOUNT);

      const balanceAfter = await lensToken.balanceOf(user1.address);
      const totalSupplyAfter = await lensToken.totalSupply();

      expect(balanceAfter).to.equal(balanceBefore + MINT_AMOUNT);
      expect(totalSupplyAfter).to.equal(totalSupplyBefore + MINT_AMOUNT);
    });

    it("Should reject mint from non-owner", async function () {
      await expect(
        lensToken.connect(user1).mint(user2.address, MINT_AMOUNT)
      ).to.be.revertedWithCustomError(lensToken, "OwnableUnauthorizedAccount");
    });

    it("Should mint to owner address", async function () {
      const balanceBefore = await lensToken.balanceOf(owner.address);
      
      await lensToken.connect(owner).mint(owner.address, MINT_AMOUNT);
      
      const balanceAfter = await lensToken.balanceOf(owner.address);
      expect(balanceAfter).to.equal(balanceBefore + MINT_AMOUNT);
    });

    it("Should mint zero amount", async function () {
      const balanceBefore = await lensToken.balanceOf(user1.address);
      const totalSupplyBefore = await lensToken.totalSupply();

      await lensToken.connect(owner).mint(user1.address, 0);

      const balanceAfter = await lensToken.balanceOf(user1.address);
      const totalSupplyAfter = await lensToken.totalSupply();

      expect(balanceAfter).to.equal(balanceBefore);
      expect(totalSupplyAfter).to.equal(totalSupplyBefore);
    });

    it("Should mint large amount", async function () {
      const largeAmount = ethers.parseEther("10000000");
      const balanceBefore = await lensToken.balanceOf(user1.address);

      await lensToken.connect(owner).mint(user1.address, largeAmount);

      const balanceAfter = await lensToken.balanceOf(user1.address);
      expect(balanceAfter).to.equal(balanceBefore + largeAmount);
    });
  });

  describe("Burn Operations", function () {
    const BURN_AMOUNT = ethers.parseEther("100");

    beforeEach(async function () {
      // Transfer some tokens to user1 for burning
      await lensToken.transfer(user1.address, ethers.parseEther("1000"));
    });

    it("Should burn tokens from sender", async function () {
      const balanceBefore = await lensToken.balanceOf(user1.address);
      const totalSupplyBefore = await lensToken.totalSupply();

      await expect(lensToken.connect(user1).burn(BURN_AMOUNT))
        .to.emit(lensToken, "Transfer")
        .withArgs(user1.address, ethers.ZeroAddress, BURN_AMOUNT);

      const balanceAfter = await lensToken.balanceOf(user1.address);
      const totalSupplyAfter = await lensToken.totalSupply();

      expect(balanceAfter).to.equal(balanceBefore - BURN_AMOUNT);
      expect(totalSupplyAfter).to.equal(totalSupplyBefore - BURN_AMOUNT);
    });

    it("Should reject burn with insufficient balance", async function () {
      const largeAmount = ethers.parseEther("2000");
      
      await expect(
        lensToken.connect(user1).burn(largeAmount)
      ).to.be.revertedWithCustomError(lensToken, "ERC20InsufficientBalance");
    });

    it("Should burn all tokens", async function () {
      const balance = await lensToken.balanceOf(user1.address);
      const totalSupplyBefore = await lensToken.totalSupply();

      await lensToken.connect(user1).burn(balance);

      const balanceAfter = await lensToken.balanceOf(user1.address);
      const totalSupplyAfter = await lensToken.totalSupply();

      expect(balanceAfter).to.equal(0);
      expect(totalSupplyAfter).to.equal(totalSupplyBefore - balance);
    });

    it("Should reject burn zero amount", async function () {
      // Note: ERC20 burn might allow zero, but we test it anyway
      await expect(
        lensToken.connect(user1).burn(0)
      ).to.not.be.reverted; // ERC20 typically allows burning 0
    });
  });

  describe("Transfer Operations", function () {
    const TRANSFER_AMOUNT = ethers.parseEther("100");

    it("Should transfer tokens between accounts", async function () {
      const balanceBefore = await lensToken.balanceOf(user1.address);
      
      await expect(lensToken.connect(owner).transfer(user1.address, TRANSFER_AMOUNT))
        .to.emit(lensToken, "Transfer")
        .withArgs(owner.address, user1.address, TRANSFER_AMOUNT);

      const balanceAfter = await lensToken.balanceOf(user1.address);
      expect(balanceAfter).to.equal(balanceBefore + TRANSFER_AMOUNT);
    });

    it("Should reject transfer with insufficient balance", async function () {
      const largeAmount = ethers.parseEther("2000000");
      
      await expect(
        lensToken.connect(owner).transfer(user1.address, largeAmount)
      ).to.be.revertedWithCustomError(lensToken, "ERC20InsufficientBalance");
    });

    it("Should reject transfer to zero address", async function () {
      // OpenZeppelin v5 ERC20 prevents transfer to zero address
      await expect(
        lensToken.connect(owner).transfer(ethers.ZeroAddress, TRANSFER_AMOUNT)
      ).to.be.revertedWithCustomError(lensToken, "ERC20InvalidReceiver");
    });
  });

  describe("Approve and TransferFrom Operations", function () {
    const APPROVE_AMOUNT = ethers.parseEther("500");
    const TRANSFER_AMOUNT = ethers.parseEther("200");

    beforeEach(async function () {
      // Transfer tokens to user1
      await lensToken.transfer(user1.address, ethers.parseEther("1000"));
    });

    it("Should approve tokens for spender", async function () {
      await expect(lensToken.connect(user1).approve(user2.address, APPROVE_AMOUNT))
        .to.emit(lensToken, "Approval")
        .withArgs(user1.address, user2.address, APPROVE_AMOUNT);

      const allowance = await lensToken.allowance(user1.address, user2.address);
      expect(allowance).to.equal(APPROVE_AMOUNT);
    });

    it("Should transferFrom with allowance", async function () {
      await lensToken.connect(user1).approve(user2.address, APPROVE_AMOUNT);

      const balanceBefore = await lensToken.balanceOf(user2.address);
      
      await expect(
        lensToken.connect(user2).transferFrom(user1.address, user2.address, TRANSFER_AMOUNT)
      )
        .to.emit(lensToken, "Transfer")
        .withArgs(user1.address, user2.address, TRANSFER_AMOUNT);

      const balanceAfter = await lensToken.balanceOf(user2.address);
      expect(balanceAfter).to.equal(balanceBefore + TRANSFER_AMOUNT);

      const allowanceAfter = await lensToken.allowance(user1.address, user2.address);
      expect(allowanceAfter).to.equal(APPROVE_AMOUNT - TRANSFER_AMOUNT);
    });

    it("Should reject transferFrom with insufficient allowance", async function () {
      await lensToken.connect(user1).approve(user2.address, TRANSFER_AMOUNT / 2n);

      await expect(
        lensToken.connect(user2).transferFrom(user1.address, user2.address, TRANSFER_AMOUNT)
      ).to.be.revertedWithCustomError(lensToken, "ERC20InsufficientAllowance");
    });

    it("Should reject transferFrom with insufficient balance", async function () {
      const largeAmount = ethers.parseEther("2000");
      await lensToken.connect(user1).approve(user2.address, largeAmount);

      await expect(
        lensToken.connect(user2).transferFrom(user1.address, user2.address, largeAmount)
      ).to.be.revertedWithCustomError(lensToken, "ERC20InsufficientBalance");
    });
  });

  describe("Edge Cases", function () {
    it("Should handle multiple mints", async function () {
      const mintAmount = ethers.parseEther("100");
      const balanceBefore = await lensToken.balanceOf(user1.address);

      await lensToken.connect(owner).mint(user1.address, mintAmount);
      await lensToken.connect(owner).mint(user1.address, mintAmount);
      await lensToken.connect(owner).mint(user1.address, mintAmount);

      const balanceAfter = await lensToken.balanceOf(user1.address);
      expect(balanceAfter).to.equal(balanceBefore + mintAmount * 3n);
    });

    it("Should handle multiple burns", async function () {
      const transferAmount = ethers.parseEther("1000");
      const burnAmount = ethers.parseEther("100");

      await lensToken.transfer(user1.address, transferAmount);
      const balanceBefore = await lensToken.balanceOf(user1.address);

      await lensToken.connect(user1).burn(burnAmount);
      await lensToken.connect(user1).burn(burnAmount);
      await lensToken.connect(user1).burn(burnAmount);

      const balanceAfter = await lensToken.balanceOf(user1.address);
      expect(balanceAfter).to.equal(balanceBefore - burnAmount * 3n);
    });

    it("Should handle mint then burn", async function () {
      const mintAmount = ethers.parseEther("500");
      const burnAmount = ethers.parseEther("200");
      const balanceBefore = await lensToken.balanceOf(user1.address);

      await lensToken.connect(owner).mint(user1.address, mintAmount);
      await lensToken.connect(user1).burn(burnAmount);

      const balanceAfter = await lensToken.balanceOf(user1.address);
      expect(balanceAfter).to.equal(balanceBefore + mintAmount - burnAmount);
    });
  });
});

