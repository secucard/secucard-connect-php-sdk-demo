<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Payment\Model\CloneParams;
use SecucardConnect\Product\Payment\Model\Data;

/**
 * @var \SecucardConnect\Product\Payment\ContractsService $service
 */
$service = $secucard2->payment->contracts;

$merchant = new \SecucardConnect\Product\Payment\Model\CreateSubContractRequest();
$merchant->project = 'Project-Name #' . time(); // must be unique
$merchant->payin_account = true;

$merchant->contact = new \SecucardConnect\Product\Common\Model\Contact();
$merchant->contact->gender = 'm';
$merchant->contact->salutation = 'Mr.';
$merchant->contact->title = 'Dr.';
$merchant->contact->forename = 'John';
$merchant->contact->surname = 'Doe';
$merchant->contact->companyname = 'Example Inc.';
$merchant->contact->nationality = 'DE';
$merchant->contact->dob = '1901-02-03';
$merchant->contact->phone = '0049123456789';
$merchant->contact->email = 'mail@example.com';
$merchant->contact->address = new \SecucardConnect\Product\Common\Model\Address();
$merchant->contact->address->street = 'Example Street';
$merchant->contact->address->street_number = '6a';
$merchant->contact->address->postal_code = '01234';
$merchant->contact->address->city = 'Examplecity';
$merchant->contact->address->country = 'DE';
$merchant->contact->birthplace = 'Examplecity';
$merchant->contact->url_website = 'example.com';

$merchant->payout_account = new \SecucardConnect\Product\Payment\Model\Data();
$merchant->payout_account->iban = 'DE37503240001000000524';
$merchant->payout_account->owner = 'Joe Black';

$merchant->iframe_opts = new \SecucardConnect\Product\Payment\Model\IframeOptData();
$merchant->iframe_opts->show_basket = true;
$merchant->iframe_opts->basket_title = 'Projext XY unterstützen';
$merchant->iframe_opts->submit_button_title = 'Zahlungspflichtig unterstützen';

try {
    /**
     * @var \SecucardConnect\Product\Payment\Model\CreateSubContractResponse $merchant_ids
     */
    $merchant_ids = $service->createSubContract($merchant);
    echo 'New cloned contract data: ' . "\n";
    print_r($merchant_ids);
} catch (Exception $e) {
    echo 'Cloning contract failed, error message: ' . $e->getMessage() . "\n";
}