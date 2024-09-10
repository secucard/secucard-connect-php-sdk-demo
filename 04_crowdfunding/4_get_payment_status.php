<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var \SecucardConnect\Product\Payment\SecupayCreditcardsService $service
 */
$service = $secucard->payment->secupaycreditcards;

// You may obtain a global list of available containers
$creditcard = $service->get($payment->id); // f.e. "goqsjsrurucx11124568"

if ($creditcard === null) {
    throw new Exception("No creditcard transaction found.");
}

print_r($creditcard);
