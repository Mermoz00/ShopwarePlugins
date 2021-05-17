<?php

namespace SPKCartAlsoBought;

use Shopware\Components\Plugin;

/**
 * This plugin also adds purchased items to the cart.
 *
 * @package SPKCartAlsoBought
 */
class SPKCartAlsoBought extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onFrontendPostDispatch',
            'Theme_Compiler_Collect_Plugin_Less' => 'addLessFiles',
            'Theme_Compiler_Collect_Plugin_Javascript' => 'addJsFiles',
        ];
    }

    private $debug = false;

    /**
     * @param \Enlight_Event_EventArgs $args
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function addLessFiles(\Enlight_Event_EventArgs $args)
    {
        $less = new \Shopware\Components\Theme\LessDefinition([], [__DIR__ . '/Resources/frontend/less/all.less']);
        return new \Doctrine\Common\Collections\ArrayCollection([$less]);
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function addJsFiles(\Enlight_Event_EventArgs $args)
    {
        $jsFiles = [__DIR__ . '/Resources/frontend/js/jquery.main.js'];
        return new \Doctrine\Common\Collections\ArrayCollection($jsFiles);
    }


    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onFrontendPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        // get config from backend
        $shop = $args->getShop();
        $config = Shopware()->Container()->get('shopware.plugin.cached_config_reader')->getByPluginName($this->getName(), $shop);

        $view = $args->getSubject()->View();
        $view->addTemplateDir($this->getPath() . '/Resources/views');
        if ($view->sBasket) {
            $tmp = [];
            $ids = [];
            //collect ids
            foreach ($view->sBasket["content"] as $sBasketItem) {
                array_push($ids, $sBasketItem["articleID"]);
            }

            $this->debug = (boolean)$config['logging'];

            //get bought too
            $tmp = array_merge($tmp, $this->getBoughtToo($ids));

            //get similar
            if (count($tmp) == 0) {
                $tmp = array_merge($tmp, $this->getSimilarShown($ids));
            }

            $articles = [];
            $i = 0;
            $maxProducts = (int)$config['maxProducts'];

            foreach ($tmp as $articleData) {
                $data = Shopware()->Modules()->Articles()->sGetPromotionById('fix', 0, (int)$articleData['id']);

                if ($i + 1 > $maxProducts) {
                    break;
                }

                if (!empty($data)) {
                    $articles[] = $data;
                }

                $i++;
            }

            $view->assign('alsoBoughtArticles', $articles);
        }

        // create array with all variables from plugin-config
        $variables = [];
        foreach ($config as $key => $value) {

            if ($key == "crossSellingInCart") {
                if ($value == "Ja" || $value == true) {
                    $variables[$key] = true;
                } else {
                    $variables[$key] = false;
                }
            }
            if ($key == "crossSellingInConfirmTemplate") {
                if ($value == "Ja" || $value == true) {
                    $variables[$key] = true;
                } else {
                    $variables[$key] = false;
                }
            }
            if (($key == "crossSellingInShippingPaymentTemplate")) {
                if ($value == "Ja" || $value == true) {
                    $variables[$key] = true;
                } else {
                    $variables[$key] = false;
                }
            }
            if (($key == "crossSellingLastSeen")) {
                if ($value == "Ja" || $value == true) {
                    $variables[$key] = true;
                } else {
                    $variables[$key] = false;
                }
            }
        }

        $view->assign('variables', $variables);
    }

    /**
     * Get articles that bought in combination with last added product to
     * display on cart page
     *
     * @param int $articleID
     * @return array
     */
    public function getBoughtToo($articleIDs)
    {
        return $this->sGetAlsoBoughtArticles($articleIDs, Shopware()->Modules()->Basket()->sGetBasketIds());
    }

    public function sGetAlsoBoughtArticles($articleIDs, $blacklist, $limit = 100)
    {
        if (empty($limit)) {
            $limit = 100;
        }

        $limit = (int)$limit;
        $where = '';

        if (!empty($blacklist)) {
            $where = Shopware()->Db()->quote($blacklist);
            if (!empty($where)) {
                $where = ' AND alsoBought.related_article_id NOT IN (' . $where . ') ';
            }
        }

        $articleIDsQuoted = Shopware()->Db()->quote($articleIDs);

        $sql = "SELECT DISTINCT
                alsoBought.sales as sales,
                alsoBought.related_article_id as id,
                detail.ordernumber as `number`
            FROM s_articles_also_bought_ro alsoBought
                INNER JOIN s_articles articles
                    ON  alsoBought.related_article_id = articles.id
                    AND articles.active = 1
                INNER JOIN s_articles_details detail
                    ON detail.id = articles.main_detail_id
                INNER JOIN s_articles_categories_ro articleCategories
                    ON  alsoBought.related_article_id = articleCategories.articleID
            WHERE alsoBought.article_id IN ($articleIDsQuoted)
            $where
            ORDER BY alsoBought.sales DESC, alsoBought.related_article_id DESC
            LIMIT $limit
        ";

        if ($this->debug) {
            Shopware()->PluginLogger()->error('SPK Cart Also Bought', ["sql" => $sql]);
        }
        return Shopware()->Db()->fetchAll($sql, []);
    }

    public function sGetSimilaryShownArticles($articleIds, $blacklist, $limit = 100)
    {
        if (empty($limit)) {
            $limit = 100;
        }
        $limit = (int)$limit;

        $where = '';
        if (!empty($this->sBlacklist)) {
            $where = Shopware()->Db()->quote($blacklist);
            $where = 'AND similarShown.related_article_id NOT IN (' . $where . ')';
        }

        $articleIdsQuoted = Shopware()->Db()->quote($articleIds);

        $sql = "
            SELECT
                 similarShown.viewed as hits,
                 similarShown.related_article_id as id,
                 detail.ordernumber as `number`
            FROM s_articles_similar_shown_ro as similarShown
              INNER JOIN s_articles as a
                ON  a.id = similarShown.related_article_id
                AND a.active = 1
              INNER JOIN s_articles_details as detail
                ON detail.id = a.main_detail_id
            WHERE similarShown.article_id in ($articleIdsQuoted)
            $where
            GROUP BY similarShown.viewed, similarShown.related_article_id
            ORDER BY similarShown.viewed DESC, similarShown.related_article_id DESC
            LIMIT $limit";

        if ($this->debug) {
            Shopware()->PluginLogger()->error('SPK Cart Also Bought', ["sql" => $sql]);
        }

        return Shopware()->Db()->fetchAll($sql);
    }

    /**
     * Get similar shown products to display in ajax add dialog
     *
     * @param int $articleIDs
     * @return array
     */
    public function getSimilarShown($articleIDs)
    {
        return $this->sGetSimilaryShownArticles($articleIDs, Shopware()->Modules()->Basket()->sGetBasketIds());
    }
}
