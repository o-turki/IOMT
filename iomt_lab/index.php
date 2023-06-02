<?php

date_default_timezone_set("Africa/Tunis");

// JSON HEADERS
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


// TRY TO CONNECT TO THE DATABASE
try {
    $hostname = "127.0.0.1";
    $db_name = "iomt_lab";
    $username = "root";
    $password = "";

    $dsn = "mysql:host=$hostname;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "error" => "Connection failed",
        "message" => $e->getMessage()
    ]);
    exit();
}


// EXAMPLE: http://192.168.137.1/iomt_lab/
$method = $_SERVER["REQUEST_METHOD"];

if ($method == "GET") {
    // GET: MAHRAN
    $sql = "SELECT `BPM` FROM `BPM` ORDER BY `id` DESC LIMIT 1;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $bpm = $stmt->fetch();
    $stmt->closeCursor();

    echo json_encode($bpm);
} else if ($method == "POST") {
    $data = (array) json_decode(file_get_contents("php://input"), true);

    $BPM = $data["BPM"];

    $sql = "INSERT INTO `BPM`
                (`BPM`)
            VALUES
                (:BPM);";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":BPM" => $BPM
    ]);
    $stmt->closeCursor();

    http_response_code(201);
    echo json_encode($data);
}
