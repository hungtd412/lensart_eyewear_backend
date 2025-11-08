const fs = require("fs");
const path = require("path");

const envFile = path.join(__dirname, ".env");

console.log("=== Enable Tenderly Automatic Verification ===\n");

let envContent = "";
if (fs.existsSync(envFile)) {
  envContent = fs.readFileSync(envFile, "utf8");
}

// Check if TENDERLY_AUTOMATIC_VERIFICATION exists
if (envContent.includes("TENDERLY_AUTOMATIC_VERIFICATION")) {
  envContent = envContent.replace(
    /TENDERLY_AUTOMATIC_VERIFICATION=.*/g,
    "TENDERLY_AUTOMATIC_VERIFICATION=true"
  );
  console.log("✓ Updated TENDERLY_AUTOMATIC_VERIFICATION=true");
} else {
  envContent += "\n# Tenderly Automatic Verification\n";
  envContent += "TENDERLY_AUTOMATIC_VERIFICATION=true\n";
  console.log("✓ Added TENDERLY_AUTOMATIC_VERIFICATION=true");
}

// Check if TENDERLY_ACCESS_TOKEN exists (optional but recommended)
if (!envContent.includes("TENDERLY_ACCESS_TOKEN")) {
  console.log("\n⚠️  TENDERLY_ACCESS_TOKEN not found (optional but recommended)");
  console.log("   Get your token from: https://dashboard.tenderly.co/settings/account/authorization");
  console.log("   Then add to .env: TENDERLY_ACCESS_TOKEN=your_token");
}

fs.writeFileSync(envFile, envContent);
console.log("\n✓ Automatic verification enabled!");
console.log("   Run: npm run deploy:tenderly");


