<?php
require_once "PublishMessage.php";


$endPoint = '';
$topicName = '';
$ak = '';
$sk = '';
$client = new PublishMessage($ak, $sk, $endPoint, $topicName);
$sign = "";
$templateId = "";
$res = $client->send($sign, $templateId, "", []);
var_dump($res);
