<?php

/* @var yii\web\View $this */
/* @var array $detail */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = Yii::t('app', 'Water temperature in Odessa for September 2023');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Yii::t('app', 'What is the sea water temperature in September in Odessa?') ?></h1>

    <?= yii\widgets\DetailView::widget([
        'model' => $detail,
        'attributes' => [
            [
                'label' => Yii::t('app', 'Water temperature today'),
                'value' => function ($model) {
                    return $model['today'] . ' °C';
                }
            ],
            [
                'label' => Yii::t('app', 'Water temperature tomorrow'),
                'value' => function ($model) {
                    return $model['tomorrow'] . ' °C';
                }
            ],
            [
                'label' => Yii::t('app', 'Average water temperature in September'),
                'value' => function ($model) {
                    return $model['avg'] . ' °C';
                }
            ],
            [
                'label' => Yii::t('app', 'The warmest water will be'),
                'value' => function ($model) {
                    return Yii::t('app', '{max} September', ['max' => $model['max']]);
                }
            ],
            [
                'label' => Yii::t('app', 'The coldest water will be'),
                'value' => function ($model) {
                    return Yii::t('app', '{min} September', ['min' => $model['min']]);
                }
            ],
        ]
    ]) ?>
    
    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}',
        'columns' => [
            [
                'label' => Yii::t('app', 'Date'),
                'value' => function ($model, $key, $index) {
                    return Yii::t('app', '{min} September', ['min' => $key]);
                }
            ],
            [
                'label' => Yii::t('app', 'Water temperature in {year}', ['year' => 2023]),
                'value' => function ($model, $key, $index) {
                    return $model[2023] . ' °C';
                }
            ],
            [
                'label' => Yii::t('app', 'Water temperature in {year}', ['year' => 2022]),
                'value' => function ($model, $key, $index) {
                    return $model[2022] . ' °C';
                }
            ]
        ]
    ]) ?>
</div>
