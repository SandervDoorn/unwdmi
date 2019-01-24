from socket import *

HOST = '127.0.0.1'
PORT = 65456

with socket(AF_INET, SOCK_STREAM) as s:
    s.connect((HOST, PORT))
    examplejson = """{
    "station": 123456,
	"measurement": {
		"time": "15:01",
		"date": "21-01-19",
		"temperature": "21",
		"humidity": "10",
		"dewpoint": "15"
	}
    }"""
    s.sendall(examplejson.encode())

