<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP WebSocket Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        #chat-container { max-width: 800px; margin: 50px auto; }
        #chat-log { 
            height: 400px; 
            overflow-y: scroll; 
            background: white; 
            border: 1px solid #dee2e6; 
            padding: 15px; 
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .message-row { margin-bottom: 8px; }
        .status-indicator { 
            display: inline-block; 
            width: 10px; 
            height: 10px; 
            border-radius: 50%; 
            background-color: #dc3545; /* Red by default */
            margin-right: 5px;
        }
        .status-connected { background-color: #198754; /* Green */ }
    </style>
</head>
<body>

<div class="container" id="chat-container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">WebSocket Chat</h5>
            <div>
                <span id="status-dot" class="status-indicator"></span>
                <span id="status-text" class="text-white small">Disconnected</span>
            </div>
        </div>
        <div class="card-body">
            <div id="chat-log"></div>
            
            <form id="chat-form" class="d-flex gap-2">
                <input type="text" id="message-input" class="form-control" placeholder="Type a message..." autocomplete="off" disabled>
                <button type="submit" id="send-btn" class="btn btn-primary" disabled>Send</button>
                <button type="button" id="connect-btn" class="btn btn-success">Connect</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="main.js"></script>
</body>
</html>