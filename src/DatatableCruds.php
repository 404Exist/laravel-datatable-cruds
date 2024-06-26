<?php

namespace Exist404\DatatableCruds;

use Closure;
use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;
use Exist404\DatatableCruds\Traits\Globals;
use Exist404\DatatableCruds\Traits\Columns;
use Exist404\DatatableCruds\Traits\Inputs;
use Exist404\DatatableCruds\Traits\Common;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

class DatatableCruds
{
    use Globals;
    use Columns;
    use Inputs;
    use Common;

    protected string $bladeExtendsName = '';

    protected string $bladeSectionName = '';

    protected array $bladeSections = [];

    protected array $bladeStacks = [];

    protected string $pageTitle = '';

    protected string $dir = '';

    protected string $dateFormat = '';

    protected string $findBy = '';

    protected array $texts = [];

    protected array $searchBar = [];

    protected array $pagination = [];

    protected array $limits = [];

    protected string $formWidth = "";

    protected string $formHeight = "";

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
    protected array $filterBy = [];
    protected array $exports = [];
    /**
     * Create a new DatatableCruds instance.
     *
    */
    protected ?LengthAwarePaginator $result = null;
    public function __construct()
    {
        foreach (config('datatablecruds') as $key => $value) {
            if (empty($this->$key)) {
                $this->$key = $value;
            }
        }
    }
    /**
     * Create a new (input || column)
    */
    protected function create(string $instance, string|Closure $name): self
    {
        $this->addCurrentInstance();

        if ($name instanceof Closure) {
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

    public function dump(): self
    {
        $this->addCurrentInstance();
        dump($this);
        return $this;
    }

    public function dd(): self
    {
        $this->addCurrentInstance();
        dd($this);
        return $this;
    }

    public function renderData(): array
    {
        $this->addCurrentInstance();

        $datatable = [
            'component_id' => $this->pageTitle,
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
            'filterBy' => $this->filterBy,
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
                'storeInput' => $this->formStoreButton,
                'updateInput' => $this->formUpdateButton,
                'deleteInput' => $this->formDeleteButton
            ]
        ];

        $this->reset();

        return $datatable;
    }

    public function table(): Htmlable
    {
        return new HtmlString("<datatable-cruds data='" . json_encode($this->renderData()) . "'></datatable-cruds>");
    }

    public function getResults(): LengthAwarePaginator
    {
        $columnsHasCallback = false;
        foreach ($this->columns as $column) {
            if (isset($column["callback"])) {
                $columnsHasCallback = true;
                break;
            }
        }
        if ($columnsHasCallback) {
            array_map(function ($item) {
                array_map(function ($column) use ($item) {
                    if (isset($column['callback']) && $column['callback'] instanceof Closure) {
                        $item->{$column['name']} = $column['callback']($item);
                        return $column;
                    }
                }, $this->columns);
            }, $this->result->items());
        }

        return $this->result;
    }

    public function isXhr(): bool
    {
        return isset(
            $_SERVER['HTTP_X_DATATABLE'],
            $_SERVER['HTTP_X_DATATABLE_COMPONENT']
        ) && $_SERVER['HTTP_X_DATATABLE_COMPONENT'] === $this->pageTitle;
    }

    public function render(array|string $extendsData = []): View|LengthAwarePaginator
    {
        if (!isset($this->model)) {
            throw ModelIsNotSet::create();
        }

        if ($this->isXhr()) {
            return $this->getResults();
        }

        if (is_string($extendsData) && view($extendsData)) {
            return view($extendsData)->with(["datatable" => $this]);
        }

        return view('datatable::datatable-cruds')->with([
            "datatable" => $this->renderData(),
            "extendsName" => $this->bladeExtendsName,
            "sectionName" => $this->bladeSectionName,
            "sections" => $this->bladeSections,
            "stacks" => $this->bladeStacks,
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
        if ($value instanceof Closure && $key != "callback") {
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
        $this->filterBy = [];
        $this->pageTitle = "";
        $this->instance = "";
        $this->with = "";
    }
}
