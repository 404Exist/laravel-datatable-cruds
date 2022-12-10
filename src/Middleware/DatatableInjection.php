<?php

namespace Exist404\DatatableCruds\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DatatableInjection
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $this->inject($response);

        return $response;
    }

    public function inject(Response $response)
    {
        $content = $response->getContent();

        if ($this->isDataTableRenderd($content)) {
            $route = config("datatablecruds.script_file_url");

            $widget = "<script src='$route' defer></script>";

            $pos = stripos($content, '<body');


            if ($pos !== false) {
                $bodyRegex = "/<\s*body[^>]*>/";
                preg_match($bodyRegex, substr($content, $pos), $bodyOpenTag);
                $content = substr($content, 0, $pos) . preg_replace(
                    $bodyRegex,
                    $bodyOpenTag[0] . "<div id='datatablecruds'>",
                    substr($content, $pos)
                );
            }

            $pos = strripos($content, '</body>');

            if ($pos !== false) {
                $content = substr($content, 0, $pos) . "</div>" . substr($content, $pos);
            }

            $pos = strripos($content, '</head>');

            if (false !== $pos) {
                $content = substr($content, 0, $pos) . $widget . substr($content, $pos);
            }

            $response->setContent($content);
            $response->headers->remove('Content-Length');
        }
    }

    protected function isDataTableRenderd($content): bool
    {
        return (bool) strripos($content, '<data-list');
    }
}
