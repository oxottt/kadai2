<?php
class UsersController
{
    public function indexAction()
    {
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .menu { margin-bottom: 20px; padding: 10px; background: #f5f5f5; }
        .menu a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .menu a:hover { text-decoration: underline; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:hover { background-color: #f5f5f5; }
        .json-data { font-family: monospace; background: #f8f9fa; padding: 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class=\"menu\">
        <a href=\"/\">Home</a>
        <a href=\"/webhook\">Webhook</a>
        <a href=\"/users\">Users</a>
        <a href=\"/test-mongo\">Test DB</a>
    </div>
    
    <h1>Users List</h1>";
        
        try {
            // ???????????? ? MongoDB
            $client = new MongoDB\Client('mongodb://localhost:27017');
            $collection = $client->webhook_db->users;
            
            // ???????? ?????????????
            $users = $collection->find([], ['sort' => ['last_updated' => -1], 'limit' => 50]);
            
            echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Hash</th>
                    <th>Name</th>
                    <th>Family</th>
                    <th>Update Timestamp</th>
                    <th>Data</th>
                    <th>Created At</th>
                    <th>Last Updated</th>
                </tr>";
            
            $count = 0;
            foreach ($users as $user) {
                $count++;
                echo "<tr>
                    <td>" . ($user['_id'] ?? 'N/A') . "</td>
                    <td>" . ($user['hash'] ?? 'N/A') . "</td>
                    <td>" . ($user['name'] ?? 'N/A') . "</td>
                    <td>" . ($user['family'] ?? 'N/A') . "</td>
                    <td>" . ($user['update'] ?? 'N/A') . "</td>
                    <td class=\"json-data\">" . json_encode($user['data'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</td>
                    <td>" . (isset($user['created_at']) ? 
                        date('Y-m-d H:i:s', $user['created_at']->toDateTime()->getTimestamp()) : 'N/A') . "</td>
                    <td>" . (isset($user['last_updated']) ? 
                        date('Y-m-d H:i:s', $user['last_updated']->toDateTime()->getTimestamp()) : 'N/A') . "</td>
                </tr>";
            }
            
            if ($count === 0) {
                echo "<tr><td colspan='8' style='text-align: center;'>No users found</td></tr>";
            }
            
            echo "</table>";
            
            echo "<p><strong>Total users: " . $collection->countDocuments() . "</strong></p>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error connecting to MongoDB: " . $e->getMessage() . "</p>";
        }
        
        echo "</body></html>";
    }
}
