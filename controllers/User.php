<?php
session_start();

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class User implements MessageComponentInterface {

	/**
	 * When a new connection is opened it will be passed to this method
	 *
	 * @param  ConnectionInterface $conn The socket/connection that just connected to your application
	 * @throws \Exception
	 */
	function onOpen(ConnectionInterface $conn) {}

	/**
	 * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
	 *
	 * @param  ConnectionInterface $conn The socket/connection that is closing/closed
	 * @throws \Exception
	 */
	function onClose(ConnectionInterface $conn) {}

	/**
	 * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
	 * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
	 *
	 * @param  ConnectionInterface $conn
	 * @param  \Exception $e
	 * @throws \Exception
	 */
	function onError(ConnectionInterface $conn, \Exception $e) {
		$conn->send(json_encode(['error' => true, 'message' => $e->getMessage()]));
	}

	/**
	 * Triggered when a client sends data through the socket
	 *
	 * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
	 * @param  string $msg                        The message received
	 * @throws \Exception
	 */
	function onMessage(ConnectionInterface $from, $msg) {
		$msg = json_decode($msg, true);
		$return_msg = [];
		if(isset($msg['connect']) && $msg['connect'] === true) {
			$_SESSION['user'] = 61;
			$return_msg = ['connected' => true, 'id' => $_SESSION['user']];
		}
		elseif (isset($msg['disconnect']) && $msg['disconnect']) {
			unset($_SESSION['user']);
			$return_msg = ['disconnect' => true];
		}
		elseif (isset($msg['is_connected']) && $msg['is_connected']) {
			$return_msg = isset($_SESSION['user']) ? ['connected' => true] : ['connected' => false];
		}
		$from->send(json_encode($return_msg));
	}
}