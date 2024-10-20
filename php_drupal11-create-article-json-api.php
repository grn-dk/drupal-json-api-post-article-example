<?php
require 'vendor/autoload.php'; // Autoload required for Dotenv

use Dotenv\Dotenv;

// Load environment variables from the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get credentials from environment variables
$drupalUrl = $_ENV['DRUPAL_URL'];
$username = $_ENV['DRUPAL_USERNAME'];
$password = $_ENV['DRUPAL_PASSWORD'];

// Content data to be posted
$articleData = [
    'data' => [
        'type' => 'node--article',  // Change 'article' to your content type machine name
        'attributes' => [
            'title' => 'Sample Article Title',
            'body' => [
                'value' => '<p>This is the body of the article</p>',
                'format' => 'basic_html',  // Change format as per your site settings
            ],
        ],
    ],
];

// Authenticate with Drupal and get CSRF token
$authUrl = "$drupalUrl/user/login?_format=json";
$authData = json_encode([
    'name' => $username,
    'pass' => $password,
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $authUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $authData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    die('Authentication failed: ' . $response);
}

$responseData = json_decode($response, true);
$csrfToken = $responseData['csrf_token'] ?? null;

if (!$csrfToken) {
    die('Unable to retrieve CSRF token');
}

// Post article
$nodeUrl = "$drupalUrl/jsonapi/node/article"; // Update path based on content type
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $nodeUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($articleData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/vnd.api+json',
    'X-CSRF-Token: ' . $csrfToken,
    'Authorization: Basic ' . base64_encode("$username:$password"),
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$articleResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    echo 'Article posted successfully: ' . $articleResponse;
} else {
    echo 'Failed to post article: ' . $articleResponse;
}

?>
