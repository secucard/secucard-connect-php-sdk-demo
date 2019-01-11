<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);


/**
 * @var \SecucardConnect\Product\Document\UploadsService $service
 */
$service = $secucard->document->uploads;

try {
    $upload = new \SecucardConnect\Product\Document\Model\Upload();
    $upload->content = base64_encode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'test2.pdf'));

    $upload = $service->save($upload);

    if ($upload) {
        echo 'Response data: ' . print_r($upload, true) . "\n";
    } else {
        echo 'Getting Crowd-Funding-Data failed' . "\n";
        exit;
    }
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

/**
 * @var \SecucardConnect\Product\Services\UploadidentsService $service
 */
$service = $secucard->services->uploadidents;

try {
    $ident_data = new \SecucardConnect\Product\Services\Model\Uploadident();
    $ident_data->payment_id = 'rojwzrxgvjfi3375909';
    $ident_data->documents[] = $upload->id;

    $ident_data = $service->save($ident_data);

    if ($ident_data) {
        echo 'Response data: ' . print_r($ident_data, true) . "\n";
    } else {
        echo 'Getting Crowd-Funding-Data failed' . "\n";
        exit;
    }
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}
