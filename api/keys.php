<?php

include 'config.php';
header("Cache-Control: max-age=20, public");

$id = $_GET['id'] ?? exit;
$api = "https://cdn.babel-in.xyz/tpck/index.php?id=$id";
$userAgent = 'Babel-IN'; // u can change if u wanna
$json = fetchMPDManifest($api, $userAgent);

header('Content-Type: application/json');
echo $json;
