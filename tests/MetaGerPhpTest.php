<?php

class MetaGerPhpTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
        $this->visit('/')
            ->see('MetaGer')
            ->dontSee('Google');
        $this->visit('/')
            ->click('Datenschutz')
            ->seePageIs('/datenschutz');
        $this->visit('/')
            ->type('test', 'eingabe')
            ->press('submit')
            ->seePageIs('/meta/meta.ger3');
    }
}
