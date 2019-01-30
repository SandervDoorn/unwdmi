import datetime
import os
import shutil
import configparser
import csv

config = configparser.ConfigParser()
config.read('config.ini')
rootdir = config['lacthosa']['filepath']


# ================Average the previous day================
yesterday = datetime.datetime.now() - datetime.timedelta(1)
dayToAverage = yesterday.strftime('%d-%m-%Y')

subdirs = [x[1] for x in os.walk(rootdir)]
stationFolders = subdirs[0]

# For each station directory:
for statdir in stationFolders:
    successFlag = True
    file = rootdir + statdir + '/' + dayToAverage + '.csv'
    avgfile = rootdir + statdir + '/averages.csv'
    if os.path.exists(file):
        temps = []
        hums = []
        dewps = []

        try:
            # Read yesterdays csv files and store all data in lists
            with open(file) as f:
                reader = csv.DictReader(f)
                for row in reader:
                    temps.append(float(row['temperature']))
                    hums.append(float(row['humidity']))
                    dewps.append(float(row['dewpoint']))

            # Check if avg file exists or create
            if not os.path.exists(avgfile):
                with open(avgfile, 'a', newline='') as csvfile:
                    writer = csv.writer(csvfile, quoting=csv.QUOTE_MINIMAL)
                    writer.writerow(['date', 'temperature', 'humidity', 'dewpoint'])

            # Open avg file and add averages
            with open(avgfile, 'a', newline='') as csvfile:
                writer = csv.writer(csvfile, quoting=csv.QUOTE_MINIMAL)
                writer.writerow([dayToAverage, sum(temps)/len(temps), sum(hums)/len(hums), sum(dewps)/len(dewps)])

        except (IOError, TypeError, KeyError):
            print("Error occured, preventing deletion")
            successFlag = False

        except ZeroDivisionError:
            print("File is empty, deleting...")

        if successFlag:
            os.remove(file)

    # ================Delete 28 days old averages================
    retentionDelta = datetime.datetime.now() - datetime.timedelta(28)
    dayToDelete = retentionDelta.strftime('%d-%m-%Y')
    temppath = rootdir + statdir + '/temp.csv'

    with open(avgfile, 'r', newline='') as f:
        # Create temporary file
        with open(temppath, 'w', newline='') as t:
            tempwriter = csv.writer(t)

            # Read rows in averages.csv and write to temp
            # Source https://stackoverflow.com/questions/29725932/deleting-rows-with-python-in-a-csv-file
            for row in csv.reader(f):
                if row[0] != dayToDelete:
                    tempwriter.writerow(row)
    shutil.copy(temppath, avgfile)
    os.remove(temppath)

