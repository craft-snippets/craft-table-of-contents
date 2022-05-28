<?php
/**
 * Table of contents plugin for Craft CMS 3.x
 *
 * This plugin generates a table of contents from HTML headers in text.
 *
 * @link      http://craftsnippets.com
 * @copyright Copyright (c) 2019 Piotr Pogorzelski
 */

namespace craftsnippets\tableofcontents;

use craftsnippets\tableofcontents\variables\TableOfContentsVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class TableOfContents
 *
 * @author    Piotr Pogorzelski
 * @package   TableOfContents
 * @since     1.0.0
 *
 */
class TableOfContents extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var TableOfContents
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('toc', TableOfContentsVariable::class);
            }
        );

        Craft::info(
            Craft::t(
                'table-of-contents',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
