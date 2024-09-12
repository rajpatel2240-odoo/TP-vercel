<?php

include 'config.php';
header("Cache-Control: max-age=20, public");

$id = $_GET['id'] ?? exit;
$url = "https://cdn.babel-in.xyz/tpck/index.php";
$userAgent = "Babel/5.0";

$channelInfo = getChannelInfo($id);
$dashUrl = $channelInfo['streamData']['initialUrl'] ?? exit;
$manifestContent = fetchMPDManifest($dashUrl, $userAgent) ?? exit;
$baseUrl = dirname($dashUrl);
$widevinePssh = extractPsshFromManifest($manifestContent, $baseUrl, $userAgent, $beginTimestamp);

if (!$widevinePssh) {
    exit("Error: Could not extract PSSH or KID.");
}

$kid = $widevinePssh['kid'];

$postData = json_encode([
    "id" => $id,
    "kids" => [$kid],
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($postData)
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
$response = curl_exec($ch);
curl_close($ch);

echo $response;