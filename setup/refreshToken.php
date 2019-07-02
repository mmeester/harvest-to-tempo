<?php 

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/globals.php';

if (file_exists($_SERVER['tokenPath'])) {
	$tokens = json_decode(file_get_contents($_SERVER['tokenPath']), true);
  
  $headers = array('Accept' => 'application/json');
  $query = array(
              'grant_type' => 'refresh_token',
              'client_id' => $_SERVER['TEMPO_CLIENT_ID'], 
              'client_secret' => $_SERVER['TEMPO_CLIENT_SECRET'],
              'redirect_uri' => $_SERVER['TEMPO_REDIRECT'],
              'refresh_token' => $tokens['refresh_token'] );
  $response = Unirest\Request::post( $_SERVER['tempoBase'] . '/oauth/token/', $headers, $query);
	
	// Save the token to a file.
	if (!file_exists(dirname($_SERVER['tokenPath']))) {
			mkdir(dirname($_SERVER['tokenPath']), 0700, true);
	}
	file_put_contents($_SERVER['tokenPath'], $response->raw_body);
}
