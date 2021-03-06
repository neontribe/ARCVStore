<?php

namespace Tests;

use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param $selector string Selector string to find a bunch of elements
     * @param $text string String you're looking for
     * @param $pos int Postion in the returned element array you think the text will be.
     * @return $this
     */
    public function seeInElementAtPos($selector, $text, $pos)
    {
        $element_text = trim($this->crawler->filter($selector)->eq($pos)->text());
        $this->assertContains($text, $element_text);
        return $this;
    }
}
