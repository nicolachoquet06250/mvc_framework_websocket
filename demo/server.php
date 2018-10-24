<?php
require __DIR__.'/../autoload.php';

// Run the server application through the WebSocket protocol on port 8080
$app = new Ratchet\App(
	"localhost",
	2107);
$app->route('/chat', new Chat, array('*'), 'localhost');
$app->route('/login', new User, array('*'), 'localhost');

$app->run();