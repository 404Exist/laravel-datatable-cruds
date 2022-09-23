<?php

namespace Exist404\DatatableCruds;

use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;
use Exist404\DatatableCruds\Traits\Globals;
use Exist404\DatatableCruds\Traits\Columns;
use Exist404\DatatableCruds\Traits\Inputs;
use Exist404\DatatableCruds\Traits\Common;

class DatatableCruds
{
    use Globals;
    use Columns;
    use Inputs;
    use Common;

    /**
     * Datatable blade @extends.
     * @var string
    */
    protected $bladeExtends = 'app';
    /**
     * Datatable blade @section.
     * @var string
    */
    protected $bladeSection = 'content';
    /**
     * Datatable page title.
     * @var string
    */
    protected $pageTitle = '';
    /**
     * Datatable Direction.
     * @var string
    */
    protected $dir = 'ltr';
    /**
     * Datatable Moment Format.
     * @var string
    */
    protected $dateFormat = '';
    /**
     * Datatable findBy.
     * @var string
    */
    protected $findBy = '';
    /**
     * Datatable messages.
     * @var string
    */
    protected $texts = [];
    /**
     * Button to open store modal form. [html, onclick => [openModal || funcName || href  => true]]
     * @var array
    */
    protected $addButton = [];
    /**
     * Action column buttons.
     * @var array
    */
    protected $actions = [];
    /**
     * Searchbar
     * @var array
    */
    protected $searchBar = [];
    /**
     * Searchbar
     * @var array
    */
    protected $limits = [];
    /**
     * Form width
     * @var int
    */
    protected $formWidth = null;
    /**
     * Form height
     * @var int
    */
    protected $formHeight = null;
    /**
     * Store button [color, label]
     * @var array
    */
    protected $storeButton = null;
    /**
     * Update button [color, label]
     * @var array
    */
    protected $updateButton = null;
    /**
     * Delete button [color, label]
     * @var array
    */
    protected $deleteButton = null;
    /**
     * Datatable columns to render
     * @var array
    */
    protected $columns = [];
    /**
     * Datatable inputs to render in forms
     * @var array
    */
    protected $inputs = [];
    /**
     * Form pages
     * @var array
    */
    protected $pages = [];
    /**
     * The current instance (input || column)
     * @var string
    */
    protected $instance = '';
    /**
     * The current column data
     * @var array
    */
    protected $column = [];
    /**
     * The current input data
     * @var array
    */
    protected $input = [];
    /**
     * Request headers.
     * @var array
    */
    protected $headers = [];
    /**
     * Request headers.
     * @var array
    */
    protected $request = [];
    /**
     * All Routes [get, store, update, delete].
     * @var array
    */
    protected $routes = [];
    /**
     * Routes Methods.
     * @var array
    */
    protected $routesMethods = [];
    /**
     * Relations to load with model.
     * @var string
    */
    protected $with = '';
    /**
     * Columns to searchby.
     * @var array
    */
    protected $searchBy = [];
    protected $exports = [];
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
     *
     * @param string $instance (input || column)
     * @return $this
    */
    protected function create($instance, $name)
    {
        $this->addCurrentInstance();
        $this->instance = $instance;
        $this->$instance = [];
        $this->name($name);
        return $this;
    }
    /**
     * Set name for current instance
     *
     * @param string $name
     * @return $this
    */
    protected function name($name)
    {
        $this->setValue("name", $name);

        if (!isset($this->{$this->instance}["label"])) {
            $this->label(str($name)->replace(".", " ")->headline());
        }

        return $this;
    }

    /**
     * Get Datatable Data
     *
     * @return array $data
    */
    public function applyData()
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

    /**
     * Get Datatable Data
     *
     * @param array $extendsData
     *
     * @return \Illuminate\Support\Facades\View|Illuminate\Pagination\LengthAwarePaginator
    */
    public function render($extendsData = [])
    {
        if (!isset($this->model)) {
            throw ModelIsNotSet::create();
        }
        if (isset($_SERVER['HTTP_X_DATATABLE'])) {
            return dataTableOf($this->model);
        }

        $this->addCurrentInstance();
        return view('datatable::datatable-cruds')->with([
            "datatable" => $this->applyData(),
            "extends" => $this->bladeExtends,
            "section" => $this->bladeSection,
            "extendsData" => $extendsData,
        ]);
    }

    protected function getInputs()
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

    protected function setValue($key, $value, $shouldAppend = false)
    {
        if (!isset($this->{$this->instance}[$key])) {
            $this->{$this->instance}[$key] = '';
        }
        if ($shouldAppend) {
            $this->{$this->instance}[$key] = $this->append($this->{$this->instance}[$key], $value);
        } else {
            $this->{$this->instance}[$key] = $value;
        }
    }

    protected function append($item, $value)
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

    protected function executeMethodsFromStr(...$items)
    {
        $for = $this->instance;
        foreach ($items as $item) {
            $data = explode('|', $item);
            $instance = $this->$for($data[0]);
            array_splice($data, 0, 1);
            foreach ($data as $val) {
                if (str_starts_with($val, '$@') && $for == 'column') {
                    $instance->href()->exec(ltrim($val, '$@'));
                } elseif (str_starts_with($val, '$#') && $for == 'column') {
                    $instance->html()->exec(ltrim($val, '$#'));
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
     *
     * @return $this
    */
    protected function addCurrentInstance()
    {
        if (
            isset($this->{$this->instance}) &&
            !empty($this->{$this->instance})
        ) {
            array_push($this->{$this->instance . 's'}, $this->{$this->instance});
            $this->{$this->instance} = [];
        }
        return $this;
    }
}
