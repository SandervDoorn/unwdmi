from socket import *
import configparser
import time

config = configparser.ConfigParser()
config.read("config.ini")

HOST = config['lacthosa']['host']
PORT = int(config['lacthosa']['port'])

with socket(AF_INET, SOCK_STREAM) as s:
    s.connect((HOST, PORT))
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
    s.sendall(examplejson.encode())

