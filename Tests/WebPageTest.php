<?php

class WebPageTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers WebPage::fecth($url)
     */
    public function testWebPageFecth() {
        $wp = new WebPage();
        $wp->fecth('file://BBCNewsHomepageDummy.html');
        $this->assertTrue(true);
    }

    /**
     * @covers WebPage::fecth($url)
     * @expectedException RuntimeException
     */
    public function testWebPageFecthFailure() {
        $wp = new WebPage();
        $wp->fecth('file://BBCNewsOFF.html');
    }

    /**
     * @covers WebPage::loadDom()
     */
    public function testLoadDom() {
        $wp = new WebPage();
        $wp->fecth('file://BBCNewsHomepageDummy.html');
        $wp->loadDom();
        $this->assertInstanceOf('DOMDocument', $wp->dom);
    }

}
