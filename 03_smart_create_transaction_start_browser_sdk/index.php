<?php

include "lib/init.php";
include "header.php";

// presets
if (CLIENT_ID && !$_POST['client']) {
    $client_id = CLIENT_ID;
} else {
    $client_id = $_POST['client'];
}

if (CLIENT_SECRET && !$_POST['secret']) {
    $client_secret = CLIENT_SECRET;
} else {
    $client_secret = $_POST['secret'];
}

if (VENDOR && !$_POST['vendor']) {
    $vendor = VENDOR;
} else {
    $vendor = $_POST['vendor'];
}

if (DEVICE_UID && !$_POST['uid']) {
    $uid = DEVICE_UID;
} else {
    $uid = $_POST['uid'];
}

$refresh_token = $_POST['refresh_token'];


$config = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'auth' => array('type' => 'none')
);


// Dummy Log File
$fp = fopen("/tmp/secucard_customer_test.log", "a");
$logger = new secucard\client\log\Logger($fp, true);

// Create client without auhtorization
$secucard = new secucard\Client($config, $logger, new secucard\client\storage\FileStorage('/tmp/secucard_client_conf.json'));

?>

<h2>Device Authorisation</h2>

<form action="" method="POST">

    <div class="row" id="deviceVerification">


        <div class="col-md-6">

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <span>Device verification</span>
                    </div>

                    <div class="panel-body">

                        <div class="form-group">
                            <label for="clientInput">Client-ID</label>
                            <input type="text" id=clientInput" name="client" value="<?php echo $client_id ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="secretInput">Client-Secret</label>
                            <input type="text" id=secretInput" name="secret" value="<?php echo $client_secret; ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="amountInput">Vendor</label>
                            <input type="text" id=vendortInput" name="vendor" value="<?php echo $vendor; ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="uidInput">UID (name/value)</label>
                            <input type="text" id="uidfInput" name="uid" value="<?php echo $uid; ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="refreshInput">Refresh token (optional to skip auth)</label>
                            <input type="text" id="refreshInput" name="refresh_token" value="<?php echo $refresh_token; ?>" class="form-control">
                        </div>

                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="action-start btn btn-success" name="startBtn">Start Auth</button>

                        <?php
                        if ($refresh_token):
                            $transaction_link = "transaction_create.php?client_id=$client_id&client_secret=$client_secret&refresh_token=$refresh_token";
                        ?>
                            <a class="btn btn-warning" role="button" href="<?php echo $transaction_link; ?>">Continue without auth</a>
                        <?php endif; ?>

                        </div>
                </div>

        </div>


        <div class="col-md-6">

        <?php

        // First step
        if (!empty($_POST) && (isset($_POST['startBtn']) || isset($_POST['pollBtn']))):


            if (isset($_POST['startBtn'])) {
                $device_verification = $secucard->obtainDeviceVerification("vendor/" . $vendor, $uid);
                $error = $device_verification['error_description'];
                $device_code = $device_verification['device_code'];
                $user_code = $device_verification['user_code'];
                $verification_url = $device_verification['verification_url'];
                $interval = $device_verification['interval'];
            } else {
                $device_code = $_POST['device_code'];
                $user_code = $_POST['user_code'];
                $verification_url = $_POST['verification_url'];
                $interval = $_POST['interval'];
                $error = false;
            }

            if ($error):
        ?>

        <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>

        <?php

            else:

        ?>


            <div class="panel panel-default">

                <div class="panel-heading">
                    <span>Device Polling</span>
                </div>

                <div class="panel-body">

                    <h2>PIN: <?php echo $user_code; ?></h2>
                    <p>Device-Code: <?php echo $device_code; ?></p>
                    <p>Interval: <?php echo $interval; ?></p>
                    <p>Verification Url: <a href="<?php echo $verification_url; ?>"><?php echo $verification_url; ?></a></p>

                    <input type="hidden" name="device_code" value="<?php echo $device_code; ?>">
                    <input type="hidden" name="user_code" value="<?php echo $user_code; ?>">
                    <input type="hidden" name="verification_url" value="<?php echo $verification_url; ?>">
                    <input type="hidden" name="interval" value="<?php echo $interval; ?>">


                </div>

                <div class="panel-footer">
                    <button type="submit" class="action-start btn btn-success" name="pollBtn">Poll Auth</button>
                </div>
            </div>


        <?php

            endif;

        endif;


        // Second step
        if (!empty($_POST) && isset($_POST['pollBtn'])):

            $token = $secucard->pollDeviceAccessToken($device_code);

            if (!empty($token['refresh_token'])) {
                $error = false;
                $transaction_link = "transaction_create.php?client_id=$client_id&client_secret=$client_secret&refresh_token={$token['refresh_token']}&access_token={$token['access_token']}";
            } elseif ($token['error'] !== 'authorization_pending') {
                $error = 'Getting refresh token failed, error: ' . $token['error_description'];
            } else {
                $error = "Pending, please retry Polling";
            }


            if ($error):

        ?>

            <div class="alert alert-warning" role="alert"><?php echo $error; ?></div>

            <?php else: ?>

                <div class="panel panel-success">

                    <div class="panel-heading">
                        <span>Device Polling Successfull!</span>
                    </div>

                    <div class="panel-body">
                        <?php
                        Kint::dump($token);
                        ?>
                    </div>

                    <div class="panel-footer">
                        <a href="<?php echo $transaction_link; ?>" class="btn btn-success" role="button">Continue with these credentials</a>
                    </div>
                </div>

            <?php endif; ?>

        <?php endif; ?>

        </div >
    </div>


</form>


<?php include "footer.php"; ?>