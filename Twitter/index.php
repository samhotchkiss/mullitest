<?
/**
 * This is a generaly pretty simple set of code here. This took me maybe a half hour to set up,
 * I then proceeded to tinker for the better part of the evening.  It was the JS-only version
 * that took the better part of a day.  It's not here anymore, I've since cleaned it up, but
 * there was going to be a toggle to select between pulling data from this PHP and through the
 * Streaming API.  That was a bust, and it's gone, so don't ask for it.  :-)
 */


include("lib/Phirehose.php");		// These two are not my libraries.
include("lib/OauthPhirehose.php");	// They could use some slimming...
include("lib/Twatter.php");

session_start();
define(SEP, '/');

// I was hard-codeing the username/passowrd.  This is much more secure...  You know...  Relatively.
if(!isset($_SESSION['u']) || !isset($_SESSION['p'])) {
	if (isset($_POST['u']) && isset($_POST['p'])) {
		$_SESSION['u'] = $_POST['u'];
		$_SESSION['p'] = $_POST['p'];
	} else {
		include('login.html');
		die;
	}
}

$twat = new Twatter($_SESSION['u'], $_SESSION['p']);
$twat->set_template('twats.php');									

$twat->consume(false);									// Consume without reconnect.