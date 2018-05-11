<?php

namespace SeBuDesign\PoEditor\Commands;

use Illuminate\Console\Command;
use SeBuDesign\PoEditor\PoEditor;

class SynchroniseTranslations extends Command
{
    protected $signature = 'synchronise:translations 
                {project? : The project id of POEditor}';

    protected $description = 'Synchronise translations from POEditor';

    public function handle()
    {
        $poeditor = new PoEditor($this->argument('project'));

        foreach ($poeditor->languages() as $language) {
            $languageFile = resource_path('lang/' . $language['code'] . '.json');

            $terms = collect($poeditor->terms($language['code']))
                // Pluck the translation content and set the term as the key
                ->pluck('translation.content', 'term')
                ->map(function ($content) {
                    // If the translation content is an array it is plural and has a one and other key
                    if (is_array($content)) {
                        return "{$content['one']}|{$content['other']}";
                    }

                    return $content;
                })
                ->toJson();

            // If an old file exists remove it
            if (\File::exists($languageFile)) {
                \File::delete($languageFile);
            }

            // Write the new file
            \File::put($languageFile, $terms);
        }

        $this->info("Translations synchronised");
    }
}
