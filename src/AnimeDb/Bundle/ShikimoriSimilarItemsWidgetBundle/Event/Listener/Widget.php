<?php
/**
 * AnimeDb package
 *
 * @package   AnimeDb
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\ShikimoriSimilarItemsWidgetBundle\Event\Listener;

use AnimeDb\Bundle\AppBundle\Event\Widget\Get;
use AnimeDb\Bundle\CatalogBundle\Controller\ItemController;

/**
 * Widget
 *
 * @package AnimeDb\Bundle\ShikimoriSimilarItemsWidgetBundle\Event\Listener
 * @author  Peter Gribanov <info@peter-gribanov.ru>
 */
class Widget
{
    /**
     * Registr widget
     *
     * @param \AnimeDb\Bundle\AppBundle\Event\Widget\Get $event
     */
    public function onGetWidget(Get $event)
    {
        if ($event->getPlace() == ItemController::WIDGET_PALCE_BOTTOM) {
            $event->registr('AnimeDbShikimoriSimilarItemsWidgetBundle:Widget:index');
        }
    }
}