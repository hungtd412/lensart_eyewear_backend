const { expect } = require("chai");
const { ethers } = require("hardhat");

describe("LensArtOrderNFT", function () {
  let nftContract, owner, user1, user2;
  const IPFS_HASH = "QmTestHash123";
  const IPFS_HASH_2 = "QmTestHash456";

  beforeEach(async function () {
    [owner, user1, user2] = await ethers.getSigners();

    // Deploy LensArtOrderNFT
    const LensArtOrderNFT = await ethers.getContractFactory("LensArtOrderNFT");
    nftContract = await LensArtOrderNFT.deploy(owner.address);
    await nftContract.waitForDeployment();
  });

  describe("Deployment", function () {
    it("Should set the right owner", async function () {
      expect(await nftContract.owner()).to.equal(owner.address);
    });

    it("Should set the correct token name and symbol", async function () {
      expect(await nftContract.name()).to.equal("LensArt Order NFT");
      expect(await nftContract.symbol()).to.equal("LENSORDER");
    });

    it("Should start with token ID 0 (no tokens minted)", async function () {
      // Token IDs start from 1, so totalSupply should be 0 initially
      await expect(nftContract.ownerOf(1)).to.be.revertedWithCustomError(
        nftContract,
        "ERC721NonexistentToken"
      );
    });
  });

  describe("Mint Operations", function () {
    it("Should mint NFT to specified address (only owner)", async function () {
      const orderId = 1;
      
      await expect(
        nftContract.connect(owner).mintOrderNFT(user1.address, orderId, IPFS_HASH)
      )
        .to.emit(nftContract, "OrderNFTMinted")
        .withArgs(1, orderId, user1.address, IPFS_HASH);

      // Check token ownership
      expect(await nftContract.ownerOf(1)).to.equal(user1.address);

      // Check token URI/metadata
      const orderNFT = await nftContract.getOrderNFT(1);
      expect(orderNFT.orderId).to.equal(orderId);
      expect(orderNFT.ipfsHash).to.equal(IPFS_HASH);
      expect(orderNFT.mintedAt).to.be.greaterThan(0);

      // Check order to token ID mapping
      const tokenId = await nftContract.getTokenIdByOrder(orderId);
      expect(tokenId).to.equal(1);
    });

    it("Should reject mint from non-owner", async function () {
      await expect(
        nftContract.connect(user1).mintOrderNFT(user2.address, 1, IPFS_HASH)
      ).to.be.revertedWithCustomError(nftContract, "OwnableUnauthorizedAccount");
    });

    it("Should increment token ID for each mint", async function () {
      await nftContract.connect(owner).mintOrderNFT(user1.address, 1, IPFS_HASH);
      await nftContract.connect(owner).mintOrderNFT(user2.address, 2, IPFS_HASH_2);

      expect(await nftContract.ownerOf(1)).to.equal(user1.address);
      expect(await nftContract.ownerOf(2)).to.equal(user2.address);

      const orderNFT1 = await nftContract.getOrderNFT(1);
      const orderNFT2 = await nftContract.getOrderNFT(2);

      expect(orderNFT1.orderId).to.equal(1);
      expect(orderNFT2.orderId).to.equal(2);
    });

    it("Should reject minting duplicate NFT for same orderId", async function () {
      const orderId = 1;
      
      await nftContract.connect(owner).mintOrderNFT(user1.address, orderId, IPFS_HASH);

      await expect(
        nftContract.connect(owner).mintOrderNFT(user2.address, orderId, IPFS_HASH_2)
      ).to.be.revertedWith("NFT already minted for this order");
    });

    it("Should mint NFT to owner address", async function () {
      const orderId = 1;
      
      await nftContract.connect(owner).mintOrderNFT(owner.address, orderId, IPFS_HASH);
      
      expect(await nftContract.ownerOf(1)).to.equal(owner.address);
    });

    it("Should mint NFT with empty IPFS hash", async function () {
      const orderId = 1;
      
      await nftContract.connect(owner).mintOrderNFT(user1.address, orderId, "");
      
      const orderNFT = await nftContract.getOrderNFT(1);
      expect(orderNFT.ipfsHash).to.equal("");
    });

    it("Should mint NFT with very long IPFS hash", async function () {
      const orderId = 1;
      const longHash = "Qm" + "a".repeat(100);
      
      await nftContract.connect(owner).mintOrderNFT(user1.address, orderId, longHash);
      
      const orderNFT = await nftContract.getOrderNFT(1);
      expect(orderNFT.ipfsHash).to.equal(longHash);
    });

    it("Should set correct timestamp on mint", async function () {
      const orderId = 1;
      const blockBefore = await ethers.provider.getBlock("latest");
      
      const tx = await nftContract.connect(owner).mintOrderNFT(user1.address, orderId, IPFS_HASH);
      const receipt = await tx.wait();
      const blockAfter = await ethers.provider.getBlock(receipt.blockNumber);
      
      const orderNFT = await nftContract.getOrderNFT(1);
      
      expect(orderNFT.mintedAt).to.be.greaterThanOrEqual(blockBefore.timestamp);
      expect(orderNFT.mintedAt).to.be.lessThanOrEqual(blockAfter.timestamp);
    });
  });

  describe("Order NFT Information", function () {
    beforeEach(async function () {
      await nftContract.connect(owner).mintOrderNFT(user1.address, 1, IPFS_HASH);
      await nftContract.connect(owner).mintOrderNFT(user2.address, 2, IPFS_HASH_2);
    });

    it("Should get order NFT information", async function () {
      const orderNFT = await nftContract.getOrderNFT(1);
      
      expect(orderNFT.orderId).to.equal(1);
      expect(orderNFT.ipfsHash).to.equal(IPFS_HASH);
      expect(orderNFT.mintedAt).to.be.greaterThan(0);
    });

    it("Should get token ID by order ID", async function () {
      const tokenId1 = await nftContract.getTokenIdByOrder(1);
      const tokenId2 = await nftContract.getTokenIdByOrder(2);
      
      expect(tokenId1).to.equal(1);
      expect(tokenId2).to.equal(2);
    });

    it("Should return 0 for non-existent order", async function () {
      const tokenId = await nftContract.getTokenIdByOrder(999);
      expect(tokenId).to.equal(0);
    });

    it("Should revert when getting non-existent NFT", async function () {
      await expect(
        nftContract.getOrderNFT(999)
      ).to.not.be.reverted; // This will return empty struct, not revert
      
      // But ownerOf will revert
      await expect(
        nftContract.ownerOf(999)
      ).to.be.revertedWithCustomError(nftContract, "ERC721NonexistentToken");
    });
  });

  describe("ERC721 Standard Functions", function () {
    beforeEach(async function () {
      await nftContract.connect(owner).mintOrderNFT(user1.address, 1, IPFS_HASH);
    });

    it("Should return correct balance", async function () {
      expect(await nftContract.balanceOf(user1.address)).to.equal(1);
      expect(await nftContract.balanceOf(user2.address)).to.equal(0);
    });

    it("Should return correct owner", async function () {
      expect(await nftContract.ownerOf(1)).to.equal(user1.address);
    });

    it("Should approve token transfer", async function () {
      await nftContract.connect(user1).approve(user2.address, 1);
      
      const approved = await nftContract.getApproved(1);
      expect(approved).to.equal(user2.address);
    });

    it("Should transfer NFT", async function () {
      await nftContract.connect(user1).transferFrom(user1.address, user2.address, 1);
      
      expect(await nftContract.ownerOf(1)).to.equal(user2.address);
      expect(await nftContract.balanceOf(user1.address)).to.equal(0);
      expect(await nftContract.balanceOf(user2.address)).to.equal(1);
    });

    it("Should transfer NFT after approval", async function () {
      await nftContract.connect(user1).approve(user2.address, 1);
      await nftContract.connect(user2).transferFrom(user1.address, user2.address, 1);
      
      expect(await nftContract.ownerOf(1)).to.equal(user2.address);
    });

    it("Should reject transfer from non-owner", async function () {
      await expect(
        nftContract.connect(user2).transferFrom(user1.address, user2.address, 1)
      ).to.be.revertedWithCustomError(nftContract, "ERC721InsufficientApproval");
    });

    it("Should support safeTransferFrom", async function () {
      await nftContract
        .connect(user1)
        .safeTransferFrom(user1.address, user2.address, 1);
      
      expect(await nftContract.ownerOf(1)).to.equal(user2.address);
    });
  });

  describe("Multiple Orders and NFTs", function () {
    it("Should mint multiple NFTs for different orders", async function () {
      await nftContract.connect(owner).mintOrderNFT(user1.address, 1, IPFS_HASH);
      await nftContract.connect(owner).mintOrderNFT(user1.address, 2, IPFS_HASH_2);
      await nftContract.connect(owner).mintOrderNFT(user2.address, 3, IPFS_HASH);

      expect(await nftContract.balanceOf(user1.address)).to.equal(2);
      expect(await nftContract.balanceOf(user2.address)).to.equal(1);

      expect(await nftContract.getTokenIdByOrder(1)).to.equal(1);
      expect(await nftContract.getTokenIdByOrder(2)).to.equal(2);
      expect(await nftContract.getTokenIdByOrder(3)).to.equal(3);
    });

    it("Should handle large order IDs", async function () {
      const largeOrderId = 999999;
      
      await nftContract.connect(owner).mintOrderNFT(user1.address, largeOrderId, IPFS_HASH);
      
      const orderNFT = await nftContract.getOrderNFT(1);
      expect(orderNFT.orderId).to.equal(largeOrderId);
      
      const tokenId = await nftContract.getTokenIdByOrder(largeOrderId);
      expect(tokenId).to.equal(1);
    });
  });

  describe("Edge Cases", function () {
    it("Should handle minting with order ID 0", async function () {
      await nftContract.connect(owner).mintOrderNFT(user1.address, 0, IPFS_HASH);
      
      const orderNFT = await nftContract.getOrderNFT(1);
      expect(orderNFT.orderId).to.equal(0);
      
      const tokenId = await nftContract.getTokenIdByOrder(0);
      expect(tokenId).to.equal(1);
    });

    it("Should handle minting with maximum uint256 order ID", async function () {
      const maxOrderId = ethers.MaxUint256;
      
      await nftContract.connect(owner).mintOrderNFT(user1.address, maxOrderId, IPFS_HASH);
      
      const orderNFT = await nftContract.getOrderNFT(1);
      expect(orderNFT.orderId).to.equal(maxOrderId);
    });
  });
});

