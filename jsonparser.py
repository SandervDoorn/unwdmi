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
    os.mkdir(path, permissions)

measurement = j["measurement"]
csvpath = path + "/" + measurement["date"] + ".csv"
newfile = True

if os.path.exists(path + "/" + measurement["date"] + ".csv"):
    newfile = False

with open(csvpath, "a", newline='') as csvfile:
    writer = csv.writer(csvfile, quoting=csv.QUOTE_MINIMAL)
    if newfile:
        writer.writerow(['time', 'date', 'temperature', 'humidity', 'dewpoint'])
    writer.writerow([measurement['time'],
                     measurement['date'],
                     measurement['temperature'],
                     measurement['humidity'],
                     measurement['dewpoint']])
