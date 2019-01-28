from socket import *
import ssl
import _ssl
import configparser

config = configparser.ConfigParser()
config.read("config.ini")

HOST = config['lacthosa']['host']
PORT = int(config['lacthosa']['port'])
context = ssl.SSLContext(_ssl.PROTOCOL_TLSv1_2)
context.load_verify_locations('certificates/lacthosa.crt')

with socket(AF_INET, SOCK_STREAM) as s:
    with context.wrap_socket(s, server_hostname=HOST) as sock:
        sock.connect((HOST, PORT))
        examplejson = """[
        {
            "station": 123456,
            "measurement": {
                "time": "15:01",
                "date": "21-01-19",
                "temperature": "21",
                "humidity": "10",
                "dewpoint": "15"
            }
        },
        {
            "station": 123457,
            "measurement": {
                "time": "15:01",
                "date": "21-01-19",
                "temperature": "21",
                "humidity": "10",
                "dewpoint": "15"
            }
        },
        {
            "station": 123458,
            "measurement": {
                "time": "15:01",
                "date": "21-01-19",
                "temperature": "21",
                "humidity": "10",
                "dewpoint": "15"
            }
        },
        {
            "station": 123459,
            "measurement": {
                "time": "15:01",
                "date": "21-01-19",
                "temperature": "21",
                "humidity": "10",
                "dewpoint": "15"
            }
        }
        ]"""
        sock.sendall(examplejson.encode())

