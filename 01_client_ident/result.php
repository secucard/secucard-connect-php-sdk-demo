<?php

include "lib/init.php";


$data = $secucard->services->identresults->getList(array());

echo 'Count of results returned: ' . $data->count();