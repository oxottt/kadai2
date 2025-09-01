<?php
class WebhookController
{
    public function indexAction()
    {
        echo "<h1>Webhook Endpoint</h1>";
        echo "<p>Send POST requests with JSON to this endpoint</p>";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<h2>POST Request Received</h2>";
            
            // ???????? raw JSON ??????
            $jsonData = file_get_contents('php://input');
            
            // ??????? BOM ???? ????
            $jsonData = $this->removeBOM($jsonData);
            
            // ???????? raw ?????? ??? ???????
            echo "<p><strong>Raw input (first 100 chars):</strong> " . htmlspecialchars(substr($jsonData, 0, 100)) . "</p>";
            
            // ???????? ???????????? JSON
            $data = json_decode($jsonData, true);
            
            // ????????? ?????? JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "<p style='color: red;'>? JSON Error: " . json_last_error_msg() . "</p>";
                echo "<p>Error code: " . json_last_error() . "</p>";
                echo "<p>Received data length: " . strlen($jsonData) . " chars</p>";
                echo "<pre>Received data: " . htmlspecialchars($jsonData) . "</pre>";
                return;
            }
            
            if ($data) {
                echo "<pre>Received JSON: " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
                
                // ????????? ? MongoDB
                try {
                    $client = new MongoDB\Client('mongodb://localhost:27017');
                    $collection = $client->webhook_db->webhooks;
                    
                    $document = [
                        'hash' => $data['hash'] ?? null,
                        'name' => $data['name'] ?? null,
                        'family' => $data['family'] ?? null,
                        'data' => $data['data'] ?? [],
                        'update' => $data['update'] ?? null,
                        'received_at' => new MongoDB\BSON\UTCDateTime(),
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
                    ];
                    
                    $result = $collection->insertOne($document);
                    
                    echo "<p style='color: green;'>? Data saved to MongoDB! Document ID: " . $result->getInsertedId() . "</p>";
                    
                    // ????? ?????????/????????? ???????????? ? ????????? users
                    if (isset($data['hash']) && isset($data['name'])) {
                        $usersCollection = $client->webhook_db->users;
                        
                        $userData = [
                            'hash' => (int)$data['hash'],
                            'name' => $data['name'],
                            'family' => $data['family'] ?? '',
                            'data' => $data['data'] ?? [],
                            'update' => $data['update'] ?? time(),
                            'last_updated' => new MongoDB\BSON\UTCDateTime()
                        ];
                        
                        // ???? ???????????? ?? hash ? ????????? ??? ??????? ??????
                        $usersCollection->updateOne(
                            ['hash' => (int)$data['hash']],
                            ['$set' => $userData],
                            ['upsert' => true]
                        );
                        
                        echo "<p style='color: green;'>? User data updated in users collection!</p>";
                    }
                    
                } catch (Exception $e) {
                    echo "<p style='color: red;'>? Error saving to MongoDB: " . $e->getMessage() . "</p>";
                }
                
            } else {
                echo "<p style='color: red;'>? Empty or invalid JSON received</p>";
            }
        }
    }
    
    private function removeBOM($data) {
        if (substr($data, 0, 3) == "\xef\xbb\xbf") {
            return substr($data, 3);
        }
        return $data;
    }
}
