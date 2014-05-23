<?php

function pg_connection_string_from_database_url() {
  extract(parse_url($_ENV["DATABASE_URL"]));
  return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
}


$pg_conn = pg_connect(pg_connection_string_from_database_url());

$tableMapping = array(
	'adate' => 'thedate',
	'adate_time' => 'thedate_time ',
	'text' => 'the_text ',
);

$table = 'google_spreadsheet_1';

foreach($_REQUEST['row'] as $id => $data){
	$sql = "UPDATE $table SET ";
	$colCount = 0;
	$updates = array();
	$colValues = array();
	foreach($data as $column => $value){
		$colCount++;
		$updates[] = $tableMapping[$column] . "=${$colCount}";
		$colValues[] = $value;
	}
	$sql .= implode(",", $updates);
	$sql .= "WHERE id=$" . ($colCount + 1);
	$colValues[] = $id;

	pg_query_params($sql, $colValues);
}

echo "UPDATED";