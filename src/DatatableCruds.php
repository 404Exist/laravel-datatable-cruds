<?php

namespace Exist404\DatatableCruds;

use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;
use Exist404\DatatableCruds\Traits\Globals;
use Exist404\DatatableCruds\Traits\Columns;
use Exist404\DatatableCruds\Traits\Inputs;
use Exist404\DatatableCruds\Traits\Common;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;

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

    protected array $pagination = [
        "show" => true,
        "hideIfContainOnePage" => true,
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
        $this->addCurrentInstance();

        if (is_callable($name)) {
            $name = $name();
            if (!$name) {
                return $this;
            }
        }

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

    public function dump(): mixed
    {
        $this->addCurrentInstance();
        return dump($this);
    }

    public function dd(): void
    {
        $this->addCurrentInstance();
        dd($this);
    }

    public function renderData(): array
    {
        $this->addCurrentInstance();

        $datatable = [
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
            'pagination' => $this->pagination,
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

        $this->reset();

        return $datatable;
    }

    public function render(array $extendsData = []): View|LengthAwarePaginator
    {
        if (!isset($this->model)) {
            throw ModelIsNotSet::create();
        }
        if (isset($_SERVER['HTTP_X_DATATABLE'])) {
            return dataTableOf($this->model);
        }

        return view('datatable::datatable-cruds')->with([
            "datatable" => $this->renderData(),
            "extends" => $this->bladeExtends,
            "section" => $this->bladeSection,
            "extendsData" => $extendsData,
        ]);
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
