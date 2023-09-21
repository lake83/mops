<?php

namespace app\commands;

use yii\console\Controller;
use yii\httpclient\Client;
use app\models\TemperatureMonthly;

/**
 * Odessa sea temperature in september.
 */
class MonthlyController extends Controller
{
    private $url = 'https://seatemperature.ru/monthly/odessa-odeska-oblast-ukraine-sea-temperature-in-september-6940';
    
    /**
     * Get data and save to BD.
     * @return string
     */
    public function actionIndex()
    {
        $client = new Client(['transport' => 'yii\httpclient\CurlTransport']);
        
        $response = $client->get($this->url)->send();
        
        if ($response->isOk) {
            $created = 0;
            
            try {
                $dom = new \DOMDocument('1.0', 'UTF-8');
                @$dom->loadHTML($response->content);
                $xpath = new \DomXPath($dom);

                foreach ($xpath->query('//tr[contains(@class, "godafatr2")]') as $item) {
                    foreach ([1 => 2023, 2 => 2022] as $key => $year) {
                        $row = $xpath->query('.//td[@class="godafatd2"]', $item);
                        $data = ['day' => $row->item(0)->nodeValue, 'month' => 9, 'year' => $year];
                        
                        if (!$model = TemperatureMonthly::find()->where($data)->one()) {
                            $model = new TemperatureMonthly($data);
                        }
                        $model->temperature = rtrim($row->item($key)->nodeValue, '°C');

                        if ($model->save()) {
                            $created++;
                        }
                    }
                }
                $this->stdout(date('Y-m-d H:i:s') . '. Rows created: ' . $created . PHP_EOL);
            } catch (\Exeption $e) {
                $this->stderr(date('Y-m-d H:i:s') . '. ' . $e->getMessage() . PHP_EOL);
            }
        } else {
            $this->stderr(date('Y-m-d H:i:s') . '. Failed to retrieve data.' . PHP_EOL);
        }
    }
}
