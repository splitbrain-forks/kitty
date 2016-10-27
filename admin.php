<?php
/**
 * DokuWiki Plugin kitty (Admin Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <gohr@cosmocode.de>
 */

// must be run within Dokuwiki
use dokuwiki\Form\Form;

if(!defined('DOKU_INC')) die();

class admin_plugin_kitty extends DokuWiki_Admin_Plugin {

    /**
     * @return int sort number in admin menu
     */
    public function getMenuSort() {
        return 150;
    }

    /**
     * @return bool true if only access for superuser, false is for superusers and moderators
     */
    public function forAdminOnly() {
        return true;
    }

    /**
     * Should carry out any processing required by the plugin.
     */
    public function handle() {

        $name = $_REQUEST['name'];
        $width = $_REQUEST['width'];
        $height = $_REQUEST['height'];

        if($name && $width && $height) {
            msg("Adding $name to database", 1);
            $sqlite = syntax_plugin_kitty::getDB();
            $sqlite->query("REPLACE INTO kittens (name, width, height) VALUES ('$name', $width, $height)");
        }
    }

    /**
     * Render HTML output, e.g. helpful text and a form
     */
    public function html() {
        ptln('<h1>'.$this->getLang('menu').'</h1>');

        $form = new Form(array('method'=>'post'));
        $form->addTextInput('name', 'Cat Name');
        $form->addTextInput('width', 'Width');
        $form->addTextInput('height', 'Height');
        $form->addButton('submit', 'Submit')->attr('type', 'submit');
        echo $form->toHTML();
    }
}

// vim:ts=4:sw=4:et:
