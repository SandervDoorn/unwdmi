"""
This code might be moved to a different location. It is purely for initial development
"""

import json
import csv
import os
import configparser

config = configparser.ConfigParser()
config.read("config.ini")
settings = config['lacthosa']


def from_stream(data):
    if not data:
        return
    x = json.loads(data)
    for i in x["items"]:
        create_folder(str(i["station"]))
        measurement = i["measurement"]
        filepath = settings['filepath'] + str(i["station"]) + "/" + measurement["date"] + ".csv"
        add_to_csv(measurement, filepath)


def from_file(filepath):
    # Open the json file
    file = open(filepath)
    j = json.load(file)
    file.close()

    # Create folder for station ID
    create_folder(str(j["station"]))

    measurement = j["measurement"]
    filepath = settings['filepath'] + str(j["station"]) + "/" + measurement["date"] + ".csv"

    add_to_csv(measurement, filepath)


def create_folder(station_id):
    path = settings['filepath'] + station_id
    permissions = 0o755
    if not os.path.isdir(path):
        os.mkdir(path, permissions)


def add_to_csv(measurement, file):

    newfile = True

    if os.path.exists(file):
        newfile = False

    with open(file, "a", newline='') as csvfile:
        writer = csv.writer(csvfile, quoting=csv.QUOTE_MINIMAL)

        # TODO: 1 file for station, 1 line per date with averages
        if newfile:
            writer.writerow(['time', 'date', 'temperature', 'humidity', 'dewpoint'])

        print("Processing row")
        writer.writerow([measurement['time'],
                         measurement['date'],
                         measurement['temp'],
                         measurement['hum'],
                         measurement['dewp']])
        print("Row added")



