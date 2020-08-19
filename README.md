Sitemap module for yii2
==============================

## Installation guide
```bash
$ php composer.phar require grozzzny/sitemap "v2.0"
```

Add console config
```php
  'aliases' => [
        '@webroot' => '@app/web',
  ],
   'components' => [
          'urlManager' => [
              'enablePrettyUrl' => true,
              'showScriptName' => false,
              'hostInfo' => 'my-site.ru',
              'scriptUrl' => '',
              'baseUrl' => ''
          ],
      ],
  'modules' => [
        'sitemap' => [
            'class' => 'grozzzny\sitemap\SitemapModule',
            'domain' => 'https://my-site.ru',
            'generatedByLink' => 'https://pr-kenig.ru',
            'generatedByName' => 'PRkenig',
            'controllerMap' => [
                'console' => 'app\commands\SitemapController'
            ]
        ]
    ],
```

CRON
```bash
php yii sitemap/console/update
```

Example app\commands\SitemapController
```php
class SitemapController extends ConsoleController
{
    public $lastmodStaticPage = ''; // Y-m-d

    public $staticPages = [
        [
            'loc' => '/about', // /about
            'lastmod' => '2020-08-19', // Y-m-d
            'changefreq' => Sitemap::CHANGEFREQ_MONTHLY,
            'priority' => Sitemap::PRIORITY_60,
        ],
    ];

    protected function dataSitemap()
    {
        $this->generateArticles();
    }

    protected function generateArticles()
    {
        $models = AdminArticles::find()
            ->andWhere(['active' => true])
            ->all();

        foreach($models as $model){
            $this->data_sitemap['articles'][] = array(
                'loc'           => $model->link,
                'lastmod'       => Sitemap::lastmodFormat($model->updated_at),
                'changefreq'    => Sitemap::CHANGEFREQ_MONTHLY,
                'priority'      => Sitemap::PRIORITY_60,
            );
        }
    }
}
```