import json from '../../contracts/Farm.json'
import formatAmount from './formatAmount'
import { injectModalsRoot } from './modals'
import infoModal from './infoModal'
import connectModal from './connectModal'
import { accountUnlockedStorageKey } from './constants'
import { getState, setState } from './state'
import setupWeb3 from './setupWeb3'


const rewardsAbi = [{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"spender","type":"address"},{"name":"value","type":"uint256"}],"name":"approve","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"from","type":"address"},{"name":"to","type":"address"},{"name":"value","type":"uint256"}],"name":"transferFrom","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_spender","type":"address"},{"name":"_value","type":"uint256"},{"name":"_extraData","type":"string"}],"name":"approveAndCall","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"spender","type":"address"},{"name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"to","type":"address"},{"name":"value","type":"uint256"}],"name":"mint","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"value","type":"uint256"}],"name":"burn","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"from","type":"address"}],"name":"getAvailableBalance","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"tokensMinted","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"owner","type":"address"}],"name":"balanceOf","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"from","type":"address"},{"name":"value","type":"uint256"}],"name":"burnFrom","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"account","type":"address"}],"name":"addMinter","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[],"name":"renounceMinter","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"spender","type":"address"},{"name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"to","type":"address"},{"name":"value","type":"uint256"}],"name":"transfer","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"account","type":"address"}],"name":"isMinter","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"owner","type":"address"}],"name":"freezeFor","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"owner","type":"address"}],"name":"freezeOf","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_to","type":"address"},{"name":"_value","type":"uint256"},{"name":"_unfreezeTimestamp","type":"uint256"},{"name":"_subsequentUnlock","type":"bool"}],"name":"mintWithFreeze","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"maxSupply","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"owner","type":"address"},{"name":"spender","type":"address"}],"name":"allowance","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"name":"account","type":"address"}],"name":"MinterAdded","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"account","type":"address"}],"name":"MinterRemoved","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"from","type":"address"},{"indexed":true,"name":"to","type":"address"},{"indexed":false,"name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"owner","type":"address"},{"indexed":true,"name":"spender","type":"address"},{"indexed":false,"name":"value","type":"uint256"}],"name":"Approval","type":"event"}]

const loadScript = (src) => new Promise((resolve, reject) => {
  const script = document.createElement('script')

  script.onload = resolve
  script.onerror = reject
  script.src = src

  document.head.appendChild(script)
})

type Params = {
  rewardsAddress: string
  stakingAddress: string
  duration: number
  withdrawLockPeriod: number,
  decimal: number
  onTrx: (trxHash: string) => void
  onSuccess: (address: string) => void
  onError: (error: Error | string) => void
  onFinally: () => void
}

const deploy = async (params: Params) => {
  const { abi, bytecode } = json
  const { rewardsAddress, stakingAddress, duration, withdrawLockPeriod, decimal } = params

  const { web3 } = getState()

  const onTrx = params.onTrx || (() => {})
  const onSuccess = params.onSuccess || (() => {})
  const onError = params.onError || (() => {})
  const onFinally = params.onFinally || (() => {})

  if (!rewardsAddress || !stakingAddress || !duration || !decimal || !withdrawLockPeriod) {
    onError('All fields should be filled: rewardsAddress, stakingAddress, duration, withdrawLockPeriod, decimal.')
    return
  }

  let contract
  let accounts

  try {
    contract = new web3.eth.Contract(abi)
    accounts = await window.ethereum.request({ method: 'eth_accounts' })
  }
  catch (err) {
    onError(err)
    return
  }

  if (!accounts || !accounts[0]) {
    onError('Wallet account is undefined.')
    return
  }

  contract.deploy({
    data: '0x' + bytecode,
    arguments: [ rewardsAddress, stakingAddress, duration, withdrawLockPeriod, decimal ],
  })
    .send({
      from: accounts[0],
      gas: 3000000,
    })
    .on('transactionHash', (hash) => {
      console.log('transaction hash:', hash)
      onTrx(hash)
    })
    .on('error', (error) => {
      console.log('transaction error:', error)
      onError(error)
    })
    .on('receipt', (receipt) => {
      console.log('transaction receipt:', receipt)
      onSuccess(receipt.contractAddress)
    })
    .then(() => {
      onFinally()
    })
}

const startFarming = async ({ farmAddress, rewardsAddress, amount, onSuccess, onError }) => {
  const { abi } = json
  const { web3 } = getState()

  try {
    const farmContract = new web3.eth.Contract(abi, farmAddress)
    const rewardsContract = new web3.eth.Contract(rewardsAbi, rewardsAddress)

    const accounts = await window.ethereum.request({ method: 'eth_accounts' })
    const rewardsDecimals = await rewardsContract.methods.decimals().call()

    const value = formatAmount(amount, rewardsDecimals)

    await farmContract.methods.notifyRewardAmount(value).send({ from: accounts[0] })

    if (typeof onSuccess === 'function') {
      onSuccess()
    }
  }
  catch (err) {
    if (typeof onError === 'function') {
      onError(err)
    }
  }
}

const stopFarming = () => {

}

const handleError = (err) => {
  const { opts } = getState()

  if (typeof opts.onError === 'function') {
    opts.onError(err)
  }
}

const initMetamask = async () => {
  const isAccountUnlocked = localStorage.getItem(accountUnlockedStorageKey) === 'true'

  if (isAccountUnlocked) {
    try {
      const accounts = await window.ethereum.request({ method: 'eth_accounts' })

      if (!accounts[0]) {
        localStorage.removeItem(accountUnlockedStorageKey)
        connectModal.open()
      }
      else {
        setState({ account: accounts[0] })

        await setupWeb3()

        const { opts } = getState()

        if (typeof opts.onFinishLoading === 'function') {
          opts.onFinishLoading()
        }
      }
    }
    catch (err) {
      console.error(err)
      localStorage.removeItem(accountUnlockedStorageKey)
      connectModal.open()
    }
  }
  else {
    connectModal.open()
  }
}

const connectMetamask = async () => {
  if (!window.ethereum) {
    infoModal.open({
      message: `
        <div class="install-metamask">
          <img src="https://swaponline.github.io/images/metamask_45038d.svg" /><br />
          Please install MetaMask
        </div>
      `,
    })

    return Promise.reject()
  }

  return new Promise((resolve) => {
    const interval = setInterval(() => {
      if (window.ethereum.networkVersion) {
        clearInterval(interval)
        initMetamask()
        window.ethereum.on('networkChanged', initMetamask)
        resolve()
      }
    }, 500)
  })
}

const init = async (opts) => {
  setState({ opts })

  if (typeof opts.onStartLoading === 'function') {
    opts.onStartLoading()
  }

  try {
    await Promise.all([
      loadScript('https://cdnjs.cloudflare.com/ajax/libs/web3/1.3.1/web3.min.js'),
      loadScript('https://cdnjs.cloudflare.com/ajax/libs/bignumber.js/8.0.2/bignumber.min.js'),
    ])

    injectModalsRoot()

    await connectMetamask()
  }
  catch (err) {
    handleError(err)
  }
}


export default {
  init,
  deploy,
  startFarming,
  stopFarming,
}
