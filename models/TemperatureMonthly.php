<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "temperature_monthly".
 *
 * @property int $id
 * @property int $day
 * @property int $month
 * @property int $year
 * @property int $temperature
 * @property int $created_at
 */
class TemperatureMonthly extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temperature_monthly';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp'  => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['day', 'month', 'year', 'temperature'], 'required'],
            [['day', 'month', 'year', 'temperature', 'created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'day' => 'Day',
            'month' => 'Month',
            'year' => 'Year',
            'temperature' => 'Temperature',
            'created_at' => 'Created At',
        ];
    }
    
    /**
     * Get data for Details table
     * @return array
     */
    public static function getDetails()
    {
        return [
            'today' => self::find()->select('temperature')
                ->where(['year' => date('Y'), 'month' => date('m'), 'day' => date('d')])->scalar(),
            'tomorrow' => self::find()->select('temperature')
                ->where(['year' => date('Y'), 'month' => date('m'), 'day' => date('d', strtotime("+1 day"))])->scalar(),
            'avg' => number_format(self::find()
                ->where(['year' => date('Y'), 'month' => date('m')])->average('temperature'), 2, '.', ' '),
            'max' => self::find()->select('day')
                ->where(['year' => date('Y'), 'month' => date('m')])->orderBy('temperature DESC')->limit(1)->scalar(),
            'min' => self::find()->select('day')
                ->where(['year' => date('Y'), 'month' => date('m')])->orderBy('temperature ASC')->limit(1)->scalar()
        ];
    }
    
    /**
     * Get data for table
     * @return yii\data\ArrayDataProvider
     */
    public static function getData()
    {
        $data = [];

        foreach (self::find()->select(['day', 'year', 'temperature'])->asArray()->all() as $item) {
            $data[$item['day']][$item['year']] = $item['temperature'];
        }
        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false
        ]);
    }
}
