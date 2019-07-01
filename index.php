<?php
require __DIR__ . '/vendor/autoload.php';

$connection = new \Valsplat\Harvest\Connection();
$connection->setAccessToken($_SERVER['HARVEST_ACCESS_TOKEN']);
$connection->setAccountId($_SERVER['HARVEST_ACCOUNT_ID']);

$harvest = new \Valsplat\Harvest\Harvest($connection);
$project_id = 21322146; // MIXED TEAM project

$entries = $harvest->timeEntry()->list(['project_id'=>$project_id]);

$headers = array( 'Accept' => 'application/json' );

// basic auth
\Unirest\Request::auth( $_SERVER['ATLASSIAN_USER'], $_SERVER['ATLASSIAN_TOKEN'] );


foreach($entries as $entry){
	preg_match('/^(GAZELLEB2C-\d+|BBB-\d+|FOCUS-\d+|KAL-\d+|CDB2BGAZ-\d+|MT-\d+)*$/', $entry->notes, $matches);
	if($matches){ 
		$matches['note'] = $entry->notes;
		$matches['time'] = $entry->hours;
		
		$response = Unirest\Request::get(
			'https://'.$_SERVER['JIRA_INSTANCE'].'.atlassian.net/rest/api/3/search?fields=project,issuetype,timeestimate,timeoriginalestimate,timetracking,summary,io.tempo.jira__team,io.tempo.jira__account&jql=issue in ("'.$matches[0].'")',
			$headers
		);
		$jiraId = $response->body->issues[0]->id;
		var_dump($jiraId);
		break;
		
	}
}




