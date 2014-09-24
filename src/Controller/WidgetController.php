<?php
/**
 * AnimeDb package
 *
 * @package   AnimeDb
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriSimilarItemsWidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AnimeDb\Bundle\CatalogBundle\Entity\Item;

/**
 * Similar items widget
 *
 * @package AnimeDb\Bundle\ShikimoriSimilarItemsWidgetBundle\Controller
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class WidgetController extends Controller
{
    /**
     * API path for get similar items
     *
     * @var string
     */
    const PATH_SIMILAR_ITEMS = '/animes/#ID#/similar';

    /**
     * Cache lifetime 1 week
     *
     * @var integer
     */
    const CACHE_LIFETIME = 604800;

    /**
     * New items
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Item $item
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Item $item, Request $request)
    {
        /* @var $response \Symfony\Component\HttpFoundation\Response */
        $response = $this->get('cache_time_keeper')->getResponse($item->getDateUpdate(), self::CACHE_LIFETIME);
        /* @var $widget \AnimeDb\Bundle\ShikimoriWidgetBundle\Service\Widget */
        $widget = $this->get('anime_db.shikimori.widget');

        // get shikimori item id
        if (!($item_id = $widget->getItemId($item))) {
            return $response;
        }

        $list = $this->get('anime_db.shikimori.browser')
            ->get(str_replace('#ID#', $item_id, self::PATH_SIMILAR_ITEMS));
        // add Etag by list items
        $response->setEtag($widget->hash($list));

        // response was not modified for this request
        if ($response->isNotModified($request) || !$list) {
            return $response;
        }

        // build list item entities
        foreach ($list as $key => $item) {
            $list[$key] = $widget->getWidgetItem($widget->getItem($item['id']));
        }

        return $this->render(
            'AnimeDbShikimoriSimilarItemsWidgetBundle:Widget:index.html.twig',
            ['items' => $list],
            $response
        );
    }
}
