<?php 

require __DIR__ . '/globals.php';

if(!isset($_GET['code'])) : ?>
	<a href="https://<?= $_SERVER['JIRA_INSTANCE']; ?>.atlassian.net/plugins/servlet/ac/io.tempo.jira/oauth-authorize/?client_id=<?= $_SERVER['TEMPO_CLIENT_ID']; ?>&redirect_uri=<?= $_SERVER['TEMPO_REDIRECT']; ?>&access_type=tenant_user">Login to tempo</a>
<?php else: 
	// GIFLENS-https://media2.giphy.com/media/iPONcpDn3Rdss/200.gif
	$handle = curl_init();
  curl_setopt($handle, CURLOPT_URL,"https://api.tempo.io/oauth/token/?grant_type=authorization_code&client_id=".$_SERVER['TEMPO_CLIENT_ID']."&client_secret=".$_SERVER['TEMPO_CLIENT_SECRET']."&redirect_uri=".$_SERVER['TEMPO_REDIRECT']."&code=".$_GET['code']);
	curl_setopt($handle, CURLOPT_POST, 1);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($handle);
		
	
	// Save the token to a file.
	if (!file_exists(dirname($_SERVER['tokenPath']))) {
			mkdir(dirname($_SERVER['tokenPath']), 0700, true);
	}
	file_put_contents($_SERVER['tokenPath'], $response);
endif; ?>