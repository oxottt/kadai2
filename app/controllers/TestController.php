<?php
class TestController
{
    public function mongoAction()
    {
        try {
            // ???????????? ? MongoDB
            $client = new MongoDB\Client('mongodb://localhost:27017');
            
            // ???????? ???? ?????? ? ?????????
            $database = $client->webhook_db;
            $collection = $database->users;
            
            // ???????? ??????
            $result = $collection->insertOne([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);
            
            // ???????? ??? ??????
            $users = $collection->find();
            
            echo "<h1>MongoDB Test ?</h1>";
            echo "<p>Inserted document ID: " . $result->getInsertedId() . "</p>";
            echo "<h3>Users in collection:</h3>";
            
            foreach ($users as $user) {
                echo "<p>Name: " . ($user['name'] ?? 'N/A') . ", Email: " . ($user['email'] ?? 'N/A') . "</p>";
            }
            
        } catch (Exception $e) {
            echo "<h1>MongoDB Test ?</h1>";
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    }
}
