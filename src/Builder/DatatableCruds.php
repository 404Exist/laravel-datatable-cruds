<?php

namespace Exist404\DatatableCruds\Builder;

use Exist404\DatatableCruds\Traits\Globals;
use Exist404\DatatableCruds\Traits\Columns;
use Exist404\DatatableCruds\Traits\Inputs;
use Exist404\DatatableCruds\Traits\Common;

class DatatableCruds
{
    use Globals, Columns, Inputs, Common;
    /**
     * Datatable Title.
     * @var string
    */
    protected $title = '';
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
     * Position for current instance
     * @var int
    */
    protected $position = null;
    /**
     * Name of previous methodname.
     * @var string
    */
    protected $methodName = null;
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
            $this->{$key} = $value;
        }
    }
    /**
     * Create a new (input || column)
     *
     * @param  string $instance (input || column)
     * @return $this
    */
    protected function create($instance, $name) {
        $this->setMethodAction('create');
        $this->instance = $instance;
        $this->{$instance} = [];
        $this->position = null;
        $this->name($name);
        return $this;
    }

    /**
     * Update an exist (input || column)
     *
     * @param  string $instance (input || column)
     * @param  string $name
     * @return $this
    */
    protected function update($instance, $name) {
        $this->setMethodAction('update');
        $this->instance = $instance;
        $this->{$instance} = [];
        $this->{$instance}['name'] = $name;
        return $this;
    }
    /**
     * Delete an exist (input || column)
     *
     * @param  string $instance (input || column)
     * @param  string $name
     * @return $this
    */
    protected function delete($instance, $name) {
        $this->setMethodAction('delete');
        $this->instance = $instance;
        $this->{$instance}['name'] = $name;
        if ($this->instancePosition() !== false) {
            array_splice($this->{$this->instance.'s'}, $this->instancePosition(), 1);
        }
        $this->{$instance} = [];
        return $this;
    }
    /**
     * Set name for current instance
     *
     * @param  string $name
     * @return $this
    */
    protected function name($name) {
        if (!isset($this->{$this->instance}['name'])) $this->{$this->instance}['name'] = $name;
        if (!isset($this->{$this->instance}['label'])) {
            $this->label($this->dynamicLabel($this->{$this->instance}['name']));
        }
        return $this;
    }

    /**
     * Get Datatable Data
     *
     * @return array $data
    */
    public function applyData() {
        $inputs = $this->getInputs();
        return [
            'title' => $this->title,
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
                'width' => $this->formWidth.'%',
                'inputs' => $inputs,
                'storeInput' => $this->storeButton,
                'updateInput' => $this->updateButton,
                'deleteInput' => $this->deleteButton
            ]
        ];
    }

    /**
     * Get Datatable Data
     *
     * @param  string $extends
     * @param  string $section
     * @param  array $extendsData
     *
     * @return \Illuminate\Support\Facades\View
    */
    public function render($extends = 'app', $section = 'content', $extendsData = [])
    {
        if (isset($_SERVER['HTTP_X_DATATABLE'])) {
            if (!$this->model) return $this->exception('You must send valid model class to render data');
            return dataTableOf($this->model);
        }
        $datatable = $this->applyData();
        return view('datatable::datatable-cruds', compact('datatable', 'extends', 'section', 'extendsData'));
    }

    protected function getInputs()
    {
        $inputs = [];
        if (count($this->pages) > 1) {
            $inputs = $this->pages;
            foreach ($this->inputs as $input) {
                if (!isset($input['page']) || $input['page'] < 1) $input['page'] = 1;
                if (!isset($inputs[$input['page'] - 1])) $inputs[$input['page'] - 1] = [];
                array_push($inputs[$input['page'] - 1], $input);
            }
        } else $inputs = $this->inputs;
        return $inputs;
    }

    protected function checkCast($column, ...$types)
    {
        $model = new $this->model();
        return isset($model->getCasts()[$column]) && in_array($model->getCasts()[$column], $types);
    }
    protected function getFillableByTypes(...$types)
    {
        $model = new $this->model();
        $arr = [];
        $arr1 = [];
        foreach ($model->getCasts() as $key => $value) {
            foreach ($types as $type) {
                if ($type == $value) {
                    array_push($arr, $key);
                }
            }
        }
        foreach ($model->getFillable() as $fillable) {
            if (!in_array($fillable, $arr)) {
                array_push($arr1, $fillable);
            }
        }
        return $arr1;
    }

    protected function methodParamValue($value, $objKey, $callBackVal = '')
    {
        if ($value) return $value;
        return isset($this->{$objKey}) ? $this->{$objKey}: $callBackVal;
    }

    protected function instanceMethod($instance, $method)
    {
        if ($this->instance == $instance) return $this->exception($method.' method isn\'t exist with create '.$instance);
    }
    protected function methodsToApplyAfter($curMethod, ...$oldMethods)
    {
        if (!in_array($this->methodName, $oldMethods)) return $this->exception($curMethod.' Method works with '.implode(', ', $oldMethods).' method only');
    }

    protected function exception($message)
    {
        return env('APP_DEBUG') ? throw new \Exception($message) : '';
    }

    protected function instancePosition($value = null, $searchBy = 'name')
    {
        $value = $value ? $value : $this->{$this->instance}[$searchBy];
        return array_search($value, array_column($this->{$this->instance.'s'}, $searchBy));
    }

    protected function dynamicLabel($label)
    {
        $label = str_replace('_', ' ', $label);
        $label = str_replace('-', ' ', $label);
        $label = str_replace('.', ' ', $label);
        $label = ucwords($label);
        return $label;
    }
    protected function setValue($key, $value, $append = false)
    {
        if ($this->methodAction == 'delete') return $this->exception('You can\'t add any properties to deleted column');
        if ($this->methodAction == 'update') {
            if ($this->instancePosition() !== false) {
                if (!isset($this->{$this->instance.'s'}[$this->instancePosition()][$key])) $this->{$this->instance.'s'}[$this->instancePosition()][$key] = '';
                if ($append) {
                    $this->{$this->instance.'s'}[$this->instancePosition()][$key] =
                        $this->append($this->{$this->instance.'s'}[$this->instancePosition()][$key] , $value);
                }
                else $this->{$this->instance.'s'}[$this->instancePosition()][$key] = $value;
            }
        } else {
            if (!isset($this->{$this->instance}[$key])) $this->{$this->instance}[$key] = '';
            if ($append) {
                $this->{$this->instance}[$key] = $this->append($this->{$this->instance}[$key] , $value);
            }
            else $this->{$this->instance}[$key] = $value;
        }
    }
    protected function append($item, $value)
    {
        if (is_array($value) && !is_array($item)) $item = [];
        if (is_array($item)) {
            if (is_array($value)) {
                $item = array_merge($item, $value);
            } else array_push($item, $value);
        } else  $item .= $value;
        return $item;
    }
    protected function moveElement() {
        $out = array_splice($this->{$this->instance.'s'}, $this->instancePosition(), 1);
        array_splice($this->{$this->instance.'s'}, $this->position, 0, $out);
        $this->position = null;
    }
    protected function setMethodName($name)
    {
        $this->methodName = $name;
        return $this;
    }
    protected function setMethodAction($action)
    {
        $this->methodAction = $action;
        return $this;
    }
}
