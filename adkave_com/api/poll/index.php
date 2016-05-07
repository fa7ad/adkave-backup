<?php
/**
 * @package Adkave Polls
 * @author Fahad Hossain <evilinfinite7@gmail.com>
 * @license MIT
 */

initiate("questions.txt");

/**
 * @param string $file_name Name of the file containing questions
 */
function initiate($file_name = "questions.txt")
{
    header("Content-Type: application/json");
    header("charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        }
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }
    }
    $questions = preg_replace('~\r\n?~', "\n", trim(file_get_contents($file_name)));
    echo convert_json(make_question($questions));
}

/**
 * @param $all_questions array Take all questions as a string
 * @return string return a question randomly
 */
function make_question($all_questions)
{
    $all_questions = explode("\n", trim($all_questions));
    $question = $all_questions[mt_rand(0, (count($all_questions) - 1))];
    return $question;
}

/**
 * @param $question string Takes the question
 * @return string a JSON object with a question property.
 */
function convert_json($question)
{
    $json = array(
        'question' => $question
    );
    return json_encode($json);
}