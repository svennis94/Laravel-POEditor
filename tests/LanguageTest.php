<?php

namespace SeBuDesign\PoEditor\Test;

use SeBuDesign\PoEditor\PoEditor;

class LanguageTest extends TestCase
{
    /** @test */
    public function it_has_all_languages_synchronised()
    {
        $this->artisan('synchronise:translations');

        $poeditor = new PoEditor();
        foreach ($poeditor->languages() as $language) {
            $this->assertFileExists( resource_path('lang/' . $language['code'] . '.json') );
        }
    }
}
