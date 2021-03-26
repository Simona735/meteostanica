<?php
header('Content-Type: application/json; charset=utf-8');
$response = array();
require_once "config.php";
try {

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    $insert_to_db = $decoded["element"];

    try {
        $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "UPDATE `settings` SET `insert_to_db`=".$insert_to_db." WHERE 1";

        $stmt = $conn->query($query);
        $data = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    $response = array(
        "status" => "success",
        "error" => false,
        "message" => $insert_to_db //"Insert to db updated successfully"
    );
    echo json_encode($response);

} catch (RuntimeException $e) {
    $response = array(
        "status" => "error",
        "error" => true,
        "message" => $e->getMessage()
    );
    echo json_encode($response);
}