<?php
/**
 * @package Adkave messages
 * @author Fahad Hossain <evilinfinite7@gmail.com>
 * @license MIT
 */

header("Content-Type: application/json");
header("charset=utf-8");
header("Access-Control-Allow-Origin: *");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: POST, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
}
try {
    $conn = new PDO('mysql:host=localhost;dbname=xarthcdz_slides', 'xarthcdz_sld', 'm3m3.c0m');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET CHARACTER SET utf8mb4");
    $sql = "SELECT * FROM slides";
    $results = $conn->query($sql);
    $counter = 0;
    $json_raw = array();
    if ($results != false) {
        foreach ($results as $row) {
            $json_raw[$counter] = array(
                'link' => $row['link'],
                'text' => $row['text']
            );
            $counter++;
        }
        echo json_encode($json_raw);
    } else {
        echo json_encode(array(
                0 => array(
                    'error' => 'No messages set'
                )
            )
        );
    }
} catch (PDOException $e) {
    echo json_encode(array(
            0 => array(
                'error' => $e->getMessage()
            )
        )
    );
}
$conn = null;