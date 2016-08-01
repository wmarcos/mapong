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

$drive_service = new Google_Service_Drive($client);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mapong - Search your Google spreadsheets</title>
</head>

<body>
<h2>Search:</h2>
<form method='get'>
<input type='text' name='q' value='' />
<input type='submit' />
<form/>

<?php



if(isset($_GET['q'])) {
	$optParamsQuery = array(
	  'pageSize' => 10,
	  'fields' => "nextPageToken, files(id, name)",
		'q' => "mimeType='application/vnd.google-apps.spreadsheet' and name contains '".$_GET['q']."' "
	);
	$files_list_query = $drive_service->files->listFiles($optParamsQuery);
	echo "<h3>Results:</h3><ul>";
	foreach ($files_list_query->getFiles() as $f ) {
		echo "<li><a href='choose_sheet.php?spreadsheetid=".$f->getId()."' >".$f->getName()."</a></li>";
	}
	echo "</ul>";
	}
	?>

<h3>Recent:</h3>
<ul>
<?php
$optParamsRecent = array(
  'pageSize' => 10,
  'fields' => "nextPageToken, files(id, name)",
	'q' => "mimeType='application/vnd.google-apps.spreadsheet'"
);
$files_list_recent = $drive_service->files->listFiles($optParamsRecent);
foreach ($files_list_recent->getFiles() as $f ) {
	echo "<li><a href='choose_sheet.php?spreadsheetid=".$f->getId()."' >".$f->getName()."</a></li>";
}

?>
</ul>
</body>
</html>
