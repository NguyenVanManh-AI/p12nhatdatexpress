import Echo from "laravel-echo"
window.io = require('socket.io-client');

// import { io } from "https://cdn.socket.io/4.4.1/socket.io.esm.min.js";
const socket = io();

if (typeof io !== 'undefined') {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: SOCKET_HOST_URL,
    });
}

jQuery(document).on('ready', function ($) {
    
})
