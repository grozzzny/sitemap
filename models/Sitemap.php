<?php
namespace grozzzny\sitemap\models;

use Yii;
use yii\data\BaseDataProvider;
use yii\easyii2\components\ActiveQuery;
use yii\easyii2\components\FastModel;
use yii\easyii2\components\FastModelInterface;
use yii\easyii2\helpers\WebConsole;
use yii\helpers\ArrayHelper;

/**
 * Class Sitemap
 * @package grozzzny\sitemap\models
 * @property int $id [int(11)]
 * @property string $loc [varchar(255)]
 * @property string $lastmod [int (11)]
 * @property string $changefreq [varchar(255)]
 * @property string $priority [decimal(11,1)]
 * @property bool $status [tinyint(1)]
 * @property int $order_num [int(11)]
 */
class Sitemap extends FastModel implements FastModelInterface
{
    const PRIMARY_MODEL = true;

    const CACHE_KEY = 'sitemap';

    const ORDER_NUM = false;


    const CHANGEFREQ_ALWAYS = 'always';
    const CHANGEFREQ_HOURLY = 'hourly';
    const CHANGEFREQ_DAILY = 'daily';
    const CHANGEFREQ_WEEKLY = 'weekly';
    const CHANGEFREQ_MONTHLY = 'monthly';
    const CHANGEFREQ_YEARLY = 'yearly';
    const CHANGEFREQ_NEVER = 'never';

    const PRIORITY_100 = 1;
    const PRIORITY_90 = 0.9;
    const PRIORITY_80 = 0.8;
    const PRIORITY_70 = 0.7;
    const PRIORITY_60 = 0.6;
    const PRIORITY_50 = 0.5;
    const PRIORITY_40 = 0.4;
    const PRIORITY_30 = 0.3;
    const PRIORITY_20 = 0.2;
    const PRIORITY_10 = 0.1;
    const PRIORITY_0 = 0;


    public static function tableName()
    {
        return 'gr_sitemap';
    }

    public function rules()
    {
        return [
            [['changefreq', 'loc'], 'string'],
            [['priority'], 'double'],
            ['changefreq', 'default', 'value' => self::CHANGEFREQ_MONTHLY],
            ['priority', 'default', 'value' => self::PRIORITY_50],
            ['status', 'default', 'value' => self::STATUS_OFF],
            [['order_num','lastmod'], 'integer'],
            [['loc', 'lastmod'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'loc' => Yii::t('app', 'Loc'),
            'lastmod' => Yii::t('app', 'Lastmod'),
            'changefreq' => Yii::t('app', 'Changefreq'),
            'priority' => Yii::t('app', 'Priority'),
            'status' => Yii::t('app', 'Status'),
            'order_num' => Yii::t('app', 'Sort Index'),
        ];
    }

    public static function getNameModel()
    {
        // TODO: Implement getNameModel() method.
        return Yii::t('app', 'Sitemap');
    }

    public static function getSlugModel()
    {
        // TODO: Implement getNameModel() method.
        return 'sitemap';
    }


    public static function queryFilter(ActiveQuery &$query, $get)
    {
        if(!empty($get['text'])){
            $query->andFilterWhere(['LIKE', 'loc', $get['text']]);
        }
    }

    public static function querySort(BaseDataProvider &$provider)
    {
        $provider->setSort([
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'attributes' => [
                'id',
                'lastmod',
                'changefreq',
                'priority',
                'loc',
                'status',
            ]
        ]);
    }

    public function getLastmod()
    {
        return self::lastmodFormat($this->lastmod);
    }

    public function getChangefreq()
    {
        return ArrayHelper::getValue(self::listChangefreq(), $this->changefreq, null);
    }

    public function getPriority()
    {
        return ArrayHelper::getValue(self::listPriority(), $this->priority, null);
    }

    public static function lastmodFormat($time)
    {
        return date('Y-m-d', $time);
    }

    public static function listChangefreq()
    {
        return [
            self::CHANGEFREQ_ALWAYS => Yii::t('app', 'Always'),
            self::CHANGEFREQ_HOURLY => Yii::t('app', 'Hourly'),
            self::CHANGEFREQ_DAILY => Yii::t('app', 'Daily'),
            self::CHANGEFREQ_WEEKLY => Yii::t('app', 'Weekly'),
            self::CHANGEFREQ_MONTHLY => Yii::t('app', 'Monthly'),
            self::CHANGEFREQ_YEARLY => Yii::t('app', 'Yearly'),
            self::CHANGEFREQ_NEVER => Yii::t('app', 'Never'),
        ];
    }

    public static function listPriority()
    {
        return [
            (string) self::PRIORITY_100 => '100%',
            (string) self::PRIORITY_90 => '90%',
            (string) self::PRIORITY_80 => '80%',
            (string) self::PRIORITY_70 => '70%',
            (string) self::PRIORITY_60 => '60%',
            (string) self::PRIORITY_50 => '50%',
            (string) self::PRIORITY_40 => '40%',
            (string) self::PRIORITY_30 => '30%',
            (string) self::PRIORITY_20 => '20%',
            (string) self::PRIORITY_10 => '10%',
        ];
    }


    public function afterSave($insert, $changedAttributes)
    {
        WebConsole::console()->runAction('sitemap/console/static-pages');
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}
