<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Sak\Core\Traits\Helpers;
use Dingo\Api\Http\Response;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    /**
     * @var
     */
    protected $service;

    /**
     * Respond with a no content response.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function responseSuccess()
    {
        $content = ['message' => 'ok', 'status_code' => 200];
        $response = new Response($content, 200);

        return $response;
    }
}
