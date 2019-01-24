from socket import *

HOST = '127.0.0.1'
PORT = 65456

with socket(AF_INET, SOCK_STREAM) as s:
    s.connect((HOST, PORT))
    s.sendall(b'{"station": 123456}')

