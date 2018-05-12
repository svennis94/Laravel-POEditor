<?php

namespace SeBuDesign\PoEditor\Test;

use SeBuDesign\PoEditor\PoEditor;

class LocalesTest extends TestCase
{
    /** @test */
    public function it_has_all_locales_synchronised()
    {
        $this->artisan('synchronise:translations');

        $poeditor = new PoEditor();

        // Get the locales file
        $locales = [];
        foreach ($poeditor->languages() as $language) {
            $this->assertFileExists( resource_path('lang/' . $language['code'] . '.json') );

            $locales[] = [
                'name' => $language['name'],
                'code' => $language['code'],
            ];
        }

        $this->assertJsonStringEqualsJsonFile(resource_path('lang/locales.json'), json_encode($locales));
    }
}
