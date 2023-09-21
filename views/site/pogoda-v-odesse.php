<?php

/* @var yii\web\View $this */
/* @var array $data */

use yii\data\ArrayDataProvider;

$this->title = Yii::t('app', 'Weather in Odessa for today and tomorrow');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <?php foreach ($data as $day => $info):
        $date = explode('-', $day); ?>
        <h2><?= Yii::t('app', 'Weather forecast for {week_day} (September {day}, {year})', [
                'week_day' => ($day == date('Y-m-d') ? Yii::t('app', 'today') : Yii::t('app', 'tomorrow')),
                'day' => $date[2],
                'year' => $date[0]
            ]) ?>
        </h2>
        <?= yii\grid\GridView::widget([
        'dataProvider' => new ArrayDataProvider(['allModels' => $info]),
        'layout' => '{items}',
        'columns' => [
            [
                'label' => Yii::t('app', 'Weather forecast for September {day}', ['day' => $date[2]]),
                'value' => function ($model, $key, $index) {
                    return Yii::t('app', $key);
                }
            ],
            [
                'label' => Yii::t('app', 'Air temperature °C'),
                'value' => function ($model) {
                    return $model['temp'] . ' °C';
                }
            ],
            [
                'label' => Yii::t('app', 'State of the sky'),
                'value' => function ($model) {
                    return $model['sky'];
                }
            ],
            [
                'label' => Yii::t('app', 'Wind speed'),
                'value' => function ($model) {
                    return $model['wind_speed'] . ' ' . Yii::t('app', 'm/s');
                }
            ],
            [
                'label' => Yii::t('app', 'Humidity'),
                'value' => function ($model) {
                    return $model['humidity'] . '%';
                }
            ]
        ]
    ]) ?>
    <?php endforeach ?>
</div>
