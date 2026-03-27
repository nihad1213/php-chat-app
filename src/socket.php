<?php

$address = "0.0.0.0";
$port = 8060;
$null = NULL;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $address, $port);

socket_listen($sock);

echo "Listening for new connection on port $port \n";


