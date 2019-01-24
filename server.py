from socket import *
from jsonparser import from_stream as j

HOST = '127.0.0.1'
PORT = 65456

with socket(AF_INET, SOCK_STREAM) as s:
    s.bind((HOST, PORT))
    s.listen()
    conn, addr = s.accept()
    with conn:
        print("Connected with ", addr)
        while True:
            data = conn.recv(2048)
            if not data:
                break
            j(data.decode())

