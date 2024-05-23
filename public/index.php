<?php

declare(strict_types = 1);
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);
//we need app.php to have access functions
require APP_PATH . "App.php";
//Here we create a variable (array) to collect each transaction files(in our case its only sample_1.csv
$files = getTransactionFiles(FILES_PATH);

//Array to store data.
$transactions = [];

//we loop through each file from files array and process it
foreach ($files as $file){
    //here we merge all transactions from all files(we have only one currently).
    $transactions = array_merge($transactions , getTransactions($file,'extractTransaction'));
}
//we need view (transactions.php) to show it on display(we pass data) so there we can do client side stuff.
require  VIEWS_PATH . 'transactions.php';


