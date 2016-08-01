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
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mapong - Choose a sheet</title>
</head>
<body>

<?php
// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
//$_GET['spreadsheetId'] = "1kt1xlZAXPhYc6g_IHrdOQvGlBmDpjBUge3XDdXQF338";
$spreadsheetId = $_GET['spreadsheetid'];
//$range = "Class Data!A2:E";
$response = $service->spreadsheets->get($spreadsheetId);
echo "<h2>Choose a sheet</h2><ul>";
foreach ($response['modelData']['sheets'] as $s) {
	//print_r($s);
	echo "<li><a href='choose_cols.php?spreadsheetid=".$spreadsheetId."&range=".$s['properties']['title']."' >".$s['properties']['title']."</a></li>";
}
echo "</ul>";
?>
</body>
</html>
