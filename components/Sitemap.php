<?php
namespace grozzzny\sitemap\components;

use SimpleXMLElement;

class Sitemap
{

    /**
     * @var string $domain - хранит в себе домен с протоколом
     */
    public $domain = '';

    public $webroot = '';

    /**
     * Sitemap constructor.
     * @param $domain
     * @param $webroot
     */
    public function __construct($domain, $webroot)
    {
        $this->domain = $domain;
        $this->webroot = $webroot;
    }

    /**
     * Метод создает sitemap index
     *
     * @param $path - путь где нужно создать sitemap index
     */
    public function createNewSitemapIndex($path)
    {

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="/main-sitemap.xsl"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>');
        $xml->asXML($this->webroot . $path);

    }

    /**
     * Метод вставляет в sitemap index новую запись
     *
     * @param $patch - путь sitemapa о котором будет новая запись
     * @param $parent - путь sitemapa index в котором вносится запись
     * @param $lastmod - дата записи (Если не указать, то дата поставится текущего дня) // 2005-01-01
     */
    public function insertSitemapIndex($patch, $parent, $lastmod = null)
    {
        $lastmod = (empty($lastmod)) ? date("Y-m-d") : $lastmod;

        $xml = simplexml_load_file($this->webroot . $parent);
        $sitemap = $xml->addChild('sitemap');
        $sitemap->addChild('loc', $this->domain . $patch);
        $sitemap->addChild('lastmod', $lastmod);
        $xml->asXML($this->webroot . $parent);
    }

    /**
     * Метод проверяет в sitemap index запись
     *
     * @param $path - путь sitemapa поиска
     * @param $parent - путь sitemapa index в котором идет поиск
     *
     * @return bool
     */
    public function hasSitemapIndex($path, $parent)
    {
        $xml = simplexml_load_file($this->webroot . $parent);
        foreach ($xml->children() as $sitemap) {
            if ($sitemap->loc == $this->domain . $path) return true;
        }
        return false;
    }

    /**
     * Метод обновляет в sitemap index определенную запись
     *
     * @param $path - путь sitemapa о котором нужно обновить запись
     * @param $parent - путь sitemapa index в котором вносится запись
     * @param $lastmod - дата записи (Если не указать, то дата поставится текущего дня) // 2005-01-01
     */
    public function updateSitemapIndex($path, $parent, $lastmod = null)
    {

        $lastmod = (empty($lastmod)) ? date("Y-m-d") : $lastmod;

        $xml = simplexml_load_file($this->webroot . $parent);
        foreach ($xml->children() as $sitemap) {
            if ($sitemap->loc == $this->domain . $path) {
                $sitemap->lastmod = $lastmod;
            }
        }
        $xml->asXML($this->webroot . $parent);
    }

    /**
     * Метод удаляет в sitemap index определенную запись
     *
     * @param $path - путь sitemapa index о котором нужно удалить запись
     * @param $parent - путь sitemapa в котором удаляется запись
     */
    public function removeSitemapIndex($path, $parent)
    {

        $xml = simplexml_load_file($this->webroot . $parent);
        foreach ($xml->children() as $sitemap) {
            if ($sitemap->loc == $this->domain . $path) {
                unset($sitemap[0][0]);
            }
        }

        $xml->asXML($this->webroot . $parent);
    }

    /**
     * Метод создает sitemap
     *
     * @param $path - путь где нужно создать sitemap
     */
    public function createNewSitemap($path)
    {

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="/main-sitemap.xsl"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
        $xml->asXML($this->webroot . $path);

    }

    /**
     * Метод вставляет в sitemap новую запись
     *
     * @param $parent - путь sitemapa в котором вносится запись
     * @param $loc - url страницы
     * @param $lastmod - дата записи (Если не указать, то дата поставится текущего дня) // 2005-01-01
     * @param $changefreq - период индексации //always hourly daily weekly monthly yearly never
     * @param $priority - приоритет страницы от 0 до 1.
     */
    public function insertSitemap($parent, $loc, $lastmod, $changefreq, $priority)
    {
        $lastmod = (empty($lastmod)) ? date("Y-m-d") : $lastmod;

        $xml = simplexml_load_file($this->webroot . $parent);

        $urlset = $xml->addChild('url');
        $urlset->addChild('loc', $this->domain . $loc);
        $urlset->addChild('lastmod', $lastmod);
        $urlset->addChild('changefreq', $changefreq);
        $urlset->addChild('priority', $priority);

        $xml->asXML($this->webroot . $parent);
    }


    /**
     * Метод обновляет в sitemap определенную запись
     *
     * @param $parent - путь sitemapa в котором вносится запись
     * @param $loc - url страницы
     * @param $lastmod - дата записи (Если не указать, то дата поставится текущего дня) // 2005-01-01
     * @param $changefreq - период индексации //always hourly daily weekly monthly yearly never
     * @param $priority - приоритет страницы от 0 до 1.
     */
    public function updateSitemap($parent, $loc, $lastmod, $changefreq, $priority)
    {

        $lastmod = (empty($lastmod)) ? date("Y-m-d") : $lastmod;

        $xml = simplexml_load_file($this->webroot . $parent);

        foreach ($xml->children() as $url) {

            if ($url->loc == $this->domain . $loc) {
                $url->lastmod = $lastmod;
                $url->changefreq = $changefreq;
                $url->priority = $priority;
            }
        }
        $xml->asXML($this->webroot . $parent);

    }


    /**
     * Метод removeSitemap - удаляет в sitemap определенную запись
     *
     * @param $parent - путь sitemapa в котором удаляется запись
     * @param $loc
     */
    public function removeSitemap($parent, $loc)
    {

        $xml = simplexml_load_file($this->webroot . $parent);

        foreach ($xml->children() as $url) {
            if ($url->loc == $this->domain . $loc) {
                unset($url[0][0]);
            }
        }
        $xml->asXML($this->webroot . $parent);

    }


    /**
     * Метод generateSitemap - проходит по массиву $this->data_sitemap и создаем множество sitemapОВ по патерну.
     *
     * @param $data_sitemap - массив с данными
     * @param $path_pattern - паттерн пути новых sitemapОВ (/sitemaps/sitemap_user_ob_#KEY.xml), где #KEY ключ массивов
     * @param $parent - путь sitemapa index в котором вносятся записи
     */
    public function generateSitemap($data_sitemap, $path_pattern, $parent)
    {

        foreach ($data_sitemap AS $key_item => $value_item) {
            $lastmod = '1990-01-01';

            $path = preg_replace('/#KEY/i', $key_item, $path_pattern);

            $this->createNewSitemap($path);

            $xml = simplexml_load_file($this->webroot . $path);
            foreach ($value_item AS $url) {
                $urlset = $xml->addChild('url');
                $urlset->addChild('loc', $url['loc']);
                $urlset->addChild('lastmod', $url['lastmod']);
                $urlset->addChild('changefreq', $url['changefreq']);
                $urlset->addChild('priority', $url['priority']);

                $lastmod = (strtotime($url['lastmod']) < strtotime($lastmod)) ? $lastmod : $url['lastmod'];
            }
            $xml->asXML($this->webroot . $path);

            $this->insertSitemapIndex($path, $parent, $lastmod);
        }

    }


}