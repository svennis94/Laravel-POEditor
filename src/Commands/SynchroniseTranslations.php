<?php

namespace SeBuDesign\PoEditor\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use SeBuDesign\PoEditor\Events\TranslationsSynchronized;
use SeBuDesign\PoEditor\PoEditor;

class SynchroniseTranslations extends Command
{
    protected $signature = 'synchronise:translations 
                {project? : The project id of POEditor}';

    protected $description = 'Synchronise translations from POEditor';

    public function handle()
    {
        $poeditor = new PoEditor($this->argument('project'));

        $locales = collect();
        foreach ($poeditor->languages() as $language) {
            $terms = collect($poeditor->terms($language['code']))
                // Pluck the translation content and set the term as the key
                ->pluck('translation.content', 'term')
                ->map(function ($content) {
                    // If the translation content is an array it is plural and has a one and other key
                    if (is_array($content)) {
                        return "{$content['one']}|{$content['other']}";
                    }

                    return $content;
                });

            $this->updateFile(
                resource_path('lang/' . $language['code'] . '.json'),
                $terms
            );

            $locales->push([
                'name' => $language['name'],
                'code' => $language['code'],
            ]);
        }

        $this->updateFile(
            resource_path('lang/locales.json'),
            $locales
        );

        // Create an event to clear translations cache or something else
        event(new TranslationsSynchronized());

        $this->info("Translations synchronised");
    }

    protected function updateFile($file, $content)
    {
        // If an old file exists remove it
        if (\File::exists($file)) {
            \File::delete($file);
        }

        // If it is a collection create a json string of it
        if ($content instanceof Collection) {
            $content = $content->toJson();
        }

        // Write the new file
        \File::put($file, $content);
    }
}
