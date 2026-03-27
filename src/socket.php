<?php

require __DIR__ . '/functions.php';

$address = "0.0.0.0";
$port = 8060;
$null = NULL;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $address, $port);

socket_listen($sock);

echo "Listening for new connection on port $port \n";

$members = [];
$connections = [];

$connections = [$sock];

while(true) {
    $reads = $connections;
    $writes = $exceptions = $null;

    socket_select($reads, $writes, $exceptions, null);

    if (in_array($sock, $reads)) {
        $newConnection = socket_accept($sock);
        $connections[] = $newConnection;
        $reply = "connected to the chat socker server \n";
        socket_write($newConnection, $reply, strlen($reply));

        $sockIndex = array_search($sock, $reads);
        unset($reads[$sockIndex]);
    }

    foreach($reads as $key=>$value) {
        $data = socket_read($value, 1024);

        if (!empty($data)) {
            foreach($connections as $ckey => $cvalue) {
                if ($ckey === 0) continue;
                socket_write($cvalue, $data, strlen($data));
            }
        } else if ($data === '') {
            echo "disconnecting client $key \n";
            unset($connections[$key]);
            socket_close($value);
        }
    }
}


socket_close($sock);

