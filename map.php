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

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mapong - Map your data</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@0.7.7/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@0.7.7/dist/leaflet.js"></script>
<script   src="http://code.jquery.com/jquery-3.1.0.min.js"   integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
<script src="mapong.js"></script>
<link rel="stylesheet" href="mapong.css" />
<script>
SPREADSHEETID = '<?php echo $_GET['spreadsheetid']; ?>';
RANGE = '<?php echo $_GET['range']; ?>';
GPS = '<?php echo $_GET['gps']; ?>';
LABEL = '<?php echo $_GET['label']; ?>';
</script>
</head>
<body onload='init()' >
<a href='choose.php'>Choose another spreadsheet</a>
<div id='map'></div>
</body>
</html>
