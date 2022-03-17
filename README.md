# farmfactory
https://farm.wpmix.net/ - demo
https://www.youtube.com/watch?v=HdM1_XxN3VQ - video instruction
https://t.me/farmsupportbot - Contact our team using if you have any questions 
 
The simplest way to allow your users to deposit ERC20 tokens (from example USDT) using simple "Deposit/Withdraw" interface and earn (farm) rewards. 

How to create farm contract:
1. Go to this interface (or https://kovan.etherscan.io/address/0x867f4a2a230de019370931ef3f21a09504fd87f2#writeContract for kovan tesntet (networkid=42)
2. Connect metamask (click "Connect to web3")
3. Open "Create farm" dialog (https://screenshots.wpmix.net/chrome_v0wRXGUaKS0rwhHfoQKN1eonZqQLxIXv.png see screenshot) and enter this variables:
4. Enter _rewardsToken (address) the same as you entered above
5. Enter _stakingToken (address)
6. Enter _rewardsDuration - duration of staking round in seconds. 86400 - 1 day, 2592000 - 30 day, 31536000 - 1 year
7. Enter _newOwner - your metamask's address. This is admin's address, who can start round.
8. Click "write" and copy paste new contract address to this "Farming address Address" input (https://screenshots.wpmix.net/chrome_alA4vL8kN2zsxTpBpJWtn0a2DFrCJ9xi.png" - where to get the address)


Simplest usage: Your holder to stake X TOKEN_A and get (X+Y) TOKEN_A back in 1 month (you must add  Y TOKEN_A to the contract)

Advanced usage: 
1. Your holders go to uniswap.exchange and create pool TOKEN_A/ETH (or TOKEN_A/USDT)  
2. receive unsiwap's LP token, which needs to withdraw pooled assets back to the wallet (this step is autmatically performs on uniswap)
3. stake this LP token in the staking contract
4. after expiration holder gets  LP token back  and TOKEN_A as a reward (in proportion of their staking value and staking time).

Contact our team using https://t.me/farmsupportbot if you have any questions

Icons made by <a href="https://www.flaticon.com/authors/flat-icons" title="Flat Icons">Flat Icons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a>

