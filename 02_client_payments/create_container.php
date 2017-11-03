<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Payment\Model\Container;
use SecucardConnect\Product\Payment\Model\Data;

/**
 * @var \SecucardConnect\Product\Payment\ContainersService $service
 */
$service = $secucard->payment->containers;

// You may obtain a global list of available containers
$containers = $service->getList();
if ($containers === null) {
    throw new Exception("No Containers found.");
}


// new container creation:

// create new Data subobject for contrainer
$container_data = new Data();
$container_data->iban = 'DE62100208900001317270';
$container_data->owner = 'Max Mustermann';

// the customer reference for the container is optional, but we strongly recommend it
//$customer = new Customer();
//$customer->object = 'payment.customers';
//$customer->id = '@your-already-created-customer-id';

$container = new Container();
$container->private = $container_data;
$container->customer = $customer;
$logger->debug('object data initialized');

try {
    $container = $service->save($container);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($container->id) {
    echo 'Created Container with id: ' . $container->id . "\n";
    echo 'Container data: ' . print_r($container, true) . "\n";
} else {
    echo 'Container creation failed';
}

/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created Container with id: PCT_WEGJKJ68U2MFSF95X75XUJHX7ASSAR
Container data: SecucardConnect\Product\Payment\Model\Container Object
(
    [customer] => SecucardConnect\Product\Payment\Model\Customer Object
        (
            [created] =>
            [updated] =>
            [contract] =>
            [contact] =>
            [merchant] =>
            [merchant_customer_id] =>
            [id] => PCU_ECZ4DPUPT2MFSF95X75XUJHX7ASSAQ
            [object] => payment.customers
        )

    [public] => SecucardConnect\Product\Payment\Model\Data Object
        (
            [owner] => Max Mustermann
            [iban] => DE62100208900001317270
            [bic] => HYVEDEMM488
            [bankname] => UniCredit Bank - HypoVereinsbank
        )

    [private] => SecucardConnect\Product\Payment\Model\Data Object
        (
            [owner] => Max Mustermann
            [iban] => DE62100208900001317270
            [bic] => HYVEDEMM488
            [bankname] => UniCredit Bank - HypoVereinsbank
        )

    [type] =>
    [created] => DateTime Object
        (
            [date] => 2017-11-03 15:16:43.000000
            [timezone_type] => 1
            [timezone] => +01:00
        )

    [updated] =>
    [contract] => SecucardConnect\Product\Payment\Model\Contract Object
        (
            [created] =>
            [updated] =>
            [parent] =>
            [allow_cloning] =>
            [id] => PCR_2NSCASA2N2MF75F5875XUDD87M8UA6
            [object] => payment.contracts
        )

    [mandate] => SecucardConnect\Product\Payment\Model\Mandate Object
        (
            [iban] => DE62100208900001317270
            [bic] => HYVEDEMM488
            [type] => COR1
            [identification] => PAM/ACSQ42PH82PO4660K
            [status] => 1
            [sepa_mandate_id] => 431814
        )

    [id] => PCT_WEGJKJ68U2MFSF95X75XUJHX7ASSAR
    [object] => payment.containers
)

 */