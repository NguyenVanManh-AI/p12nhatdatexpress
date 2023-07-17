require('laravel-echo-server').run({
	"authHost": "https://nhadatexpress.toolgencode.com",
	"authEndpoint": "/broadcasting/auth",
	"clients": [],
	"database": "redis",
	"databaseConfig": {
		"redis": {
		    "host": "127.0.0.1",
            "port": "6379"
		},
	},
	"devMode": true,
    "port": "6001",
    "protocol": "https",
    "socketio": {},
    "sslCertPath": "/home/toolgenc/ssl/certs/nhadatexpress_toolgencode_com_b22e2_51929_1648271696_3fbf27a7de9531a4d1a08b074a7a06f5.crt",
	"sslKeyPath": "/home/toolgenc/ssl/keys/b22e2_51929_7e81d8de0c37ddcbeb656b0d8e8b0ff7.key",
    "sslCertChainPath": "",
    "sslPassphrase": "",
    "subscribers": {
            "http": true,
            "redis": true
    },
    "apiOriginAllow": {
            "allowCors": true,
            "allowOrigin": "https://nhadatexpress.toolgencode.com",
            "allowMethods": "GET, POST",
            "allowHeaders": "Origin, Content-Type, X-Auth-Token, X-Requested-With, Accept, Authorization, X-CSRF-TOKEN, X-Socket-Id"
	}
});