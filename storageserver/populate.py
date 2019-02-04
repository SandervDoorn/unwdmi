import os
import csv
import datetime
import random

# ===========Create weatherstations directory===============
if not os.path.isdir('weather-stations'):
    os.mkdir('weather-stations')

# ===========Create folders for all weatherstations==========
with open('../src/weerstations.csv', 'r') as f:
    reader = csv.reader(f)
    print("Creating folders...")
    for row in reader:
        if len(row) != 0:
            if not os.path.isdir('weather-stations/' + row[0]):
                #if 'INSERT' in row[0]:
                #    break
                os.mkdir('weather-stations/' + row[0])

# =========Populating folders with csv files==========
subdirs = [x[1] for x in os.walk('weather-stations/')]
stationFolders = subdirs[0]

today = datetime.datetime.now()
dayToCreate = today.strftime('%d-%m-%Y')

print("Populating csv files...")
for station in stationFolders:
    measurements = 'weather-stations/' + station + '/' + dayToCreate + '.csv'
    averages = 'weather-stations/' + station + '/averages.csv'

    # Creating todays measurements
    if not os.path.exists(measurements):
        with open(measurements, 'w', newline='') as f:
            writer = csv.writer(f)
            writer.writerow(["time", "date", "temperature", "humidity", "dewpoint"])
            for x in range(100):
                time = datetime.datetime.now() - datetime.timedelta(minutes=x)
                strtime = time.strftime("%H:%M")
                writer.writerow([strtime, dayToCreate, random.randint(15, 25), random.randint(20, 60), random.randint(-5, 3)])

    # Creating averages
    if not os.path.exists(averages):
        with open(averages, 'w', newline='') as f:
            writer = csv.writer(f)
            writer.writerow(["date", "temperature", "humidity", "dewpoint"])
            for x in range(28):
                day = today - datetime.timedelta(x+1)
                dayToWrite = day.strftime('%d-%m-%Y')
                writer.writerow([dayToWrite, random.randint(15, 25), random.randint(20, 60), random.randint(-5, 3)])