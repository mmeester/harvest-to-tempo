<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/globals.php';

$connection = new \Valsplat\Harvest\Connection();
$connection->setAccessToken($_SERVER['HARVEST_ACCESS_TOKEN']);
$connection->setAccountId($_SERVER['HARVEST_ACCOUNT_ID']);

$harvest = new \Valsplat\Harvest\Harvest($connection);
$project_id = 21322146; // MIXED TEAM project

$entries = $harvest->timeEntry()->list(['project_id'=>$project_id]);

$headers = array( 'Accept' => 'application/json' );
Unirest\Request::auth( $_SERVER['ATLASSIAN_USER'], $_SERVER['ATLASSIAN_TOKEN'] );

$response = Unirest\Request::get('https://'.$_SERVER['JIRA_INSTANCE'].'.atlassian.net/rest/api/3/myself', $headers );
$accountId = $response->body->accountId;

$tokens = json_decode(file_get_contents($_SERVER['tokenPath']), true);

foreach($entries as $entry){
  preg_match('/^(GAZELLEB2C-\d+|BBB-\d+|FOCUS-\d+|KAL-\d+|CDB2BGAZ-\d+|MT-\d+)*$/', $entry->notes, $matches);
  if($matches){ 
    $matches['note'] = $entry->notes;
    $matches['time'] = $entry->hours;
    
    $headers['Authorization'] = 'Bearer ' . $tokens['access_token'];
    
    $d = new DateTime($entry->timer_started_at);
    
    $data = [
              'issueKey' => $matches[0], 
              'timeSpentSeconds' => intval( $entry->hours*3600 ), 
              'startDate' => $d->format('Y-m-d'), 
              'startTime' => $d->format('H:i:s'),
              'description' => $entry->notes,
              'authorAccountId' =>  $accountId,
              'attributes' => [
                ['key' =>  '_Account_','value' => 'KAL-ENH-B2C']
              ]
            ];
    $body = Unirest\Request\Body::json($data);
    
    $response = Unirest\Request::post( $_SERVER['tempoBase'] . '/core/3/worklogs', $headers, $body);
    var_dump($response);
    // var_dump($_SERVER['tempoBase'] . '/core/3/worklogs');
    // var_dump($headers);
    // var_dump($body);
    break;
    
  }
}
