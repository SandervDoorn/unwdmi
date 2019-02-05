"""
web-interface socket tester file
"""
from socket import *
import configparser

config = configparser.ConfigParser()
config.read("config.ini")

HOST = config['web-interface']['host']
PORT = int(config['web-interface']['port'])


with socket(AF_INET, SOCK_STREAM) as s:
    s.connect((HOST, PORT))
    data = s.recv(2048)
    print(data.decode())
