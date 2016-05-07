<?php
/**
 * @package Adkave Polls
 * @author Fahad Hossain <evilinfinite7@gmail.com>
 * @license MIT
 */
header("Content-Type: application/json");
header("charset=utf-8");
header("Access-Control-Allow-Origin: *");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: POST");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
}
$sql_server = array(
    'host' => 'localhost',
    'db' => 'xarthcdz_polls',
    'user' => 'xarthcdz_polladm',
    'pass' => 'poll1ng',
    'table' => 'polls'
);
$site = (isset($_POST['site'])) ? $_POST['site'] : (
(isset($_SERVER['HTTP_ORIGIN'])) ? $_SERVER['HTTP_ORIGIN'] : $_SERVER['REMOTE_ADDR']
);
$question = trim($_POST['question']);
$answer = trim($_POST['answer']);

try {
    $conn = new PDO(
        'mysql:host=' . $sql_server['host'] . ';dbname=' . $sql_server['db'],
        $sql_server['user'],
        $sql_server['pass']
    );

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare("INSERT INTO polls (TIME, site, question, answer)
    VALUES (NULL,:site,:question,:answer)");
    $sql->bindParam(':site', $site);
    $sql->bindParam(':question', $question);
    $sql->bindParam(':answer', $answer);
    $sql->execute();
    echo json_encode(array(
        'message' => "Thank You! Your answer has been successfully recorded."
    ));
} catch (PDOException $e) {
    echo json_encode(array(
        'message' => $e->getMessage()
    ));
}

$conn = null;