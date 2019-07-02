<?php 

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/globals.php';

if(!isset($_GET['code'])) : ?>
  <a href="https://<?= $_SERVER['JIRA_INSTANCE']; ?>.atlassian.net/plugins/servlet/ac/io.tempo.jira/oauth-authorize/?client_id=<?= $_SERVER['TEMPO_CLIENT_ID']; ?>&redirect_uri=<?= $_SERVER['TEMPO_REDIRECT']; ?>&access_type=tenant_user">Login to tempo</a>
<?php else: 
  $headers = array('Accept' => 'application/json');
  $query = array(
              'grant_type' => 'authorization_code',
              'client_id' => $_SERVER['TEMPO_CLIENT_ID'], 
              'client_secret' => $_SERVER['TEMPO_CLIENT_SECRET'],
              'redirect_uri' => $_SERVER['TEMPO_REDIRECT'],
              'code' => $_GET['code'] );

  $response = Unirest\Request::post('https://api.tempo.io/oauth/token/', $headers, $query);
  
  // Save the token to a file.
  if (!file_exists(dirname($_SERVER['tokenPath']))) {
      mkdir(dirname($_SERVER['tokenPath']), 0700, true);
  }
  file_put_contents($_SERVER['tokenPath'], $response->raw_body);
endif; ?>