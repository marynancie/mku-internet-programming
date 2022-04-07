<?php
if (!isset($_POST['target'])) die();
$target = $_POST['target'];


require_once __DIR__ . '/../model/Response.php';
require_once __DIR__ . '/../controller/functions.php';
$res = new Response();

if ($target = 'getShippingCosts' && isset($_POST['longitude'], $_POST['latitude'])) {
    generalResponse("ok", 200, ['amount'=> random_int(0, 1000)]);
} else {
    //request matches no rule
    sleep(2);
    generalResponse('Bad Request', 400, ['Your Request' => $_POST]);
}
