<?php

namespace Exist404\DatatableCruds\Traits;
Trait Globals {
    /**
     * Datatable model
     *
     * @param  string $model
     * @return $this
    */
    public function setModel($model)
    {
        $this->model = $model;
        if (!$this->title) $this->setTitle(ucwords((new $model())->getTable()));
        return $this;
    }
    /**
     * Datatable title
     *
     * @param  string $title
     * @return $this
    */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    /**
     * Datatable direction
     *
     * @param  string $dir
     * @return $this
    */
    public function setDir($dir)
    {
        $this->dir = $dir;
        return $this;
    }
    /**
     * Request Headers
     *
     * @param  array $headers
     * @return $this
    */
    public function setHeader($name, $value)
    {
        $this->headers = array_merge($this->headers, [$name => $value]);
        return $this;
    }
    /**
     * Relations to load with model
     *
     * @param  mixed ...$with
     * @return $this
    */
    public function with(...$with)
    {
        foreach ($with as $index => $value) {
            $this->with .= $index == 0 ? $value :'|'.$value;
        }
        return $this;
    }
    /**
     * Columns names to search by
     *
     * @param  mixed ...$searchBy
     * @return $this
    */
    public function searchBy(...$searchBy)
    {
        $this->searchBy = array_merge($this->searchBy, $searchBy);
        return $this;
    }
    /**
     * Set get route
     *
     * @param  string $route
     * @return $this
    */
    public function setGetRoute($route)
    {
        $this->routes['get'] = $route;
        return $this;
    }
    /**
     * Set store route
     *
     * @param  string $route
     * @param  string $method
     * @return $this
    */
    public function setStoreRoute($route, $method = 'post')
    {
        $this->routes['store'] = $route;
        $this->routesMethods['store'] = $method;
        return $this;
    }
    /**
     * Set update route
     *
     * @param  string $route
     * @param  string $method
     * @return $this
    */
    public function setUpdateRoute($route, $method = 'patch')
    {
        $this->routes['update'] = $route;
        $this->routesMethods['update'] = $method;
        return $this;
    }
    /**
     * Set delete route
     *
     * @param  string $route
     * @param  string $method
     * @return $this
    */
    public function setDeleteRoute($route, $method = 'delete')
    {
        $this->routes['delete'] = $route;
        $this->routesMethods['delete'] = $method;
        return $this;
    }
    /**
     * Request FindBy Key
     *
     * @param  string $findBy
     * @return $this
    */
    public function setRequestFindByKey($findBy)
    {
        $this->findBy = $findBy;
        return $this;
    }
    /**
     * Default Date Format
     *
     * @param  string $dateFormat
     * @return $this
    */
    public function setDefaultDateFormat($dateFormat)
    {
        $this->dateFormat = 'format('.$dateFormat.')';
        return $this;
    }
    /**
     * Datatable order
     *
     * @param  string $orderBy
     * @param  string $order
     * @return $this
    */
    public function setDefaultOrder($orderBy = 'created_at', $order = 'desc')
    {
        $this->request['order'] = $order;
        $this->request['orderBy'] = $orderBy;
        return $this;
    }
    /**
     * Update addAction
     *
     * @param  string $html
     * @param  string $onclick (openModal | funcName | href)
     * @param  string $value
     * @return $this
    */
    public function addAction($html = null, $onclick = 'openModal', $value = true) {
        $this->addButton['onclick'] = [$onclick => $value];
        $this->addButton['html'] = $html;
        return $this;
    }
    /**
     * Update editAction
     *
     * @param  string $html
     * @param  string $onclick (openModal | funcName | href)
     * @param  string $value
     * @return $this
    */
    public function editAction($html = null, $onclick = 'openModal', $value = true) {
        $this->actions["edit"]['onclick'] = [$onclick => $value];
        $this->actions["edit"]['html'] = $html;
        if ($html === false) $this->actions["edit"] = false;
        return $this;
    }
    /**
     * Update deleteAction
     *
     * @param  string $html
     * @param  string $onclick (openModal | funcName | href)
     * @param  string $value
     * @return $this
    */
    public function deleteAction($html = null, $onclick = 'openModal', $value = true) {
        $this->actions["delete"]['onclick'] = [$onclick => $value];
        $this->actions["delete"]['html'] = $html;
        if ($html === false) $this->actions["delete"] = false;
        return $this;
    }
    /**
     * Update cloneAction
     *
     * @param  string $html
     * @param  string $onclick (openModal | funcName | href)
     * @param  string $value
     * @return $this
    */
    public function cloneAction($html = null, $onclick = 'openModal', $value = true) {
        $this->actions["clone"]['onclick'] = [$onclick => $value];
        $this->actions["clone"]['html'] = $html;
        if ($html === false) $this->actions["clone"] = false;
        return $this;
    }
    /**
     * Set search debounce and class name
     *
     * @param  string $debounce
     * @param  string $class
     * @return $this
    */
    public function search($debounce, $class = 'form-control')
    {
        $this->searchBar = [
            "class" => $class,
            "debounce" => $debounce
        ];
        return $this;
    }
    /**
     * Datatable exports
     *
     * @param  array $exports
     * @return $this
    */
    public function exports($exports = [])
    {
        $this->exports = array_merge(config('datatablecruds.exports'), $exports);
        return $this;
    }
    /**
     * Default Texts
     *
     * @param  string $key
     * @param  string $text
     * @return $this
    */
    public function setText($key, $text)
    {
        $this->texts[$key] = $text;
        return $this;
    }
    /**
     * Set view limits
     *
     * @param  mixed $limits
     * @return $this
    */
    public function setLimits(...$limits)
    {
        $this->limits = $limits;
        return $this;
    }
    /**
     * Set form width
     *
     * @param  int $width
     * @return $this
    */
    public function formWidth($width)
    {
        $this->formWidth = $width;
        return $this;
    }
    /**
     * Update form store button
     *
     * @param  string $label
     * @param  string $color
     * @return $this
    */
    public function storeButton($label = 'Create', $color = 'primary')
    {
        $this->storeButton = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }
    /**
     * Update form update button
     *
     * @param  string $label
     * @param  string $color
     * @return $this
    */
    public function updateButton($label = 'Update', $color = 'primary')
    {
        $this->updateButton = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }
    /**
     * Update form delete button
     *
     * @param  string $label
     * @param  string $color
     * @return $this
    */
    public function deleteButton($label = 'Delete', $color = 'danger')
    {
        $this->deleteButton = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }
}
