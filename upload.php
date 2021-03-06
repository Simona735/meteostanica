<?php

require_once "config.php";

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$insert_to_db = 0;

$sql = "SELECT * FROM settings";
$result = $conn->query($sql);

if($result->num_rows == 1){
    $result = $result->fetch_assoc();
    $insert_to_db = $result["insert_to_db"];
    echo "realtime#".$result["insert_time"]."~";
}else{
    echo "settings error: " .$sql . $conn->error;
}

require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount(FB_DB_JSON_KEY)
    ->withDatabaseUri(FB_DB_LINK);

$FBdatabase = $factory->createDatabase();

if(isset($_POST['temperature'])){
    $postData = [
        'temperature' => $_POST["temperature"],
        'humidity' => $_POST["humidity"],
        'illuminance' => $_POST["illuminance"],
    ];

    $reference = $FBdatabase->getReference('realtime_data')->update($postData);
}

date_default_timezone_set ( "Europe/Bratislava" );


if(isset($_POST['temperature']) && $insert_to_db){
    $sql = "INSERT INTO `data`(`temperature`, `humidity`, `illuminance`) VALUES (".$_POST["temperature"].", ".$_POST["humidity"].", ".$_POST["illuminance"]." )";

    $inserted_id = 0;
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        $inserted_id = $conn->insert_id;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $result = $conn->query("SELECT timestamp FROM data WHERE `ID`=".$inserted_id);

    $conn->close();

    $postData = [
        'ID' => $inserted_id,
        'temperature' => $_POST["temperature"],
        'humidity' => $_POST["humidity"],
        'illuminance' => $_POST["illuminance"],
        'timestamp' => $result->fetch_assoc()["timestamp"]
    ];

    $reference = $FBdatabase->getReference('tina_data')->push($postData);
}
?>