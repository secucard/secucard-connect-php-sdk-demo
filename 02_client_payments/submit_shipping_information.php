<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var \SecucardConnect\Product\Payment\SecupayInvoicesService $service
 */
$service = $secucard->payment->secupayinvoices;

$paymentId = 'asfzhiosghrq3716518';

// Set the invoice number (optional, but recommended)
$invoiceNumber = "201800218";

// Set the tracking information of the parcel (optional)
$carrier = "DHL";
$trackingId = "00340433836442636597";


try {
    $response = $service->setShippingInformation(
        $paymentId,
        $carrier,
        $trackingId,
        $invoiceNumber
    );

    var_dump($response); // bool(true) -> successful
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}
