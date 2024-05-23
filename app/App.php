<?php

declare(strict_types = 1);

// We get transactions from given directory Path and set them on array.
function getTransactionFiles(string $dirPath):array{
    $files=[];
    foreach (scandir($dirPath) as $file){
      if(is_dir($file)){
          continue;
      }
      $files[]= $dirPath . $file;
    }
    return  $files;
}

//Here we get transactions from given file name processing the data flexible(handlers) and return it as an Array.
function getTransactions(string $fileName, ?callable $transactionHandler = null):array{
    //Checking if file exist or not.
    if(! file_exists($fileName)){
        trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR);
    }
    //file opens here in read mode.
    $file = fopen($fileName,'r');
    //first line of file is not transaction so we get rid of it.
    fgetcsv($file);
    $transactions = [];

    while (($transaction = fgetcsv($file))!==false){
        //here we extract transactions with given order with handler(order can be different function).
        if($transactionHandler!==null){
            //data will be passed to handler to correctly order it.
            //in our case its extractTransaction function
            $transaction = $transactionHandler($transaction);
        }
        $transactions[] = $transaction;
    }

    return $transactions;
}
function extractTransaction(array $transactionRow):array{
    //Array destruction here to get each row accordingly.
    [$date,$checkNumber,$description,$amount] = $transactionRow;

    // getting rid of special characters in amount section to be able to calculate.
    $amount = (float) str_replace(['$',','],'',$amount);

    return [
        'date'=>$date,
        'checkNumber'=>$checkNumber,
        'description'=>$description,
        'amount'=>$amount
    ];
}

//In This Function we will accept transactions and process total income , total expense and total net accordingly.
function calculateTotals(array $transactions):array{
    //we create array with keys as below and set them to 0.
    $totals = ['netTotal'=>0,'totalIncome'=>0,'totalExpense'=>0];
    //we loop once to get all transaction and add amount to netTotal either its - or +
    foreach ($transactions as $transaction){
        $totals['netTotal'] += $transaction['amount'];
        //checking transaction if its higher or lower than 0 to add amount accordingly.
        if($transaction['amount']>=0){
            $totals['totalIncome'] += $transaction['amount'];
        }else{
            $totals['totalExpense'] += $transaction['amount'];
        }
    }
    return $totals;
}