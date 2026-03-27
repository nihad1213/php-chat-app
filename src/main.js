document.addEventListener('DOMContentLoaded', () => {
    const chatLog = document.getElementById('chat-log');
    const messageInput = document.getElementById('message-input');
    const chatForm = document.getElementById('chat-form');
    const connectBtn = document.getElementById('connect-btn');
    const sendBtn = document.getElementById('send-btn');
    const statusDot = document.getElementById('status-dot');
    const statusText = document.getElementById('status-text');

    const wsUrl = 'ws://localhost:8060'; 
    
    let socket = null;

    function addMessage(text, type = 'info') {
        const div = document.createElement('div');
        div.classList.add('message-row');
        
        const timestamp = new Date().toLocaleTimeString();
        
        if (type === 'system') {
            div.innerHTML = `<small class="text-muted">[${timestamp}] <strong>System:</strong> ${text}</small>`;
            div.classList.add('text-muted');
        } else if (type === 'sent') {
            div.innerHTML = `<small class="text-muted">[${timestamp}]</small> <strong>You:</strong> ${escapeHtml(text)}`;
            div.classList.add('text-end', 'text-primary');
        } else {
            div.innerHTML = `<small class="text-muted">[${timestamp}]</small> <strong>Other:</strong> ${escapeHtml(text)}`;
        }

        chatLog.appendChild(div);
        chatLog.scrollTop = chatLog.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function updateState(connected) {
        if (connected) {
            statusDot.classList.add('status-connected');
            statusText.textContent = 'Connected';
            messageInput.disabled = false;
            sendBtn.disabled = false;
            connectBtn.textContent = 'Disconnect';
            connectBtn.classList.replace('btn-success', 'btn-danger');
        } else {
            statusDot.classList.remove('status-connected');
            statusText.textContent = 'Disconnected';
            messageInput.disabled = true;
            sendBtn.disabled = true;
            connectBtn.textContent = 'Connect';
            connectBtn.classList.replace('btn-danger', 'btn-success');
        }
    }

    function connect() {
        if (socket) {
            socket.close();
            return;
        }

        try {
            socket = new WebSocket(wsUrl);

            socket.onopen = () => {
                updateState(true);
                addMessage('Connection established.', 'system');
            };

            socket.onmessage = (event) => {
                addMessage(event.data, 'received');
            };

            socket.onclose = () => {
                updateState(false);
                addMessage('Connection lost.', 'system');
                socket = null;
            };

            socket.onerror = (error) => {
                console.error('WebSocket Error:', error);
                addMessage('Error connecting to server.', 'system');
                updateState(false);
                socket = null;
            };

        } catch (e) {
            console.error(e);
            addMessage('Failed to create WebSocket.', 'system');
        }
    }

    function disconnect() {
        if (socket) {
            socket.close();
        }
    }

    connectBtn.addEventListener('click', () => {
        if (socket && socket.readyState === WebSocket.OPEN) {
            disconnect();
        } else {
            connect();
        }
    });

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const message = messageInput.value.trim();
        
        if (message && socket && socket.readyState === WebSocket.OPEN) {
            socket.send(message);

            addMessage(message, 'sent');
            messageInput.value = '';
        }
    });

    updateState(false);
});