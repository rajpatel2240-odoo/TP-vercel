<?php

include 'config.php';
header("Cache-Control: max-age=20, public");

$id = $_GET['id'] ?? exit;
$api = "https://cdn.babel-in.xyz/tpck/index.php?id=$id";
$userAgent = 'Babel-IN'; // u can change if u wanna

$json = fetchMPDManifest($api, $userAgent);
$data = json_decode($json, true);
$keys = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

header('Content-Type: application/json');
echo $keys;
