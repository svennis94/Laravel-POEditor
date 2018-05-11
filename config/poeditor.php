<?php

return [

    /*
     * The API token of POEditor can be found in your account settings in the
     * 'API Access' tab. Click on the little eye ball and copy the token. Put
     * the token in your .env file.
     */

    'api_token' => env('POEDITOR_API_TOKEN'),

    /*
     * The project id of POEditor can be found in your account settings in the
     * 'API Access' tab. Grab the project id and this package will take care of the rest.
     */

    'project_id' => env('POEDITOR_PROJECT_ID', 'YourPoEditorProjectId'),
];
