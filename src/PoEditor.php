<?php

namespace SeBuDesign\PoEditor;

use \GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;

class PoEditor
{
    /**
     * The API token of POEditor
     *
     * @var string
     */
    protected $apiToken;

    /**
     * The project id of POEditor
     *
     * @var integer
     */
    protected $projectId;

    public function __construct($projectId = null, $apiToken = null)
    {
        $this->projectId = (is_null($projectId) ? config('poeditor.project_id') : $projectId);
        $this->apiToken = (is_null($apiToken) ? config('poeditor.api_token') : $apiToken);
    }

    /**
     * Get all the languages
     *
     * return array
     *
     * @throws PoEditorException
     */
    public function languages()
    {
        $languages = $this->apiData('languages/list', ['id' => $this->projectId]);

        return data_get($languages, 'languages', []);
    }

    /**
     * Get all the terms
     *
     * @return array
     *
     * @throws PoEditorException
     */
    public function terms($language)
    {
        $terms = $this->apiData('terms/list', ['id' => $this->projectId, 'language' => $language]);

        return data_get($terms, 'terms', []);
    }

    /**
     * Perform the API request and return the data
     *
     * @param string $path
     * @param array $data
     *
     * @return array
     *
     * @throws PoEditorException
     */
    protected function apiData($path, $data = [])
    {
        $data = array_merge(
            [
                'api_token' => $this->apiToken,
            ],
            $data
        );

        $client = new HttpClient();
        $response = $client->post('https://api.poeditor.com/v2/' . $path, [
            RequestOptions::FORM_PARAMS => $data
        ]);

        $response = json_decode((string) $response->getBody(), true);

        if (data_get($response, 'response.status') === 'fail') {
            throw new PoEditorException(data_get($response, 'response.message', 'Unknown POEditor error'), 500);
        }

        return data_get($response, 'result', []);
    }
}
