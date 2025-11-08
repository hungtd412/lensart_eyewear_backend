// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/token/ERC721/ERC721.sol";
import "@openzeppelin/contracts/access/Ownable.sol";

contract LensArtOrderNFT is ERC721, Ownable {
    uint256 private _tokenIdCounter;

    struct OrderNFT {
        uint256 orderId;
        string ipfsHash;
        uint256 mintedAt;
    }

    mapping(uint256 => OrderNFT) public orderNFTs;
    mapping(uint256 => uint256) public orderToTokenId;

    event OrderNFTMinted(
        uint256 indexed tokenId,
        uint256 indexed orderId,
        address indexed to,
        string ipfsHash
    );

    constructor(address initialOwner) ERC721("LensArt Order NFT", "LENSORDER") Ownable(initialOwner) {}

    // Chỉ owner mới được mint NFT
    function mintOrderNFT(
        address _to,
        uint256 _orderId,
        string memory _ipfsHash
    ) public onlyOwner returns (uint256) {
        require(orderToTokenId[_orderId] == 0, "NFT already minted for this order");

        _tokenIdCounter++;
        uint256 newTokenId = _tokenIdCounter;

        _mint(_to, newTokenId);

        orderNFTs[newTokenId] = OrderNFT({
            orderId: _orderId,
            ipfsHash: _ipfsHash,
            mintedAt: block.timestamp
        });

        orderToTokenId[_orderId] = newTokenId;

        emit OrderNFTMinted(newTokenId, _orderId, _to, _ipfsHash);

        return newTokenId;
    }

    function getOrderNFT(uint256 _tokenId) public view returns (OrderNFT memory) {
        return orderNFTs[_tokenId];
    }

    function getTokenIdByOrder(uint256 _orderId) public view returns (uint256) {
        return orderToTokenId[_orderId];
    }
}