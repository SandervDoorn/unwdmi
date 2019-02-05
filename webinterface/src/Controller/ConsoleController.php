<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 13:31
 */

namespace App\Controller;

use App\Request;
use App\Response;
use App\Service\Database;
use App\Service\UserService;
use PHPSocketIO\Socket;
use Workerman\Worker;
use PHPSocketIO\SocketIO;

class ConsoleController
{

    const HONDURAS_LATITUDE_START = 13;
    const HONDURAS_LATITUDE = 14.75;
    const HONDURAS_LATITUDE_END = 16.5;
    const HONDURAS_LATITUDE_RANGE = 1.75;

    const HONDURAS_LONGITUDE_START = -89.4;
    const HONDURAS_LONGITUDE = -86.3;
    const HONDURAS_LONGITUDE_END = -83.2;
    const HONDURAS_LONGITUDE_RANGE = 3.1;

    /**
     * @var SocketIO
     * */
    private $io;

    private $database;

    private $userService;


    public function __construct()
    {
        $this->database = new Database(
            'localhost',
            'weather-project',
            'weather',
            'weather-project'
        );
        $this->database->connect();

        $this->userService = new UserService($this->database);
    }

    public function index(Request $request, Response $response)
    {
        $this->io = new SocketIO($_SERVER['ARGUMENTS'][1]);
        $this->io->on('connection', [$this, 'socketInit']);

        global $argv;
        $argvOld = $argv;
        $argv = ['public/index.php', 'start'];


        while(true) {
            Worker::runAll();
        }

        $argv = $argvOld;

        return $response;
    }

    public function socketInit(Socket $socket)
    {
        echo "new connection: " . $socket->id . "!\n";

        $socket->on('get_station', $this->socket($socket, function($stationId) use ($socket) {
            echo "get_station\n";
            return $this->getStation($stationId);
        }));

        $socket->on('get_markers', $this->socket($socket, function() use ($socket) {
            echo "get_markers\n";

            return $this->getUSAMarkers() + $this->getHondurasMarkers() + $this->getOtherMarkers();
        }));

        $socket->on('get_usa_markers', $this->socket($socket, function() use ($socket) {
            echo "get_usa_markers\n";

            return $this->getUSAMarkers();
        }));

        $socket->on('get_honduras_markers', $this->socket($socket, function() use ($socket) {
            echo "get_honduras_markers\n";

            return $this->getHondurasMarkers();
        }));

        $socket->on('get_average_temperature', $this->socket($socket, function() use ($socket) {
            echo "get_average_temperature\n";
            return $this->getAverageTemperature();
        }));

        $socket->on('get_average_humidity', $this->socket($socket, function() use ($socket) {
            echo "get_average_humidity\n";
            return $this->getAverageHumidity();
        }));

        $socket->on('get_honduras_regions', $this->socket($socket, function() use ($socket) {
            echo "get_honduras_regions\n";
            $stations = $this->getStationsInfo();

            if ($stations == false) {
                throw new \Exception('Unable to load stations');
            }

            $availableStations = $this->getAvailableStations();

            $stationRegionArray = [
                'Honduras North' => [],
                'Honduras East' => [],
                'Honduras South' => [],
                'Honduras West' => [],
            ];

            foreach ($stations as $stationInfo) {
                if ($stationInfo['country'] != "'HONDURAS'") {
                    continue;
                }

                if (! in_array($stationInfo['id'], $availableStations)) {
                    continue;
                }

                try {
                    $stationData = $this->getStation($stationInfo['id']);
                } catch (\Exception $e) {
                    continue;
                }

                if ($stationInfo['lat'] >= self::HONDURAS_LATITUDE) {
                    /* NORTH */
                    $latDistance = abs($stationInfo['lat'] - self::HONDURAS_LATITUDE);
                    $latDistanceValue = $latDistance / self::HONDURAS_LATITUDE_RANGE;

                    if ($stationInfo['lng'] >= self::HONDURAS_LONGITUDE) {
                        /* EAST */
                        $lngDistance = abs($stationInfo['lng'] - self::HONDURAS_LONGITUDE);
                        $lgnDistanceValue = $lngDistance / self::HONDURAS_LONGITUDE_RANGE;

                        if ($lgnDistanceValue > $latDistanceValue) {
                            $type = 'Honduras East';
                        } else {
                            $type = 'Honduras North';
                        }
                    } else {
                        /* WEST */
                        $lngDistance = abs($stationInfo['lng'] - self::HONDURAS_LONGITUDE);
                        $lgnDistanceValue = $lngDistance / self::HONDURAS_LONGITUDE_RANGE;

                        if ($lgnDistanceValue > $latDistanceValue) {
                            $type = 'Honduras West';
                        } else {
                            $type = 'Honduras North';
                        }
                    }
                } else {
                    /* SOUTH */
                    $latDistance = abs($stationInfo['lat'] - self::HONDURAS_LATITUDE);
                    $latDistanceValue = $latDistance / self::HONDURAS_LATITUDE_RANGE;

                    if ($stationInfo['lng'] >= self::HONDURAS_LONGITUDE) {
                        /* EAST */
                        $lngDistance = abs($stationInfo['lng'] - self::HONDURAS_LONGITUDE);
                        $lgnDistanceValue = $lngDistance / self::HONDURAS_LONGITUDE_RANGE;

                        if ($lgnDistanceValue > $latDistanceValue) {
                            $type = 'Honduras East';
                        } else {
                            $type = 'Honduras South';
                        }
                    } else {
                        /* WEST */
                        $lngDistance = abs($stationInfo['lng'] - self::HONDURAS_LONGITUDE);
                        $lgnDistanceValue = $lngDistance / self::HONDURAS_LONGITUDE_RANGE;

                        if ($lgnDistanceValue > $latDistanceValue) {
                            $type = 'Honduras West';
                        } else {
                            $type = 'Honduras South';
                        }
                    }
                }

                $stationRegionArray[$type][] = $stationData;
            }

            $regionArray = [];
            foreach ($stationRegionArray as $region => $stations) {
                $temperatures = [];
                $humidities = [];
                foreach ($stations as $station) {
                    $temperatures[] = $station['temperature'];
                    $humidities[] = $station['humidity'];
                }

                if (count($temperatures)) {
                    $tempAvg = round(array_sum($temperatures)/count($temperatures), 1) . ' °';
                } else {
                    $tempAvg = '-';
                }

                if (count($humidities)) {
                    $humAvg = round(array_sum($humidities)/count($humidities), 1) . ' %';
                } else {
                    $humAvg = '-';
                }

                $regionArray[] = [
                    'region' => $region,
                    'temperature' => $tempAvg,
                    'humidity' => $humAvg,
                    'station_count' => count($stations)
                ];
            }

            return $regionArray;
        }));

        $socket->on('get_honduras_stations', $this->socket($socket, function() use ($socket) {
            echo "get_honduras_stations\n";
            $stations = $this->getStationsInfo();

            if ($stations == false) {
                throw new \Exception('Unable to load stations');
            }

            $availableStations = $this->getAvailableStations();

            $stationArray = [];

            foreach ($stations as $stationInfo) {
                if ($stationInfo['country'] != "'HONDURAS'") {
                    continue;
                }

                if (!in_array($stationInfo['id'], $availableStations)) {
                    continue;
                }

                $stationData = $this->getStation($stationInfo['id']);

                $stationArray[] = [
                    'id' => $stationInfo['id'],
                    'name' => $stationInfo['name'],
                    'temperature' => $stationData['temperature'],
                    'humidity' => $stationData['humidity'],
                    'datetime' => $stationData['date'] . ' ' . $stationData['time'],
                ];
            }

            return $stationArray;
        }));

        $socket->on('get_usa_stations', $this->socket($socket, function() use ($socket) {
            echo "get_honduras_stations\n";
            $stations = $this->getStationsInfo();

            if ($stations == false) {
                throw new \Exception('Unable to load stations');
            }

            $availableStations = $this->getAvailableStations();

            $stationArray = [];

            foreach ($stations as $stationInfo) {
                if ($stationInfo['country'] != "'UNITED STATES'") {
                    continue;
                }

                if (!in_array($stationInfo['id'], $availableStations)) {
                    continue;
                }

                $stationData = $this->getStation($stationInfo['id']);

                $stationArray[] = [
                    'id' => $stationInfo['id'],
                    'name' => $stationInfo['name'],
                    'temperature' => $stationData['temperature'],
                    'humidity' => $stationData['humidity'],
                    'datetime' => $stationData['date'] . ' ' . $stationData['time'],
                ];
            }

            return $stationArray;
        }));

        $socket->on('get_archive_day_report', $this->socket($socket, function($stationId) use ($socket) {
            echo "get_archive_day_report\n";

            $date = new \DateTime();
            $date->setTimezone(new \DateTimeZone('Europe/Amsterdam'));

            return @file_get_contents(__DIR__ . '/../../../storageserver/weather-stations/' . $stationId . '/' . $date->format('d-m-Y') . '.csv', 'r');
        }));

        $socket->on('get_archive_averages', $this->socket($socket, function($stationId) use ($socket) {
            echo "get_archive_averages\n";

            $date = new \DateTime();
            $date->setTimezone(new \DateTimeZone('Europe/Amsterdam'));

            return @file_get_contents(__DIR__ . '/../../../storageserver/weather-stations/' . $stationId . '/averages.csv', 'r');
        }));

    }

    public function getUSAMarkers()
    {
        $stations = $this->getStationsInfo();

        if ($stations == false) {
            throw new \Exception('Unable to load stationmarkers');
        }

        $availableStations = $this->getAvailableStations();

        $humidityArray = [];
        foreach ($stations as $stationInfo) {
            if ($stationInfo['country'] != "'UNITED STATES'") {
                continue;
            }

            if (! in_array($stationInfo['id'], $availableStations)) {
                continue;
            }

            try {
                $stationData = $this->getStation($stationInfo['id']);
            } catch (\Exception $e) {
                continue;
            }

            if ($stationData == false) {
                continue;
            }

            $humidityArray[$stationData['humidity'] . $stationInfo['id']] = $stationInfo;
        }

        ksort($humidityArray);
        $relevantStations = array_slice($humidityArray, 0, 15);

        $data = [];
        foreach ($relevantStations as $station) {
            $data[$station['id']] = [
                'latLng' => [$station['lat'], $station['lng']],
                'name' => $station['name']
            ];
        }

        return $data;
    }

    public function getHondurasMarkers()
    {
        $stations = $this->getStationsInfo();

        if ($stations == false) {
            throw new \Exception('Unable to load stationmarkers');
        }

        $availableStations = $this->getAvailableStations();

        $stationArray = [];
        foreach ($stations as $stationInfo) {
            if ($stationInfo['country'] != "'HONDURAS'") {
                continue;
            }

            if (! in_array($stationInfo['id'], $availableStations)) {
                continue;
            }

            try {
                $this->getStation($stationInfo['id']);
            } catch (\Exception $e) {
                continue;
            }

            $stationArray[] = $stationInfo;
        }

        $data = [];
        foreach ($stationArray as $station) {
            $data[$station['id']] = [
                'latLng' => [$station['lat'], $station['lng']],
                'name' => $station['name']
            ];
        }

        return $data;
    }

    public function getOtherMarkers()
    {
        $stations = $this->getStationsInfo();

        if ($stations == false) {
            throw new \Exception('Unable to load stationmarkers');
        }

        $stationArray = [];
        foreach ($stations as $stationInfo) {
            if (in_array($stationInfo['country'], ["'HONDURAS'", "'UNITED STATES'"])) {
                continue;
            }

            $stationArray[] = $stationInfo;
        }

        $data = [];
        foreach ($stationArray as $station) {
            $data[$station['id']] = [
                'latLng' => [$station['lat'], $station['lng']],
                'name' => $station['name']
            ];
        }

        return $data;
    }

    public function getAvailableStations()
    {
        $stations = scandir(__DIR__ . '/../../../storageserver/weather-stations/');

        foreach ($stations as $key => $station) {
            if ($station == '.' || $station == '..') {
                unseT($stations[$key]);
            }
        }

        return $stations;
    }

    public function getStation($stationId)
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Amsterdam'));

        $csvData = @file_get_contents(__DIR__ . '/../../../storageserver/weather-stations/' . $stationId . '/' . $date->format('d-m-Y') . '.csv', 'r');

        if ($csvData == null || $csvData == false) {
            throw new \Exception('Station not available.');
        }

        $lines  = explode(PHP_EOL, $csvData);
        $properties = str_getcsv(array_shift($lines));

        while(end($lines) == '')
        {
            array_pop($lines);
        }

        $values = str_getcsv(end($lines));

        $station = [];
        foreach ($values as $key => $value) {
            $station[$properties[$key]] = $value;
        }

        return $station;
    }

    public function getAverageTemperature()
    {
        $availableStations = $this->getAvailableStations();
        $stationsInfo = $this->getStationsInfo();

        $values = [];
        foreach ($stationsInfo as $stationInfo) {
            if ($stationInfo['country'] != "'HONDURAS'") {
                continue;
            }

            if (!in_array($stationInfo['id'], $availableStations)) {
                continue;
            }

            try {
                $station = $this->getStation($stationInfo['id']);
            } catch (\Exception $e) {
                continue;
            }

            $values[] = $station['temperature'];
        }

        if (count($values) > 0) {
            return round(array_sum($values) / count($values), 1);
        }

        return '-';
    }

    public function getAverageHumidity()
    {
        $availableStations = $this->getAvailableStations();
        $stationsInfo = $this->getStationsInfo();

        $values = [];
        foreach ($stationsInfo as $stationInfo) {
            if ($stationInfo['country'] != "'HONDURAS'") {
                continue;
            }

            if (!in_array($stationInfo['id'], $availableStations)) {
                continue;
            }

            try {
                $station = $this->getStation($stationInfo['id']);
            } catch (\Exception $e) {
                continue;
            }

            $values[] = $station['humidity'];
        }

        if (count($values) > 0) {
            return round(array_sum($values) / count($values), 1);
        }

        return '-';
    }

    public function getStationsInfo()
    {
        $csvData = @file_get_contents(__DIR__ . '/../../data/stations.csv', 'r');

        if ($csvData == null) {
            return false;
        }

        $lines  = explode(PHP_EOL, $csvData);
        $properties = str_getcsv(array_shift($lines));

        $stations = [];
        foreach ($lines as $line) {
            $data = [];
            foreach (str_getcsv($line) as $key => $value) {
                $data[$properties[$key]] = $value;
            }
            $stations[] = $data;
        }

        return $stations;
    }

    public function socket(Socket $socket, callable $event)
    {
        return function ($data) use ($socket, $event) {
            list($token, $requestId) = $data;

            if (! $this->userService->authorize($token)) {
                $socket->emit('result', [
                    'code' => 403,
                    'error' => 'Unauthorized',
                    'requestId' => $requestId
                ]);

                return;
            }

            try {
                if (isset($data[2])){
                    $result = call_user_func_array($event, $data[2]);
                } else {
                    $result = $event();
                }
            } catch (\Exception $e) {
                $socket->emit('result', [
                    'error' => $e->getMessage(),
                    'requestId' => $requestId
                ]);

                return;
            }

            if ($result === false) {
                $socket->emit('result', [
                    'error' => 'No result',
                    'requestId' => $requestId
                ]);

                return;
            }

            $socket->emit('result', [
                'result' => $result,
                'requestId' => $requestId
            ]);
        };
    }

}