<?php
/**
 * Table of contents plugin for Craft CMS 3.x
 *
 * This plugin generates a table of contents from HTML headers in text.
 *
 * @link      http://craftsnippets.com
 * @copyright Copyright (c) 2019 Piotr Pogorzelski
 */

namespace craftsnippets\tableofcontents\models;

use craftsnippets\tableofcontents\TableOfContents;

use Craft;
use craft\base\Model;

/**
 * @author    Piotr Pogorzelski
 * @package   TableOfContents
 * @since     1.0.0
 */
class TableOfContentsModel extends Model
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $text;

    /**
     * @var int
     */
    public $level;

    /**
     * @var string
     */
    private $_hash;

    /**
     * @return string
     */
    public function getHash()
    {
        $this->_hash = '#'.$this->id;
        return $this->_hash;
    }
}
