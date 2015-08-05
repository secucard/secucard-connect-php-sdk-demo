<?php

include "lib/init.php";
include "header.php";

$client_id = $_GET['client_id'];
$client_secret = $_GET['client_secret'];
$refresh_token = $_GET['refresh_token'];
$access_token = $_GET['access_token'];
$amount = $_GET['amount'];
$merchantref = $_GET['merchantref'];
$transactionref = $_GET['transactionref'];

$config = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'auth' => array('type' => 'refresh_token', 'refresh_token' => $refresh_token),
);
// File storage - TODO you will replace it with your storage that implements our StorageInterface
$storage = new secucard\client\storage\FileStorage('/tmp/secucard_client_conf.json');
$storage->set('refresh_token', $refresh_token);

// Dummy Log File
$fp = fopen("/tmp/secucard_php_test.log", "a");
$logger = new secucard\client\log\Logger($fp, true);

$secucard = new secucard\Client($config, $logger, $storage);


?>

<h2>Create and start Smart Transaction</h2>

<form action="" method="GET">
<input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
<input type="hidden" name="client_secret" value="<?php echo $client_secret; ?>">
<input type="hidden" name="refresh_token" value="<?php echo $refresh_token; ?>">
<input type="hidden" name="access_token" value="<?php echo $access_token; ?>">


<div class="row" id="transactionCreate">
    <div class="col-md-6">
        <form action="transaction_create.php" method="GET">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <span>New transaction</span>
                </div>

                <div class="panel-body">

                    <div class="form-group">
                        <label for="amountInput">Sum</label>
                        <div class="input-group">
                            <div class="input-group-addon">&euro;</div>
                            <input type="number" id="amountInput" name="amount" value="<?php echo $_GET['amount']?>" class="form-control">
                            <div class="input-group-addon">in cents</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchantrefInput">Customer-Reference</label>
                        <input type="text" id="merchantrefInput" name="merchantref" value="<?php echo $_GET['merchantref']?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="transactionrefInput">Transaction-Reference</label>
                        <input type="text" id="transactionrefInput" name="transactionref" value="<?php echo $_GET['transactionref']?>" class="form-control">
                    </div>

                </div>

                <div class="panel-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </div>

        </form>
    </div >

    <div class="col-md-6">

    <?php

    if ($amount):
        // creation:
        $transaction_data = array(
            'merchantRef' => $merchantref,
            'transactionRef' => $transactionref,
            'basket_info' => [
                'sum' => (int)$amount,
                'currency' => 'EUR'
            ],
        );

        $transaction = $secucard->factory('Smart\Transactions');
        $transaction->initValues($transaction_data);

        $success = false;
        try {
            $success = $transaction->save();
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            
            echo '<b>Error message: </b> '. $e->getMessage() . '<br/>';
            if ($e->hasResponse()) {
                if ($e->getResponse()->getBody()) {
                    echo '<b>Body: </b>' . print_r($e->getResponse()->getBody()->__toString(), true) . '<br/>';
                }
            }
        } catch (Exception $e) {
            echo 'Error : '. $e->getMessage();
            
        }
        if ($success):
            #echo 'Created Transaction with id: ' . $transacion->id;
            #echo '<br/>Access token : ' . print_r($storage->get('access_token'), true);
    ?>

            <div class="panel panel-success">

                <div class="panel-heading">
                    <span>Transaction create Result</span>
                </div>

                <div class="panel-body">
                    <h4>ID: <?php echo $transaction->id; ?></h4>
                    <?php
                    Kint::dump($transaction);
                    ?>
                </div>

                <div class="panel-footer">
                	
                	<div class="form-group">
	                	<select class="form-control" id="transactionType">
							<option value="demo">demo</option>
						</select>
					</div>
                    <button type="button" data-transaction="<?php echo $transaction->id; ?>" class="action-start btn btn-success has-spinner"><span class="spinner"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i></span> Start <?php echo $transaction->id; ?> via Browser SDK</button>
                </div>
            </div>

    <?php
        endif;
    endif;
    ?>

    </div>
</div>

<div class="row">
	<div id="transaction-result" class="panel panel-default" style="display: none;">

                <div class="panel-heading">
                    <span>Events:</span>
                </div>
                
                <div class="panel-body display-events">
                    
                </div>
                
                <div class="panel-heading">
                    <span>Result:</span>
                </div>
                
                <div class="panel-body display-result">
                    
                </div>
                
            </div>
            <script>
            	demo.init(<?php echo(json_encode($storage->get('access_token'))); ?>);
            </script>
</div>

</form>

<?php
include "footer.php";
?>
