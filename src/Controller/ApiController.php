<?php

namespace AlgoliaApp\Controller;

use AlgoliaApp\Entity\App as AppEntity;

class ApiController extends Controller
{
    public function runAddEntity()
    {
        try {
            if (empty($_POST['data'])) {
                throw new \Exception('Data is missing');
            }

            $data = json_decode($_POST['data'], true);

            if (empty($data)) {
                throw new \Exception('Failed to decode data: ' . json_last_error_msg());
            }

            $newApp = new AppEntity($data); // useful only for data validation
            $objectId = $this->model->createInIndex($newApp);

            $this->response->setResponseCode(201);

            $this->response->render($objectId);
        } catch (\Exception $e) {
            throw new \Exception('Entity could not be created: ' . $e->getMessage(), 400, $e);
        }
    }

    /**
     * Deletes an entity from the index
     * @param  string|int $id
     */
    public function runDeleteEntity($id)
    {
        $this->model->delete($id);
    }
}
