<?php
require '../vendor/autoload.php'; 
use MongoDB\Client; 

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->account; 
$collection = $db->users;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $mobile = $_POST['mobile'];
    $location = $_POST['location'];
    $password = $_POST['password'];

    if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/',$email)) {
        echo json_encode(['success' => false, 'message' => 'Email is required.']);
        exit;
    }

    if(!preg_match('/^[A-Za-z\s]+$/', $username)){
        echo json_encode(['success' => false, 'message' => 'Username is required.']);
        exit; 
    }

    if (!preg_match('/^[0-9]+$/',$mobile)) {
        echo json_encode(['success' => false, 'message' => 'Mobile is required.']);
        exit;
    }

    if (!preg_match('/^[A-Za-z\s]+$/',$location)) {
        echo json_encode(['success' => false, 'message' => 'Location is required.']);
        exit;
    }

    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/',$password)) {
        echo json_encode(['success' => false, 'message' => 'Password is required.']);
        exit;
    }


    $existingUser = $collection->findOne(['email' => $email]);
    if ($existingUser) {
        echo json_encode(['success' => false, 'message' => 'An account with this email already exists.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

    $userDocument = [
        'username' => $username,
        'email' => $email,
        'mobile' => $mobile,
        'location' => $location,
        'password' => $hashedPassword,
    ];

    
    try {
        $insertResult = $collection->insertOne($userDocument);

        if ($insertResult->getInsertedCount() === 1) {
            $tokenData = [
                'username' => $username,
                'email' => $email,
                'mobile' => $mobile,
                'location' => $location,
                'password' => $hashedPassword
            ];
        
            echo json_encode(['success' => true, 'message' => 'Registration successful']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error inserting data into MongoDB']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'MongoDB Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>

