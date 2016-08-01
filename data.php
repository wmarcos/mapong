<?php
require_once 'Google/autoload.php';

session_start();

if (!isset($_SESSION['access_token'])) {
	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'];
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

$client = new Google_Client();
$client->setAuthConfigFile( __DIR__ . '/client_secrets.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->addScope(array( Google_Service_Sheets::SPREADSHEETS_READONLY, Google_Service_Drive::DRIVE_METADATA_READONLY));
$client->setAccessToken($_SESSION['access_token']);

if($client->isAccessTokenExpired()) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
}

$service = new Google_Service_Sheets($client);

header('Content-Type: application/json');

$spreadsheetId = $_GET['spreadsheetid'];
$range = $_GET['range'];

$response = $service->spreadsheets_values->get($spreadsheetId,$range);
$values = $response->getValues();
echo json_encode($values);
?>
