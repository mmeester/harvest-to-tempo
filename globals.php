<?php

$_SERVER['tokenPath'] = __DIR__ . '/token.json';
$_SERVER['tempoBase'] = 'https://api.tempo.io';
$_SERVER['JIRA_TICKETS'] = '/^(GAZELLEB2C-\d+|BBB-\d+|FOCUS-\d+|KAL-\d+|CDB2BGAZ-\d+|MT-\d+)*$/';
$_SERVER['ACCOUNT_MAPPING'] = ['BBB' => 'BBB-ENH-B2C', 'FOCUS' => 'FOC-ENH-B2C', 'KAL' => 'KAL-ENH-B2C', 'MT' => 'MIX-MAIN-B2C'];