<?php
echo "<h1>Test Page</h1>";
echo "<p>Phalcon Extension: " . (extension_loaded('phalcon') ? '?' : '?') . "</p>";
echo "<p>MongoDB Library: " . (class_exists('MongoDB\Client') ? '?' : '?') . "</p>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
