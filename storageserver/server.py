from socket import *
from storageserver import jsonparser

HOST = '127.0.0.1'
PORT = 65456

with socket(AF_INET, SOCK_STREAM) as s:
    s.bind((HOST, PORT))
    s.listen()
    conn, addr = s.accept()
    with conn:
        while True:
            data = conn.recv(2048)
            if not data:
                break
            jsonparser.from_stream(data.decode())

