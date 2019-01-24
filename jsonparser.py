"""
This code might be moved to a different location. It is purely for initial development
"""

import json
import csv
import os


def from_stream(data):
    x = json.loads(data)
    print(x["station"])


def from_file(filepath):
    # Open the json file
    file = open(filepath)
    j = json.load(file)
    file.close()

    # Create folder for station ID
    create_folder(str(j["station"]))

    measurement = j["measurement"]
    filepath = "json-testfiles/" + str(j["station"]) + "/" + measurement["date"] + ".csv"

    add_to_csv(measurement, filepath)


def create_folder(station_id):
    path = "json-testfiles/" + station_id
    permissions = 0o755
    if not os.path.isdir(path):
        os.mkdir(path, permissions)


def add_to_csv(measurement, file):

    newfile = True

    if os.path.exists(file):
        newfile = False

    with open(file, "a", newline='') as csvfile:
        writer = csv.writer(csvfile, quoting=csv.QUOTE_MINIMAL)
        if newfile:
            writer.writerow(['time', 'date', 'temperature', 'humidity', 'dewpoint'])

        writer.writerow([measurement['time'],
                         measurement['date'],
                         measurement['temperature'],
                         measurement['humidity'],
                         measurement['dewpoint']])


from_file("json-testfiles/dataformat.json")

