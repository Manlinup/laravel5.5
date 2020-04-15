<?php

namespace App\Http\Controllers;

use Sak\Core\Controllers\SwaggerController as BaseSwaggerController;

class SwaggerController extends BaseSwaggerController
{
    /**
     *
     * @SWG\Swagger(
     *   @SWG\Info(
     *     title="SAK DATAS API",
     *     version="1.0.0"
     *   )
     * )
     */
    public function getJSON()
    {
        return parent::getJSON();
    }
}
