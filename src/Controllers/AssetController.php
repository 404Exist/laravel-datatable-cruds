<?php

namespace Exist404\DatatableCruds\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class AssetController extends Controller
{
    /**
     * Return the javascript for the DatatableCruds
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function js()
    {
        $content = file_get_contents(__DIR__.'/../public/js/datatable-cruds.min.js') . "\n";
        $response = new Response(
            $content,
            200,
            [
                'Content-Type' => 'text/javascript',
            ]
        );

        return $response;
        return $this->cacheResponse($response);
    }

    /**
     * Cache the response 1 year (31536000 sec)
     */
    protected function cacheResponse(Response $response)
    {
        $response->setSharedMaxAge(31536000);
        $response->setMaxAge(31536000);
        $response->setExpires(new \DateTime('+1 year'));

        return $response;
    }
}
