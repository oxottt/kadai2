<?php
class IndexController
{
    public function indexAction()
    {
        $phalconStatus = extension_loaded('phalcon') ? "?" : "?";
        $mongoStatus = class_exists("MongoDB\Client") ? "?" : "?";
        $phalconVersion = Phalcon\Version::get();
        
        $exampleJson = '{
    "hash": 1234,
    "name": "Ivan",
    "family": "Ivanov",
    "data": {
        "key": 123,
        "url": "www.example.com",
        "img name": "foto.png"
    },
    "update": 1581120000
}';

        echo "<!DOCTYPE html>
<html>
<head>
    <title>Webhook App</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .menu { margin-bottom: 20px; padding: 10px; background: #f5f5f5; }
        .menu a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .menu a:hover { text-decoration: underline; }
        .status { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .success { color: green; }
        .example { background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; }
        pre { background: #2b2b2b; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; }
        code { font-family: 'Consolas', 'Monaco', monospace; }
        .copy-btn { background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-top: 10px; }
        .copy-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class=\"menu\">
        <a href=\"/\">Home</a>
        <a href=\"/webhook\">Webhook</a>
        <a href=\"/users\">Users</a>
        <a href=\"/test-mongo\">Test DB</a>
    </div>
    
    <h1>Webhook App ??</h1>
    <p>Phalcon " . $phalconVersion . " is working!</p>
    
    <div class=\"status\">
        <h2>System Status:</h2>
        <p>Phalcon Extension: " . $phalconStatus . "</p>
        <p>MongoDB Library: " . $mongoStatus . "</p>
        <p>PHP Version: " . PHP_VERSION . "</p>
    </div>
    
    <div class=\"success\">
        <h3>? MongoDB Connection: Successful</h3>
        <p>Database is ready to use!</p>
    </div>
    
    <div class=\"example\">
        <h2>?? Example Webhook Request:</h2>
        <pre><code>" . htmlspecialchars($exampleJson) . "</code></pre>
        
        <button class=\"copy-btn\" onclick=\"copyJson()\">?? Copy JSON</button>
        
        <h3>Test with curl:</h3>
        <pre><code>curl -X POST http://localhost:8000/webhook \\
  -H \"Content-Type: application/json\" \\
  -d '" . addslashes($exampleJson) . "'</code></pre>
        
        <h3>Test with PowerShell:</h3>
        <pre><code>Invoke-WebRequest -Uri http://localhost:8000/webhook \\
  -Method POST \\
  -ContentType \"application/json\" \\
  -Body '" . addslashes($exampleJson) . "'</code></pre>
    </div>
    
    <h2>Features:</h2>
    <ul>
        <li><a href=\"/webhook\"><strong>Webhook endpoint</strong></a> - Accepts POST JSON data</li>
        <li><a href=\"/users\"><strong>User management</strong></a> - Displays users from MongoDB</li>
        <li><a href=\"/test-mongo\"><strong>Database test</strong></a> - Test MongoDB connection</li>
    </ul>
    
    <script>
    function copyJson() {
        const jsonText = `" . addslashes($exampleJson) . "`;
        navigator.clipboard.writeText(jsonText).then(() => {
            alert('JSON copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
    </script>
</body>
</html>";
    }
}
