import datetime
import os
import configparser
import csv

config = configparser.ConfigParser()
config.read('config.ini')
rootdir = config['lacthosa']['filepath']


# Average the previous day and delete all measurements

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
            with open(avgfile, 'a') as csvfile:
                writer = csv.writer(csvfile, quoting=csv.QUOTE_MINIMAL)
                writer.writerow([dayToAverage, sum(temps)/len(temps), sum(hums)/len(hums), sum(dewps)/len(dewps)])

        except (IOError, TypeError, KeyError, ZeroDivisionError):
            successFlag = False

        if successFlag:
            os.remove(file)

