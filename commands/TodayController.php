<?php

namespace app\commands;

use yii\console\Controller;
use yii\httpclient\Client;

/**
 * Odessa weather today.
 */
class TodayController extends Controller
{
    private $url = 'https://api.openweathermap.org/data/3.0/onecall?lat=46.4775&lon=30.7326&units=metric&lang=ru&exclude=hourly,current,minutely,alerts&appid=e4a95afdbea23d654da203bc4b9d6241';

    /**
     * Get data from API.
     * @return string|array
     */
    public function actionIndex()
    {
        $client = new Client(['transport' => 'yii\httpclient\CurlTransport']);
        
        $response = $client->get($this->url)->send();
        
        if ($response->isOk) {
            $data = [];
            foreach ($response->data['daily'] as $item) {
                if (date('Y-m-d', $item['dt']) == date('Y-m-d') ||
                    date('Y-m-d', $item['dt']) == date('Y-m-d', strtotime('tomorrow'))
                ) {
                    foreach ($item['temp'] as $key => $value) {
                        if ($key == 'max' || $key == 'min') {
                            continue;
                        }
                        $data[date('Y-m-d', $item['dt'])][$key] = [
                            'temp' => $value,
                            'sky' => $item['weather'][0]['description'],
                            'wind_speed' => $item['wind_speed'],
                            'humidity' => $item['humidity']
                        ];
                    }
                }
            }
            \Yii::$app->cache->set('pogoda-v-odesse', $data, 21600);
            $this->stdout(date('Y-m-d H:i:s') . '. Data saved.' . PHP_EOL);
        } else {
            $this->stderr(date('Y-m-d H:i:s') . '. Failed to retrieve data.' . PHP_EOL);
        }
    }
}
