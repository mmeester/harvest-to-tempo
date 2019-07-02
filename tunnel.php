<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/globals.php';

$connection = new \Valsplat\Harvest\Connection();
$connection->setAccessToken($_SERVER['HARVEST_ACCESS_TOKEN']);
$connection->setAccountId($_SERVER['HARVEST_ACCOUNT_ID']);

$harvest = new \Valsplat\Harvest\Harvest($connection);

$entries = $harvest->timeEntry()->list([
              'project_id'=> $_SERVER['HARVEST_PROJECT_ID'],
              'from' => date('Y-m-d', time() - 60 * 60 * 24)
            ]);

$headers = array( 'Accept' => 'application/json' );

Unirest\Request::auth( $_SERVER['ATLASSIAN_USER'], $_SERVER['ATLASSIAN_TOKEN'] );

$response = Unirest\Request::get('https://'.$_SERVER['JIRA_INSTANCE'].'.atlassian.net/rest/api/3/myself', $headers );
$accountId = $response->body->accountId;



foreach($entries as $entry){
  preg_match($_SERVER['JIRA_TICKETS'], $entry->notes, $matches);
  if($matches){ 
    $project = explode('-', $matches[0]);
    $account = $_SERVER['ACCOUNT_MAPPING'][ $project[0] ];
    $headers['Authorization'] = 'Bearer '.$_SERVER['TEMPO_ACCESS_TOKEN'];
    
    $d = new DateTime($entry->timer_started_at);
    
    $data = [
              'issueKey' => $matches[0], 
              'timeSpentSeconds' => intval( $entry->hours*3600 ), 
              'startDate' => $d->format('Y-m-d'), 
              'startTime' => $d->format('H:i:s'),
              'description' => $entry->notes,
              'authorAccountId' =>  $accountId,
              'attributes' => [
                [
                  'key' =>  '_Account_',
                  'value' => $account
                ]
              ]
            ];
    $body = Unirest\Request\Body::json($data);
    
    $response = Unirest\Request::post( $_SERVER['tempoBase'] . '/core/3/worklogs', $headers, $body);
    
    break;
    
  }
}
