<?php


define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/filelib.php');

header('Content-Type: application/json; charset=utf-8');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Method Not Allowed']));
}

// testes de segurança 
require_login();

$sesskey  = required_param('sesskey',  PARAM_RAW);
$question = required_param('question', PARAM_TEXT);
$question = trim($question);

if (!confirm_sesskey($sesskey)) {
    http_response_code(403);
    die(json_encode(['error' => get_string('errorsesskey', 'block_gemini_chat')]));
}

if ($question === '') {
    echo json_encode(['error' => get_string('erroremptyquestion', 'block_gemini_chat')]);
    exit;
}

// leitura das configuraçoes 

$config          = get_config('block_gemini_chat');
$apikey          = $config->apikey          ?? '';
$model           = 'gemini-2.5-flash';
$maxoutputtokens = (int)($config->maxoutputtokens ?? 1024);
$temperature     = (float)($config->temperature   ?? 0.7);
$systemprompt    = $config->systemprompt    ?? '';

if (empty($apikey)) {
    echo json_encode(['error' => get_string('errornoapikey', 'block_gemini_chat')]);
    exit;
}

// ── Build the Gemini request payload ─────────────────────────────────────────

$contents = [];

// Optional system instruction (injected as a first "user" turn for models
// that do not support a dedicated system role).
if (!empty($systemprompt)) {
    $contents[] = [
        'role'  => 'user',
        'parts' => [['text' => $systemprompt]],
    ];
    $contents[] = [
        'role'  => 'model',
        'parts' => [['text' => 'Understood. I will follow those instructions.']],
    ];
}

$contents[] = [
    'role'  => 'user',
    'parts' => [['text' => $question]],
];

$request_body = json_encode([
    'contents'           => $contents,
    'generationConfig'   => [
        'maxOutputTokens' => $maxoutputtokens,
        'temperature'     => $temperature,
    ],
]);

// chama o REST API

$endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apikey}";

$curl = new curl();
$curl->setHeader(['Content-Type: application/json']);

$options = [
    'CURLOPT_TIMEOUT'        => 60,
    'CURLOPT_CONNECTTIMEOUT' => 15,
    'CURLOPT_RETURNTRANSFER' => true,
];

$raw_response = $curl->post($endpoint, $request_body, $options);

$http_code = $curl->get_info()['http_code'] ?? 0;

if ($curl->get_errno() || $http_code === 0) {
    echo json_encode(['error' => get_string('errorgeneral', 'block_gemini_chat') . ' (cURL error)']);
    exit;
}

$response = json_decode($raw_response, true);

// apresentar os erros da API

if (isset($response['error'])) {
    $msg = $response['error']['message'] ?? get_string('errorgeneral', 'block_gemini_chat');
    echo json_encode(['error' => $msg]);
    exit;
}

if ($http_code !== 200) {
    echo json_encode(['error' => get_string('errorgeneral', 'block_gemini_chat') . " (HTTP {$http_code})"]);
    exit;
}

//extrai a  pergunta feita

$answer = $response['candidates'][0]['content']['parts'][0]['text'] ?? null;

if ($answer === null) {
    
    $finish_reason = $response['candidates'][0]['finishReason'] ?? 'UNKNOWN';
    echo json_encode(['error' => "No response from model (finishReason: {$finish_reason})"]);
    exit;
}

// Caso de sucesso 

echo json_encode(['answer' => $answer]);
