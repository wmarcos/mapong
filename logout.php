<?php
require_once 'google-api-php-client/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfigFile( __DIR__ . '/client_secrets.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->addScope(array( Google_Service_Sheets::SPREADSHEETS_READONLY, Google_Service_Drive::DRIVE_METADATA_READONLY));
$client->setAccessToken($_SESSION['access_token']);

$client->revokeToken();

session_destroy();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'];
header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));

?>
