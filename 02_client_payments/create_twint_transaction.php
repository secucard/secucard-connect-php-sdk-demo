<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/*
 * NOTE: It is not allowed to list all already created secupay twint transactions, so you should store created ids.
 */

use SecucardConnect\Product\Payment\Model\Transaction;

/**
 * @var \SecucardConnect\Product\Payment\TransactionsService $service
 */
$service = $secucard->payment->transactions;

$twint = new Transaction();
$twint->amount = 100; // Amount in cents (or in the smallest unit of the given currency)
$twint->currency = 'CHF'; // The ISO-4217 code of the currency
$twint->purpose = 'Your purpose from TestShopName';
$twint->customer = $customer;
$twint->payment_methods[] = 'TWINT';

try {
    $payment = $service->save($twint);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
    exit;
}

if ($payment->id) {
    echo 'Created twint transaction with id: ' . $payment->id . "\n";

    echo 'Payment data: ' . print_r($payment, true) . "\n";

    $redirect_url = null;
    if (isset($payment->redirect_url->iframe_url)) {
        $redirect_url = $payment->redirect_url->iframe_url;
        // TODO Add your redirect here.
        echo '$redirect_url: ' . $redirect_url . "\n";
    }

    echo 'TEST GET: ' . $payment->id . ' ----';
    print_r($service->get($payment->id));
    echo '----';


} else {
    echo 'Transaction creation failed' . "\n";
}


/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created twint transaction with id: ihgvgzprvayj4455716
Payment data: SecucardConnect\Product\Payment\Model\Transaction Object
(
    [id] => ihgvgzprvayj4455716
    [object] => payment.transactions
    [contract] =>
    [customer] => SecucardConnect\Product\Payment\Model\Customer Object
        (
            [created] =>
            [updated] =>
            [contract] =>
            [contact] => SecucardConnect\Product\Common\Model\Contact Object
                (
                    [salutation] => Mr.
                    [title] => Dr.
                    [forename] => John
                    [surname] => Doe
                    [name] => John Doe
                    [companyname] => Testfirma
                    [dob] => DateTime Object
                        (
                            [date] => 1971-02-03 00:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [birthplace] => MyBirthplace
                    [nationality] => DE
                    [gender] =>
                    [phone] => 0049123456789
                    [mobile] =>
                    [email] => example@example.com
                    [picture] =>
                    [pictureObject] =>
                    [url_website] =>
                    [address] => SecucardConnect\Product\Common\Model\Address Object
                        (
                            [street] => Example Street
                            [street_number] => 6a
                            [city] => ExampleCity
                            [postal_code] => 01234
                            [country] => DE
                            [additional_address_data] =>
                            [id] =>
                            [object] =>
                        )

                )

            [merchant] =>
            [checkin] =>
            [merchant_customer_id] =>
            [id] => PCU_W4Q0TK0SH2NYY3M5Z5DEY2833XKTAZ
            [object] => payment.customers
        )

    [recipient] =>
    [amount] => 100
    [currency] => CHF
    [purpose] => Your purpose from TestShopName
    [order_id] =>
    [trans_id] => 24160280
    [status] => internal_server_status
    [transaction_status] => 1
    [basket] =>
    [experience] =>
    [accrual] =>
    [subscription] =>
    [redirect_url] => SecucardConnect\Product\Payment\Model\RedirectUrl Object
        (
            [url_success] =>
            [url_failure] =>
            [iframe_url] => https://api-dist.secupay-ag.de/payment/ihgvgzprvayj4455716
            [url_push] =>
        )

    [url_success] =>
    [url_failure] =>
    [iframe_url] => https://api-dist.secupay-ag.de/payment/ihgvgzprvayj4455716
    [opt_data] =>
    [payment_action] =>
    [used_payment_instrument] =>
    [sub_transactions] =>
    [payment_id] => PCI_WJS06ESQXRKYHDXKT838X44ZWC0KN0
    [payment_methods] =>
)

$redirect_url: https://api-dist.secupay-ag.de/payment/ihgvgzprvayj4455716


 */
