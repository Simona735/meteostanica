<?php

require_once "config.php";


$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$insert_to_db = 0;

$sql = "SELECT * FROM settings";
$stmt = $conn->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if($result != null){
    $insert_to_db = (int) $result["insert_to_db"];
    echo "success";
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