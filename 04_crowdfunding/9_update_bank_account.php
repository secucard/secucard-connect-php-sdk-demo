<?php
echo chr(10) . chr(10) . '####### ' . __FILE__ . ' #######' . chr(10) . chr(10);

/**
 * @var \SecucardConnect\Product\General\ContractsService $service
 */
$service = $secucard->general->contracts;

try {
    $successful = $service->updateBankAccount(
        $contract->id, // id from "1_create_new_project.php", f.e. "GCR_2BA2J80H5DCGAGUUTTKTECQ9W402OH"
        'Max Mustermann',
        'DE62100208900001317270',
        'HYVEDEMM488'
    );

    if ($successful) {
        echo "Bank account update was initiated successfully.\n";
    }
} catch (Throwable $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
    /*
     * Error message:
     *  API Error:
     *      type=ProductFormatException,
     *      code=0,
     *      details="Action not allowed for this contract",
     *      user-message="Es ist ein unbekannter Fehler aufgetreten",
     *      support-id=636ce59ed442b8d5b08b3573a4c1c092
     */
}
