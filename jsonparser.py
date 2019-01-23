"""
This code might be moved to a different location. It is purely for initial development
"""

import json
import csv
import os

# Open the json file
file = open("json-testfiles/dataformat.json")
j = json.load(file)
file.close()

# Create folder for station ID
path = "json-testfiles/" + j["station"]
permissions = 0o755
if not os.path.isdir(path):
    print("Folder doesnt exist")
    print("Creating folder...")
    os.mkdir(path, permissions)

print("Folder found, loading data...")
measurement = j["measurement"]
csvpath = path + "/" + measurement["date"] + ".csv"
newfile = True

if os.path.exists(path + "/" + measurement["date"] + ".csv"):
    newfile = False

with open(csvpath, "a") as csvfile:
    writer = csv.writer(csvfile, quoting=csv.QUOTE_ALL)
    if newfile:
        writer.writerow(['time', 'date', 'temperature', 'humidity', 'dewpoint'])
    # TODO: writerow overwrites if file exists, change to add a line instead
    writer.writerow([measurement['time'],
                     measurement['date'],
                     measurement['temperature'],
                     measurement['humidity'],
                     measurement['dewpoint']])
