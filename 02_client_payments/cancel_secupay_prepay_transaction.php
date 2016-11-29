<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var \SecucardConnect\Product\Payment\SecupayPrepaysService $service
 */
$service = $secucard->payment->secupayprepays;

try {
	$res = $service->cancel($prepay->id);

	if ($res) {
		echo 'Canceled secupay prepay transaction with id: ' . $prepay->id . "\n";
	} else {
		echo 'Prepay cancellation failed with id:' . $prepay->id . "\n";
	}
} catch (\Exception $e) {
    echo 'Prepay cancellation failed' . "\n";
    echo 'Error message: ' . $e->getMessage() . "\n";
}