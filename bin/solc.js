var fs = require('fs');
var path = require('path');
var solc = require('solc');


var contract = fs.readFileSync(path.resolve('contracts/Farm.sol'))

var input = {
  language: 'Solidity',
  sources: {
    'Farm.sol': {
      content: contract.toString()
    }
  },
  settings: {
    outputSelection: {
      '*': {
        '*': ['*']
      }
    }
  }
};

var output = JSON.parse(solc.compile(JSON.stringify(input)));

var result = {
  abi: output.contracts['Farm.sol']['Farm'].abi,
  bytecode: output.contracts['Farm.sol']['Farm'].evm.bytecode.object,
}

fs.writeFileSync(path.resolve('contracts/Farm.json'), JSON.stringify(result))

// `output` here contains the JSON output as specified in the documentation
// for (var contractName in output.contracts['Farm.sol']) {
//   console.log(
//     contractName +
//     ': ' +
//     output.contracts['Farm.sol'][contractName].evm.bytecode.object
//   );
// }
