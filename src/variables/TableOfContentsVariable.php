<?php
/**
 * Table of contents plugin for Craft CMS 3.x
 *
 * This plugin generates a table of contents from HTML headers in text.
 *
 * @link      http://craftsnippets.com
 * @copyright Copyright (c) 2019 Piotr Pogorzelski
 */

namespace craftsnippets\tableofcontents\variables;

use craftsnippets\tableofcontents\models\TableOfContentsModel;
use craft\helpers\StringHelper;
use craft\services\Plugins;
use Exception;

/**
 * @author    Piotr Pogorzelski
 * @package   TableOfContents
 * @since     1.0.0
 */
class TableOfContentsVariable
{
    // Public Methods
    // =========================================================================


    public function getLinks($html = null, string $tags = 'h1,h2,h3', string $language = null)
    {    

        $plugins = new Plugins();
        if(!$plugins->isPluginEnabled('anchors')){
            throw new Exception('To use Table of contents, you must install Anchors plugin');
        }

        if($html){
            
            $tags = StringHelper::split($tags);
            preg_match_all( '/<('.implode('|', $tags).')([^>]*)>(.+?)<\/\1>/', $html, $headers, PREG_SET_ORDER);

            $links = [];
            foreach ($headers as $index => $header) {
                $toc = new TableOfContentsModel();
                $toc->text  = $header[3];
                $toc->id    = \craft\anchors\Plugin::getInstance()->parser->generateAnchorName($header[3], $language);

                $level = array_search($header[1], $tags) + 1;
                // if header with level 3 is directly after header with level 1, header with level 3 must be changed to level 2 for {% nav %} tag to function properly
                if($index -1 >= 0 && $links[$index -1]['level'] < $level && $level - $links[$index -1]['level'] > 1){
                    $level = $links[$index -1]['level'] + 1;
                }
                $toc->level = $level;
                $links[] = $toc;
            }

            return $links;

        }
    }
}
