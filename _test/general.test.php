<?php
/**
 * General tests for the kitty plugin
 *
 * @group plugin_kitty
 * @group plugins
 */
class general_plugin_kitty_test extends DokuWikiTest {

    /**
     * Simple test to make sure the plugin.info.txt is in correct format
     */
    public function test_plugininfo() {
        $file = __DIR__.'/../plugin.info.txt';
        $this->assertFileExists($file);

        $info = confToHash($file);

        $this->assertArrayHasKey('base', $info);
        $this->assertArrayHasKey('author', $info);
        $this->assertArrayHasKey('email', $info);
        $this->assertArrayHasKey('date', $info);
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('desc', $info);
        $this->assertArrayHasKey('url', $info);

        $this->assertEquals('kitty', $info['base']);
        $this->assertRegExp('/^https?:\/\//', $info['url']);
        $this->assertTrue(mail_isvalid($info['email']));
        $this->assertRegExp('/^\d\d\d\d-\d\d-\d\d$/', $info['date']);
        $this->assertTrue(false !== strtotime($info['date']));
    }

    public function test_xhtmlrender(){
        $plugin = new syntax_plugin_kitty();
        $render = new Doku_Renderer_xhtml();

        $plugin->render('xhtml', $render, array(200,200));

        $this->assertContains('placekitten.com', $render->doc);
    }
}
