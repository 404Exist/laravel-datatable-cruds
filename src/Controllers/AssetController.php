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
    }
}
