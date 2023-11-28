<?php
require 'C:\xampp\htdocs\loginform\vendor/autoload.php';
require 'C:\xampp\htdocs\loginform\vendor\predis\predis/autoload.php';

use Predis\Client;

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->account;
$collection = $db->users;

$redis = new Client();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_POST['token']) ? $_POST['token'] : null;
    $newUsername = $_POST['newUsername'];
    $newMobile = $_POST['newMobile'];
    $newLocation = $_POST['newLocation'];

    $redisKey = "user_token:$token";
    $userData = json_decode($redis->get($redisKey));

    if ($userData) {
        $updateResult = $collection->updateOne(
            ['_id' => $userData->_id],
            ['$set' => ['username' => $newUsername, 'mobile' => $newMobile, 'location' => $newLocation]]
        );

        if ($updateResult->getModifiedCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid token']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
