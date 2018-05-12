<?php

namespace SeBuDesign\PoEditor\Test;

use SeBuDesign\PoEditor\Events\TranslationsSynchronized;

class EventsTest extends TestCase
{
    /** @test */
    public function it_has_all_terms_synchronised()
    {
        $this->expectsEvents(TranslationsSynchronized::class);

        $this->artisan('synchronise:translations');
    }
}
