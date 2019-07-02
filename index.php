<?php
require __DIR__ . '/vendor/autoload.php';

$connection = new \Valsplat\Harvest\Connection();
$connection->setAccessToken($_SERVER['HARVEST_ACCESS_TOKEN']);
$connection->setAccountId($_SERVER['HARVEST_ACCOUNT_ID']);

$harvest = new \Valsplat\Harvest\Harvest($connection);
$project_id = 21322146; // MIXED TEAM project

$entries = $harvest->timeEntry()->list(['project_id'=>$project_id]);

$headers = array( 'Accept' => 'application/json' );

foreach($entries as $entry){
	preg_match('/^(GAZELLEB2C-\d+|BBB-\d+|FOCUS-\d+|KAL-\d+|CDB2BGAZ-\d+|MT-\d+)*$/', $entry->notes, $matches);
	if($matches){ 
		$matches['note'] = $entry->notes;
		$matches['time'] = $entry->hours;
		
		// BBB workload to fill out
		// {"attributes":{"_Account_":{"workAttributeId":3,"value":"BBB-ENH-B2C"}},"billableSeconds":null,"workerId":"557058:b79dc083-8dc6-41b0-998c-1cb6710b60cb","comment":null,"started":"2019-07-01","timeSpentSeconds":3600,"originTaskId":"21559","remainingEstimate":null,"endDate":null,"includeNonWorkingDays":false}
		var_dump($jiraId);
		break;
		
	}
}




