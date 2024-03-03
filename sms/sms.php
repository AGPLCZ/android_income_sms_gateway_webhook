<?php
require_once "config.php";
ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING);

// Přijetí raw JSON payload z POST požadavku
$payload = file_get_contents('php://input');
$decoded = json_decode($payload, true);


// Příprava a spuštění SQL příkazu pro vložení dat
$sql = "INSERT INTO sms_messages (`from`, `fromName`, `text`, sent_stamp, received_stamp, sim) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Převod milisekundových Unix timestampů na formát MySQL DATETIME
$sentStamp = date('Y-m-d H:i:s', $decoded['sentStamp'] / 1000);
$receivedStamp = date('Y-m-d H:i:s', $decoded['receivedStamp'] / 1000);
$from = str_replace('+', '', $decoded['from']); // Odebrání plus z telefonního čísla
$stmt->bind_param("ssssss", $from, $decoded['fromName'], $decoded['text'], $sentStamp, $receivedStamp, $decoded['sim']);
$stmt->execute();
$stmt->close();

$conn->close();

// Sestavení obsahu zprávy
$content = "From: $from, Name: " . $decoded['fromName'] . ", Text: " . $decoded['text'] . ", Sent: $sentStamp, Received: $receivedStamp, SIM: " . $decoded['sim'];

// Odeslání upozornění pomocí ntfy
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: text/plain\r\n",
        'content' => $content
    ]
]);

file_get_contents('https://ntfy.sh/xxxxxxxxxxxxxxxxxxx', false, $context);
