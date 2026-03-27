<?php

require __DIR__ . '/functions.php';

$address = "0.0.0.0";
$port = 8060;
$null = NULL;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($sock, $address, $port);
socket_listen($sock);

echo "WebSocket server listening on port $port\n";

$connections = [$sock];
$clients = [];

while (true) {
    $reads = $connections;
    $writes = $exceptions = $null;

    socket_select($reads, $writes, $exceptions, null);

    if (in_array($sock, $reads)) {
        $newSocket = socket_accept($sock);
        $header = socket_read($newSocket, 1024);
        handshake($header, $newSocket, $address, $port);

        $connections[] = $newSocket;
        $clients[(int)$newSocket] = ['socket' => $newSocket];

        $welcome = pack_data("Welcome to the WebSocket chat server!\n");
        socket_write($newSocket, $welcome, strlen($welcome));

        $sockIndex = array_search($sock, $reads);
        unset($reads[$sockIndex]);
    }

    foreach ($reads as $key => $socketResource) {
        $data = socket_read($socketResource, 1024);

        if ($data === false || $data === '') {
            echo "Client disconnected: $key\n";
            unset($connections[array_search($socketResource, $connections)]);
            unset($clients[(int)$socketResource]);
            socket_close($socketResource);
            continue;
        }

        $message = unmask($data);

        foreach ($connections as $client) {
            if ($client !== $sock && $client !== $socketResource) {
                socket_write($client, pack_data($message), strlen(pack_data($message)));
            }
        }
    }
}

socket_close($sock);