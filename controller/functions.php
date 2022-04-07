<?php

/**
 * @param string $message
 * @param int $httpResCode
 * @param array $data
 * @param false $success
 * @param array $appData
 */
function generalResponse($message = 'Rejected', $httpResCode = 400, $data = [], $success = false, array $appData = [])
{

    require_once __DIR__ . "/../model/Response.php";
    //reject bad requests after 1s
    if (!$success) sleep(0.5);

    $res = new Response();
    $res->setsuccess($success);
    $res->setHttpStatusCode($httpResCode);
    $res->addMessage($message);
    $res->setData($data);

    $res->send();
    exit;
}
