<?php

require 'vendor/autoload.php';

use \InitPHP\CLITable\Table;


$query = trim(strtolower(readline("Enter company name to display it's data: ")));

if($query === "") exit("Cant leave field empty");

$queryEncoded = urlencode($query);
$url = "https://data.gov.lv/dati/lv/api/3/action/datastore_search?q=$queryEncoded&resource_id=610910e9-e086-4c5b-a7ea-0a896a697672";

try {
    $response = file_get_contents($url);
    if ($response === false) throw new Exception("Couldn't retrieve data - check your internet connection");

    $data = json_decode($response, true);
    if ($data === false) echo "Couldn't parse json";

    $entries = $data["result"]["records"];

    if ($data["result"]["total"] === 0) throw new Exception("No companies with name $query found!");

    $table = new Table();

    foreach ($entries as $entry) {
        $table->row([
            "Registration number" => $entry["Numurs"],
            "Name" => $entry["Nosaukums"],
            "Registration date" => $entry["Reģistrēts"],
        ]);
    }

    echo $table;
} catch (Exception $e) {
    echo $e->getMessage();
}