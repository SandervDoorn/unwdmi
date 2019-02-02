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
        echo "new connection!" . $socket->id . "\n";
        $socket->on('get_station', function($data) use ($socket) {
            echo "get_station\n";
            list($token, $requestId, $stationId) = $data;

            if (! $this->userService->authorize($token)) {
                $socket->emit('result', [
                    'command' => 'get_station',
                    'error' => 'Unauthorized',
                    'requestId' => $requestId
                ]);

                return;
            }

            $data = $this->getStation($stationId);

            if ($data == false) {
                $socket->emit('result', [
                    'command' => 'get_station',
                    'error' => 'Station not available.',
                    'requestId' => $requestId
                ]);

                return;
            }

            $socket->emit('result', [
                'command' => 'get_station',
                'result' => $data,
                'requestId' => $requestId
            ]);
        });

        $socket->on('get_usa_markers', function($data) use ($socket) {
            echo "get_usa_markers\n";
            list($token, $requestId) = $data;

            if (! $this->userService->authorize($token)) {
                $socket->emit('result', [
                    'command' => 'get_station',
                    'error' => 'Unauthorized',
                    'requestId' => $requestId
                ]);

                return;
            }

            $stations = $this->getStationsInfo();

            if ($stations == false) {
                $socket->emit('result', [
                    'command' => 'get_station',
                    'error' => 'Unable to load stationmarkers',
                    'requestId' => $requestId
                ]);

                return;
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

                $stationData = $this->getStation($stationInfo['id']);

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

            $socket->emit('result', [
                'command' => 'get_usa_markers',
                'result' => $data,
                'requestId' => $requestId
            ]);
        });

        $socket->on('get_honduras_markers', function($data) use ($socket) {
            echo "get_honduras_markers\n";
            list($token, $requestId) = $data;

            if (! $this->userService->authorize($token)) {
                $socket->emit('result', [
                    'command' => 'get_station',
                    'error' => 'Unauthorized',
                    'requestId' => $requestId
                ]);

                return;
            }

            $stations = $this->getStationsInfo();

            if ($stations == false) {
                $socket->emit('result', [
                    'command' => 'get_station',
                    'error' => 'Unable to load stationmarkers',
                    'requestId' => $requestId
                ]);

                return;
            }

            $availableStations = $this->getAvailableStations();

            $humidityArray = [];
            foreach ($stations as $stationInfo) {
                if ($stationInfo['country'] != "'HONDURAS'") {
                    continue;
                }

                if (! in_array($stationInfo['id'], $availableStations)) {
                    continue;
                }

                $stationData = $this->getStation($stationInfo['id']);

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

            $socket->emit('result', [
                'command' => 'get_honduras_markers',
                'result' => $data,
                'requestId' => $requestId
            ]);
        });

        $socket->on('get_average_temperature', function($data) use ($socket) {
            echo "get_average_temperature\n";
            list($token, $requestId) = $data;

            if (! $this->userService->authorize($token)) {
                $socket->emit('result', [
                    'command' => 'get_station',
                    'error' => 'Unauthorized',
                    'requestId' => $requestId
                ]);

                return;
            }

            $socket->emit('result', [
                'command' => 'get_average_temperature',
                'result' => $this->getAverageTemperature(),
                'requestId' => $requestId
            ]);
        });

        $socket->on('get_average_humidity', function($data) use ($socket) {
            echo "get_average_humidity\n";
            list($token, $requestId) = $data;

            if (! $this->userService->authorize($token)) {
                $socket->emit('result', [
                    'command' => 'get_station',
                    'error' => 'Unauthorized',
                    'requestId' => $requestId
                ]);

                return;
            }

            $socket->emit('result', [
                'command' => 'get_average_humidity',
                'result' => $this->getAverageHumidity(),
                'requestId' => $requestId
            ]);
        });
    }

    public function getAvailableStations()
    {
        $stations = scandir(__DIR__ . '/../../../storageserver/json-testfiles/');

        foreach ($stations as $key => $station) {
            if ($station == '.' || $station == '..') {
                unseT($stations[$key]);
            }
        }

        return $stations;
    }

    public function getStation($stationId)
    {
        $csvData = @file_get_contents(__DIR__ . '/../../../storageserver/json-testfiles/' . $stationId . '/' . date('d-m-y') . '.csv', 'r');

        if ($csvData == null) {
            return false;
        }

        $lines  = explode(PHP_EOL, $csvData);
        $properties = str_getcsv(array_shift($lines));
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

        $values = [];
        foreach ($availableStations as $availableStation) {
            $station = $this->getStation($availableStation);
            $values[] = $station['temperature'];
        }

        return array_sum($values) / count($values);
    }

    public function getAverageHumidity()
    {
        $availableStations = $this->getAvailableStations();

        $values = [];
        foreach ($availableStations as $availableStation) {
            $station = $this->getStation($availableStation);
            $values[] = $station['humidity'];
        }

        return array_sum($values) / count($values);
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

}