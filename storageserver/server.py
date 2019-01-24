from socket import *
from storageserver import jsonparser
import configparser

config = configparser.ConfigParser()
config.read("config.ini")

HOST = config['lacthosa']['host']
PORT = int(config['lacthosa']['port'])

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

