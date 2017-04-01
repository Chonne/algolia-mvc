<?php

namespace AlgoliaApp\Controller;

class FrontController extends Controller
{
    public function runHome()
    {
        $templatePath = $this->config['paths']['templates'] . '/' . 'search.php';

        $templateParams = [
            'algolia_applicationID' => $this->config['parameters']['algolia']['applicationID'],
            'algolia_apiKey' => $this->config['parameters']['algolia']['apiKey_search'],
            'algolia_indexName' => $this->config['parameters']['algolia']['indexName'],
        ];

        require $templatePath;
    }
}
