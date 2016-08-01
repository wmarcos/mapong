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
$range = $_GET['range'];

$response = $service->spreadsheets_values->get($spreadsheetId,$range);
$values = $response->getValues();

$cols = $response['values'][0];

echo "<form method='GET' action='map.php' >";
echo "<input type='hidden' name='spreadsheetid' value='".$spreadsheetId."' />";
echo "<input type='hidden' name='range' value='".$range."' />";

echo "<h2>Choose a GPS column</h2><select name='gps'>";
foreach ($cols as $i => $c) {
	echo "<option value='".$i."' >".$c."</option>";
}
echo "</select>";

echo "<h2>Choose a Label column</h2><select name='label'>";
foreach ($cols as $i => $c) {
	echo "<option value='".$i."' >".$c."</option>";
}
echo "</select></br></br><input type='submit' /></form>";


?>
</body>
</html>
