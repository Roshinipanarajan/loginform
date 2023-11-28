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
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = $collection->findOne(['email' => $email]);

    if ($user) {
        $hashedPassword = $user->password;
        if (password_verify($password, $hashedPassword)) {
            $token = bin2hex(random_bytes(16));
            $redisKey = "user_token:$token";
            $redis->setex($redisKey, 86400, json_encode($user));
            echo json_encode(['success' => true, 'message' => 'Login successful', 'token' => $token, 'userData' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
