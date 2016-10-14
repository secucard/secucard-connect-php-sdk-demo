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

Created Container with id: PCT_3PQDC8BX82M00Y8KX75XUMGS6W8XAR
Container data: SecucardConnect\Product\Payment\Model\Container Object
(
    [customer] => SecucardConnect\Product\Payment\Model\Customer Object
        (
            [created] =>
            [updated] =>
            [contract] =>
            [contact] =>
            [merchant] =>
            [id] => PCU_M0PSEHCWK2M00Y8KX75XUMGS6W8XAQ
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

    [assign] =>
    [type] =>
    [created] => DateTime Object
        (
            [date] => 2016-10-14 11:49:29.000000
            [timezone_type] => 1
            [timezone] => +02:00
        )

    [updated] =>
    [contract] => SecucardConnect\Product\Payment\Model\Contract Object
        (
            [created] =>
            [updated] =>
            [parent] =>
            [merchant] =>
            [allow_cloning] =>
            [sepa_mandate_inform] =>
            [id] => PCR_W6AV7JJUJ2YS6WHFR5GQGS99ABZDAP
            [object] => payment.contracts
        )

    [id] => PCT_3PQDC8BX82M00Y8KX75XUMGS6W8XAR
    [object] => payment.containers
)

 */