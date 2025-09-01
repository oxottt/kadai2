<?php
require_once 'vendor/autoload.php';

try {
    $client = new MongoDB\Client('mongodb://localhost:27017');
    $usersCollection = $client->webhook_db->users;
    
    $usersCollection->updateMany(
        ['family' => ['$exists' => false]],
        ['$set' => ['family' => 'Unknown']]
    );
    
    echo "Users updated with default family name\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
