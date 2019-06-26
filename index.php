<?php
require __DIR__ . '/vendor/autoload.php';

$connection = new \Valsplat\Harvest\Connection();
$connection->setAccessToken($_SERVER['HARVEST_ACCESS_TOKEN']);
$connection->setAccountId($_SERVER['HARVEST_ACCOUNT_ID']);

$harvest = new \Valsplat\Harvest\Harvest($connection);
$project_id = 21322146; // MIXED TEAM project

$entries = $harvest->timeEntry()->list(['project_id'=>$project_id]);

foreach($entries as $entry){
	preg_match('/^(GAZELLEB2C-\d+|BBB-\d+|FOCUS-\d+|KAL-\d+|CDB2BGAZ-\d+|MT-\d+)*$/', $entry->notes, $matches);
	if($matches){ 
		$matches['note'] = $entry->notes;
		$matches['time'] = $entry->hours;
		echo "<pre>";
			print_r($matches);
		echo "</pre>";
	}
}




