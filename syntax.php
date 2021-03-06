<?php
/**
 * DokuWiki Plugin kitty (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <gohr@cosmocode.de>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class syntax_plugin_kitty extends DokuWiki_Syntax_Plugin {
    /**
     * @return string Syntax mode type
     */
    public function getType() {
        return 'substition';
    }

    /**
     * @return string Paragraph type
     */
    public function getPType() {
        return 'block';
    }

    /**
     * @return int Sort order - Low numbers go before high numbers
     */
    public function getSort() {
        return 155;
    }

    /**
     * @return helper_plugin_sqlite
     */
    public static function getDB() {
        /** @var helper_plugin_sqlite $sqlite */
        $sqlite = plugin_load('helper', 'sqlite');
        $sqlite->init('kitty', __DIR__.'/db/');
        return $sqlite;
    }

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode Parser mode
     */
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\{\{kitty .+?}\}', $mode, 'plugin_kitty');
    }

    /**
     * Handle matches of the kitty syntax
     *
     * @param string $match The match of the syntax
     * @param int $state The state of the handler
     * @param int $pos The position in the document
     * @param Doku_Handler $handler The handler
     * @return array Data for the renderer
     */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        $name = trim(substr($match, 7, -2));
        $sqlite = self::getDB();
        $res = $sqlite->query('SELECT * FROM kittens WHERE name = ?', $name);
        if($res) {
            $row = $sqlite->res2row($res);
            $width = $row['width'];
            $height = $row['height'];
            $sqlite->res_close($res);
        } else {
            $width = 10;
            $height = 10;
        }

        return array($width, $height);
    }

    /**
     * Render xhtml output or metadata
     *
     * @param string $mode Renderer mode (supported modes: xhtml)
     * @param Doku_Renderer $renderer The renderer
     * @param array $data The data from the handler() function
     * @return bool If rendering was successful.
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        if($mode != 'xhtml') return false;

        if($this->getConf('grayscale')) {
            $g = 'g/';
        } else {
            $g = '';
        }

        list($width, $height) = $data;
        $renderer->doc .= '<img src="https://placekitten.com/' . $g . $width . '/' . $height .
            '" class="kitty" title="' . $this->getLang('cat') . '" />';

        return true;
    }
}

// vim:ts=4:sw=4:et:
