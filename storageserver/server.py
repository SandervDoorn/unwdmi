from socket import *
import ssl
import _ssl
from storageserver import jsonparser
import threading
import os
import csv
import json
import configparser
import datetime

config = configparser.ConfigParser()
config.read("config.ini")


def last_data():
    """
    Traverses all station directories and returns a JSONArray of all last measurements
    :return:
    """
    list = []
    date = datetime.datetime.now()
    today = date.strftime('%d-%m-%Y')

    subdirs = [x[1] for x in os.walk(config['lacthosa']['filepath'])]
    stationFolders = subdirs[0]

    for station in stationFolders:
        file = config['lacthosa']['filepath'] + station + '/' + today + '.csv'
        lastline = []
        if os.path.exists(file):
            with open(file, 'r') as f:
                for row in csv.reader(f):
                    lastline = row
        else:
            break

        j = json.loads('{"station": "%s","measurement": {"temp": %s,"hum": %s,"dewp": %s}}'
                      % (station, lastline[2], lastline[3], lastline[4]))
        list.append(j)
    return list


def listener():
    """
    Starts a listening socket to read and parse data coming from the weather stations
    :return:
    """
    HOST = config['lacthosa']['host']
    PORT = int(config['lacthosa']['port'])

    context = ssl.SSLContext(_ssl.PROTOCOL_TLSv1_2)
    context.load_cert_chain('certificates/lacthosa.crt', 'certificates/lacthosa.key')

    while True:
        with socket(AF_INET, SOCK_STREAM) as s:
            s.bind((HOST, PORT))
            s.listen(1)
            with context.wrap_socket(s, server_side=True) as sock:
                try:
                    conn, addr = sock.accept()
                    with conn:
                        while True:
                            data = conn.recv(2048)
                            if not data:
                                break
                            jsonparser.from_stream(data.decode())
                except ConnectionResetError:
                    print("Connection terminated unexpectedly, closing socket...")
                    sock.close()


# def sender():
#       Function omitted as it is not longer in use, code remains for future purposes
#     """
#     Starts a socket that listens for web-interface requests for live information on specific weather stations
#     :return:
#     """
#     HOST = config['web-interface']['host']
#     PORT = int(config['web-interface']['port'])
#
#     while True:
#         with socket(AF_INET, SOCK_STREAM) as sock:
#             sock.bind((HOST, PORT))
#             sock.listen(30)
#             try:
#                 conn, addr = sock.accept()
#                 with conn:
#                     returndata = json.dumps(last_data())
#                     conn.sendall(returndata.encode())
#             except (ConnectionResetError, ConnectionError, ConnectionAbortedError, ConnectionRefusedError):
#                 print("Connection terminated, restarting socket...")
                sock.close()


threading.Thread(target=listener).start()
# threading.Thread(target=sender).start()
