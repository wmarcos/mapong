<?php
require_once 'Google/autoload.php';

session_start();

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/choose.php';
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
} else {
	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
