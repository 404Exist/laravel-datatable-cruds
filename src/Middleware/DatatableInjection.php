<?php

namespace Exist404\DatatableCruds\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DatatableInjection
{
    protected string $content;

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $this->inject($response);

        return $response;
    }

    public function inject(Response $response): void
    {
        $this->content = $response->getContent();

        if ($this->isDataTableRenderd()) {
            if ($this->isNotDatatableScriptLoaded()) {
                $this->injectScript();
            }

            if ($this->isNotDatatableDivLoaded()) {
                $this->injectDatatableDiv();
            }

            $response->setContent($this->content);
            $response->headers->remove('Content-Length');
        }
    }

    protected function injectScript(): void
    {
        $route = route("datatablecruds.script_file_url", [
            'v' => filemtime(datatableScriptPath())
        ]);

        $script = "<script src=\"$route\" defer></script>";

        $pos = strripos($this->content, '</head>');

        if (false !== $pos) {
            $this->content = substr($this->content, 0, $pos) . $script . substr($this->content, $pos);
        }
    }

    protected function injectDatatableDiv(): void
    {
        $pos = stripos($this->content, '<body');

        if ($pos !== false) {
            $bodyRegex = "/<\s*body[^>]*>/";

            preg_match($bodyRegex, substr($this->content, $pos), $bodyOpenTag);

            $this->content = substr($this->content, 0, $pos) . preg_replace(
                $bodyRegex,
                $bodyOpenTag[0] . "<div id='datatablecruds'>",
                substr($this->content, $pos)
            );
        }

        $pos = strripos($this->content, '</body>');

        if ($pos !== false) {
            $this->content = substr($this->content, 0, $pos) . "</div>" . substr($this->content, $pos);
        }
    }

    protected function isDataTableRenderd(): bool
    {
        return (bool) strripos($this->content, '<data-list');
    }

    protected function isNotDatatableScriptLoaded(): bool
    {
        $matches = $this->matchTagElWithAttributeValue(
            "script",
            "src",
            ".*?\\" . config("datatablecruds.script_file_url") . "?.*?"
        );

        return ! (bool) count($matches);
    }

    protected function isNotDatatableDivLoaded(): bool
    {
        $matches = $this->matchTagElWithAttributeValue(
            "div",
            "id",
            "datatablecruds"
        );

        return ! (bool) count($matches);
    }

    protected function matchTagElWithAttributeValue($tag, $attribute, $value): array
    {
        $regexs = [
            "/<\s*$tag.*?$attribute=\"$value\"[^>]*>/",
            "/<\s*$tag.*?$attribute='$value'[^>]*>/",
        ];

        $matches = [];
        foreach ($regexs as $regex) {
            preg_match($regex, $this->content, $matches[]);
        }

        return array_merge($matches[0], $matches[1]);
    }
}
