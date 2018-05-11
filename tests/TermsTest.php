<?php

namespace SeBuDesign\PoEditor\Test;

use SeBuDesign\PoEditor\PoEditor;

class TermsTest extends TestCase
{
    /** @test */
    public function it_has_all_terms_synchronised()
    {
        $this->artisan('synchronise:translations');

        $poeditor = new PoEditor();
        foreach ($poeditor->languages() as $language) {
            \App::setLocale($language['code']);

            $term = collect($poeditor->terms($language['code']))->first();

            $key = $term['term'];
            $content = data_get($term, 'translation.content');

            if (is_array($content)) {
                $this->assertEquals($content['one'], trans_choice($key, 1));
                $this->assertEquals($content['other'], trans_choice($key, 2));
            } else {
                $this->assertEquals($content, __($key));
            }
        }
    }
}
