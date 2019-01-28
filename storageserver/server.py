from socket import *
import ssl
from storageserver import jsonparser
import configparser

config = configparser.ConfigParser()
config.read("config.ini")

HOST = config['lacthosa']['host']
PORT = int(config['lacthosa']['port'])
context = ssl.SSLContext(ssl.PROTOCOL_SSLv23)
context.load_cert_chain('certificates/lacthosa.crt', 'certificates/lacthosa.key')


while True:
    with socket(AF_INET, SOCK_STREAM) as s:
        s.bind((HOST, PORT))
        s.listen(1)
        with context.wrap_socket(s, server_side=True) as sock:
            try:
                conn, addr = sock.accept()
                print("Connection established...")
                with conn:
                    while True:
                        data = conn.recv(2048)
                        if not data:
                            break
                        jsonparser.from_stream(data.decode())
            except ConnectionResetError:
                print("Connection terminated unexpectedly, closing socket...")
                sock.close()
