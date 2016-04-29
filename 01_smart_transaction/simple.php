<?php

use SecucardConnect\Auth\RefreshTokenCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\Product\Smart\Model\Basket;
use SecucardConnect\Product\Smart\Model\BasketInfo;
use SecucardConnect\Product\Smart\Model\Ident;
use SecucardConnect\Product\Smart\Model\Product;
use SecucardConnect\Product\Smart\Model\ProductGroup;
use SecucardConnect\Product\Smart\Model\Transaction;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

require_once __DIR__ . '/../shared/init.php';

$config = array(
    'base_url' => 'https://connect.secucard.com',
    'debug' => true
);

// This just the internal logger impl. for demo purposes! For production you may use a library like Monolog.
$logger = new Logger(fopen("php://stdout", "a"), true);

// Use DummyStorage for demo purposes only, in production use FileStorage or your own implementation.
$store = new DummyStorage();

// smart product uses device auth so either provide valid refresh token here or obtain token by processing
// the auth flow, see \SecucardConnect\Auth\DeviceAuthTest
$cred = new RefreshTokenCredentials('your-client-id', 'your-client-secret', 'your-refresh-token');

$client = new SecucardConnect($config, $logger, $store, $store, $cred);

// You may obtain a global list of allowed "idents templates" to cross check if current customers ident
// is valid at all, this "manual" pre-validation avoids errors when actually submitting transactions later.
$allowedIdents = $client->smart->idents->getList();
if ($allowedIdents === null) {
    throw new Exception("No idents found."); // Should not happen.
}

// Select an ident (card) which will be charged for the basket.
// Usually the value is the id of a scanned card or the id of a Checkin object taken from the global Check-In list.
$ident = new Ident();
$ident->value = "my-ident-id";

// Now you can proceed in two ways:
// - creating a "empty" transaction first and adding products afterwards by updating this transaction step by step
// - adding products to the basket first and creating a new transactions afterwards with the complete basket.
// The second approach may be faster but you get product errors late and all at once while the first approach shows
// possible errors immediately after each update.


// We show the first way: create a empty product basket and the basket summary and create a new transaction first.
$service = $client->smart->transactions;
$newTrans = new Transaction();
$newTrans->idents = [$ident];

/**
 * @var Transaction
 */
$trans = $service->save($newTrans);
if ($trans->status !== Transaction::STATUS_CREATED) {
    throw new Exception();
}

$basket = new Basket();
$basketInfo = new BasketInfo(0, "EUR");
$trans->basket = $basket;
$trans->basket_info = $basketInfo;


// Add products to the basket and update.
$productGroup = new ProductGroup("group1", "beverages", 1);
$basket->products = [new Product(1, null, "123", "5449000017888", "desc1", 1, 500, 1900, [$productGroup])];
$basketInfo->sum = 500;
$result = $service->save($trans);

// Add other product again and update.
$basket->products[] = new Product(2, null, "456", "1060215249800", "desc2", 1, 1000, 1900, [$productGroup]);
$basketInfo->sum = 1500;
$result = $service->save($trans);

// demo|auto|cash, demo instructs the server to simulate a different (random) transaction for each invocation of
// startTransaction, also different formatted receipt lines will be returned.
$type = "demo";
$trans = $service->start($trans->id, $type);
if ($trans->status !== Transaction::STATUS_OK) {
    throw new Exception();
}

// If staus == OK, everything worked..
echo 'Transaction started!';

//Now you can directly  process the  receipt:
$receiptLines = $trans->receipt;
foreach ($receiptLines as $line) {
    echo 'Receipt Line: ' . $line->type . ', ' . $line->value;
}

// Or alternatively you can skip the result from start()
// and retrieve the started transaction later and process the receipt then like:
$trans = $service->get($trans->id);
$receiptLines = $trans->receipt;


// You may also cancel a transaction:
$ok = $service->cancel($trans->id);
$trans = $service->get($trans->id); // Status has now changed.
if ($trans->status !== Transaction::STATUS_CANCELED) {
    throw new Exception();
}