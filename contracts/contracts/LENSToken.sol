// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/token/ERC20/ERC20.sol";
import "@openzeppelin/contracts/access/Ownable.sol";

contract LENSToken is ERC20, Ownable {
    constructor(address initialOwner) ERC20("LensArt Token", "LENS") Ownable(initialOwner) {
        // Mint 1,000,000 tokens cho owner
        _mint(initialOwner, 1000000 * 10**decimals());
    }

    // Hàm mint thêm token (chỉ owner)
    function mint(address to, uint256 amount) public onlyOwner {
        _mint(to, amount);
    }

    // Hàm burn token
    function burn(uint256 amount) public {
        _burn(msg.sender, amount);
    }
}