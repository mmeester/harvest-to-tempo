<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/globals.php';

file_put_contents(__DIR__ . '/process_cron.log', '['.date("F j, Y, g:i a").'][START]: Starting import from Harvest'.PHP_EOL, FILE_APPEND);

$connection = new \Valsplat\Harvest\Connection();
$connection->setAccessToken($_SERVER['HARVEST_ACCESS_TOKEN']);
$connection->setAccountId($_SERVER['HARVEST_ACCOUNT_ID']);

$harvest = new \Valsplat\Harvest\Harvest($connection);

// Get latest entries starting from yesterday
$entries = $harvest->timeEntry()->list([
              'project_id'=> $_SERVER['HARVEST_PROJECT_ID'],
              'from' => date('Y-m-d', time() - 60 * 60 * 24)
            ]);

file_put_contents(__DIR__ . '/process_cron.log', '['.date("F j, Y, g:i a").']: Found '.count($entries).' entries on Harvest'.PHP_EOL, FILE_APPEND);

$headers = array( 'Accept' => 'application/json' );

Unirest\Request::auth( $_SERVER['ATLASSIAN_USER'], $_SERVER['ATLASSIAN_TOKEN'] );

$response = Unirest\Request::get('https://'.$_SERVER['JIRA_INSTANCE'].'.atlassian.net/rest/api/3/myself', $headers );
$accountId = $response->body->accountId;

foreach($entries as $entry){
  preg_match($_SERVER['JIRA_TICKETS'], $entry->notes, $matches);
  if($matches){ 
    $project = explode('-', $matches[0]);
    $account = $_SERVER['ACCOUNT_MAPPING'][ $project[0] ];
    $headers['Authorization'] = 'Bearer ' . $_SERVER['TEMPO_ACCESS_TOKEN'];
    
    $data = [
              'issueKey' => $matches[0], 
              'timeSpentSeconds' => intval( $entry->hours*3600 ), 
              'startDate' => $entry->spent_date, 
              'startTime' => '12:00:00',
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
    
    if($response->code===200){ 
      file_put_contents(__DIR__ . '/process_cron.log', '['.date("F j, Y, g:i a").'][SUCCESS]['.$response->code.']: Logged '.$matches[0].' to Tempo'.PHP_EOL, FILE_APPEND);
    }else {
      file_put_contents(__DIR__ . '/process_cron.log', '['.date("F j, Y, g:i a").'][ERROR]['.$response->code.']: '.$response->body->errors[0]->message.PHP_EOL, FILE_APPEND);
    }
  }
}

file_put_contents(__DIR__ . '/process_cron.log', '['.date("F j, Y, g:i a").'][END]: Import done'.PHP_EOL, FILE_APPEND);
