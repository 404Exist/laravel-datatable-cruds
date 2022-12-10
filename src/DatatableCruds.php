<?php

namespace Exist404\DatatableCruds;

use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;
use Exist404\DatatableCruds\Traits\Globals;
use Exist404\DatatableCruds\Traits\Columns;
use Exist404\DatatableCruds\Traits\Inputs;
use Exist404\DatatableCruds\Traits\Common;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class DatatableCruds
{
    use Globals;
    use Columns;
    use Inputs;
    use Common;

    protected string $bladeExtends = 'app';

    protected string $bladeSection = 'content';

    protected string $pageTitle = '';

    protected string $dir = 'ltr';

    protected string $dateFormat = '';

    protected string $findBy = '';

    protected array $texts = [];

    protected array $searchBar = [
        "debounce" => "500ms",
        "class" => "form-control"
    ];

    protected array $limits = [10, 25, 50, 100];

    protected string $formWidth = "100%";

    protected string $formHeight = "100vh";

    protected array $columns = [];

    protected array $inputs = [];
    /**
     * Form pages
    */
    protected array $pages = [];
    /**
     * The current instance (input || column)
    */
    protected string $instance = '';
    /**
     * The current column data
    */
    protected array $column = [];
    /**
     * The current input data
    */
    protected array $input = [];
    /**
     * Request headers.
    */
    protected array $headers = [];

    protected array $request = [];
    /**
     * All Routes [get, store, update, delete].
    */
    protected array $routes = [];

    protected array $routesMethods = [];
    /**
     * Relations to load with model.
    */
    protected string $with = '';
    /**
     * Columns to searchby.
     * @var array
    */
    protected array $searchBy = [];
    protected array $exports = [];
    /**
     * Create a new DatatableCruds instance.
     *
    */
    public function __construct()
    {
        foreach (config('datatablecruds') as $key => $value) {
            $this->$key = $value;
        }
    }
    /**
     * Create a new (input || column)
    */
    protected function create(string $instance, string|callable $name): self
    {
        if (is_callable($name)) {
            $name = $name();
            if (!$name) {
                return $this;
            }
        }
        $this->addCurrentInstance();
        $this->instance = $instance;
        $this->$instance = [];
        $this->name($name);
        return $this;
    }
    /**
     * Set name for current instance
    */
    protected function name(string $name): self
    {
        $this->setValue("name", $name);

        if (!isset($this->{$this->instance}["label"])) {
            $this->label(str($name)->replace(".", " ")->headline());
        }

        return $this;
    }

    /**
     * Get Datatable Data
    */
    public function getDatatableData(): array
    {
        return [
            'title' => $this->pageTitle,
            'dir' => $this->dir,
            'dateFormat' => $this->dateFormat,
            'request' => $this->request,
            'findBy' => $this->findBy,
            'messages' => $this->texts,
            'headers' => $this->headers,
            'methods' => $this->routesMethods,
            'with' => $this->with,
            'searchBy' => $this->searchBy,
            'limits' => $this->limits,
            'actions' => $this->actions,
            'routes' => $this->routes,
            'addButton' => $this->addButton,
            'searchBar' => $this->searchBar,
            'exports' => $this->exports,
            'columns' => $this->columns,
            'forms' => [
                'width' => $this->formWidth,
                'height' => $this->formHeight,
                'inputs' => $this->getInputs(),
                'storeInput' => $this->storeButton,
                'updateInput' => $this->updateButton,
                'deleteInput' => $this->deleteButton
            ]
        ];
    }

    public function render(array $extendsData = []): View|LengthAwarePaginator|Collection
    {
        if (!isset($this->model)) {
            throw ModelIsNotSet::create();
        }
        if (isset($_SERVER['HTTP_X_DATATABLE'])) {
            return dataTableOf($this->model);
        }

        $this->addCurrentInstance();
        return view('datatable::datatable-cruds')->with([
            "datatable" => $this->getDatatableData(),
            "extends" => $this->bladeExtends,
            "section" => $this->bladeSection,
            "extendsData" => $extendsData,
        ]);
    }

    public function renderData(): array
    {
        $this->addCurrentInstance();

        $datatable = $this->getDatatableData();

        $this->reset();

        return $datatable;
    }

    protected function getInputs(): array
    {
        $inputs = $this->inputs;
        if (count($this->pages) > 1) {
            $inputs = $this->pages;
            foreach ($this->inputs as $input) {
                if (!isset($input['page']) || $input['page'] < 1) {
                    $input['page'] = 1;
                }
                if (!isset($inputs[$input['page'] - 1])) {
                    $inputs[$input['page'] - 1] = [];
                }
                array_push($inputs[$input['page'] - 1], $input);
            }
        }
        return $inputs;
    }

    protected function setValue(string $key, mixed $value, bool $shouldAppend = false): void
    {
        if (is_callable($value)) {
            $value = $value();
        }
        if (!isset($this->{$this->instance}[$key])) {
            $this->{$this->instance}[$key] = '';
        }
        if ($shouldAppend) {
            $this->{$this->instance}[$key] = $this->append($this->{$this->instance}[$key], $value);
        } else {
            $this->{$this->instance}[$key] = $value;
        }
    }

    protected function append(mixed $item, mixed $value): mixed
    {
        if (is_array($value) && !is_array($item)) {
            $item = [];
        }
        if (is_array($item)) {
            if (is_array($value)) {
                $item = array_merge($item, $value);
            } else {
                array_push($item, $value);
            }
        } else {
            $item .= $value;
        }
        return $item;
    }

    protected function executeMethodsFromStr(string ...$items): void
    {
        $for = $this->instance;
        foreach ($items as $item) {
            $data = explode('|', $item);
            $instance = $this->$for($data[0]);
            array_splice($data, 0, 1);
            foreach ($data as $val) {
                if (str_starts_with($val, '$@') && $for == 'column') {
                    $instance->execHref(ltrim($val, '$@'));
                } elseif (str_starts_with($val, '$#') && $for == 'column') {
                    $instance->execHtml(ltrim($val, '$#'));
                } else {
                    $method = explode('(', $val)[0];
                    if (!method_exists($this, $method) && $for == 'input') {
                        $instance->type($method);
                    } else {
                        preg_match('/\((.*)\)/', $val, $params);
                        if (isset($params[1])) {
                            $params = $params[1];
                            $fixedParams = [];
                            foreach (explode(',', $params) as $param) {
                                if ($param == 'true') {
                                    $param = true;
                                } elseif ($param == 'false') {
                                    $param = false;
                                } elseif ($param == 'null') {
                                    $param = null;
                                } elseif (is_numeric($param)) {
                                    $param = (int)$param;
                                }
                                array_push($fixedParams, $param);
                            }
                            $arr = [];
                            if (str_ends_with($params, '}') || str_ends_with($params, ']')) {
                                $arr = json_decode($params, true);
                            }
                            $arr ? $instance->{$method}($arr) : $instance->{$method}(...$fixedParams);
                        } else {
                            $instance->{$method}();
                        }
                    }
                }
            }
            $instance->addCurrentInstance();
        }
    }
    /**
     * To push current instance to ($this->inputs, $this->columns)
    */
    protected function addCurrentInstance(): self
    {
        if (
            isset($this->{$this->instance}) &&
            isset($this->{$this->instance}['name'])
        ) {
            array_push($this->{$this->instance . 's'}, $this->{$this->instance});
            $this->{$this->instance} = [];
        }
        return $this;
    }

    protected function reset(): void
    {
        $this->__construct();
        $this->columns = [];
        $this->inputs = [];
        $this->routes = [];
        $this->routesMethods = [];
        $this->pages = [];
        $this->searchBy = [];
        $this->pageTitle = "";
        $this->instance = "";
        $this->with = "";
    }
}
