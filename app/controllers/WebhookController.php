<?php
class WebhookController
{
    public function indexAction()
    {
        // ????????????? ????????? Content-Type
        header('Content-Type: text/plain');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ???????? raw JSON ??????
            $jsonData = file_get_contents('php://input');
            $jsonData = $this->removeBOM($jsonData);
            
            $data = json_decode($jsonData, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                // ?????????? ?????? ?? ?? ???????? 200
                http_response_code(200);
                echo 'ERROR: Invalid JSON - ' . json_last_error_msg();
                return;
            }
            
            if ($data) {
                try {
                    $client = new MongoDB\Client('mongodb://localhost:27017');
                    
                    // ????????? ? webhooks ?????????
                    $webhooksCollection = $client->webhook_db->webhooks;
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
                    
                    $result = $webhooksCollection->insertOne($document);
                    
                    // ?????????? ? USERS ?????????
                    if (isset($data['hash']) && isset($data['name'])) {
                        $usersCollection = $client->webhook_db->users;
                        
                        $userData = [
                            'hash' => (int)$data['hash'],
                            'name' => $data['name'],
                            'family' => $data['family'] ?? '',
                            'data' => $data['data'] ?? [],
                            'update' => $data['update'] ?? time(),
                            'created_at' => new MongoDB\BSON\UTCDateTime(),
                            'last_updated' => new MongoDB\BSON\UTCDateTime()
                        ];
                        
                        $usersCollection->updateOne(
                            ['hash' => (int)$data['hash']],
                            ['$set' => $userData],
                            ['upsert' => true]
                        );
                    }
                    
                    // ?????????? ???????? ?????
                    http_response_code(200);
                    echo 'OK';
                    
                } catch (Exception $e) {
                    // ?????????? ?????? ?? ?? ???????? 200
                    http_response_code(200);
                    echo 'ERROR: MongoDB - ' . $e->getMessage();
                }
                
            } else {
                // ?????????? ?????? ?? ?? ???????? 200
                http_response_code(200);
                echo 'ERROR: Empty JSON received';
            }
        } else {
            // ??? GET ???????? ?????????? ??????????
            header('Content-Type: text/html');
            echo "<h1>Webhook Endpoint</h1>";
            echo "<p>Send POST requests with JSON to this endpoint</p>";
            echo "<p>Response: HTTP 200 with 'OK' text</p>";
        }
    }
    
    private function removeBOM($data) {
        if (substr($data, 0, 3) == "\xef\xbb\xbf") {
            return substr($data, 3);
        }
        return $data;
    }
}
