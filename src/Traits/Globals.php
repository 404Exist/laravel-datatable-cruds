<?php

namespace Exist404\DatatableCruds\Traits;

use Closure;
use Exist404\DatatableCruds\DatatableCruds;
use Exist404\DatatableCruds\Model\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

trait Globals
{
    public function for(Builder|Relation|string $model): DatatableCruds
    {
        if (is_string($model)) {
            $this->tableName = ! class_exists($model) ? $model : null;
            $model = class_exists($model) ? (new $model())->query() : BaseModel::query();
        }

        if ($model instanceof Relation) {
            $model = $model->getQuery();
        }

        $this->tableName ??= $model->getModel()->getTable();

        $this->model = $model;

        $this->setPageTitle(str($this->tableName)->headline());

        if ($this->isXhr()) {
            $this->result = dataTableOf($this->model, $this->tableName);
        }

        return $this;
    }
    /**
     * Datatable page title
    */
    public function setPageTitle(string $pageTitle): DatatableCruds
    {
        $this->pageTitle = $pageTitle;
        return $this;
    }
    /**
     * Datatable direction
    */
    public function setDir(string|Closure $dir): DatatableCruds
    {
        $this->dir = $dir;
        return $this;
    }
    /**
     * Request Headers
    */
    public function setHeader(string $name, string $value): DatatableCruds
    {
        $this->headers = array_merge($this->headers, [$name => $value]);
        return $this;
    }
    /**
     * Relations to load with model
    */
    public function with(string ...$with): DatatableCruds
    {
        foreach ($with as $index => $value) {
            $this->with .= $index == 0 ? $value : '|' . $value;
        }
        return $this;
    }
    /**
     * Columns names to search by
    */
    public function searchBy(string ...$searchBy): DatatableCruds
    {
        $this->searchBy = array_merge($this->searchBy, $searchBy);
        return $this;
    }
    /**
     * Column to filter by
    */
    public function selectFilter(string $filterBy, array $options, string $label, string $defaultValue = null): DatatableCruds
    {
        $this->filterBy = array_merge($this->filterBy, [
            $filterBy => ["options" => $options, "label" => $label, "defaultValue" => $defaultValue]
        ]);
        return $this;
    }

    public function setGetRoute(string $route): DatatableCruds
    {
        $this->routes['get'] = $route;
        return $this;
    }

    public function setStoreRoute(string $route, string $method = 'post'): DatatableCruds
    {
        $this->routes['store'] = $route;
        $this->routesMethods['store'] = $method;
        return $this;
    }

    public function setUpdateRoute(string $route, string $method = 'patch'): DatatableCruds
    {
        $this->routes['update'] = $route;
        $this->routesMethods['update'] = $method;
        return $this;
    }

    public function setDeleteRoute(string $route, string $method = 'delete'): DatatableCruds
    {
        $this->routes['delete'] = $route;
        $this->routesMethods['delete'] = $method;
        return $this;
    }

    public function setRequestFindByKeyName(string $findBy): DatatableCruds
    {
        $this->findBy = $findBy;
        return $this;
    }

    public function setDefaultDateFormat(string $dateFormat): DatatableCruds
    {
        $this->dateFormat = str($dateFormat)->wrap("format(", ")");
        return $this;
    }

    public function setDefaultOrder(string $orderBy = 'created_at', string $order = 'desc'): DatatableCruds
    {
        $this->request['order'] = $order;
        $this->request['orderBy'] = $orderBy;
        return $this;
    }

    public function rowAddButton(string|bool $html = null, bool $isJS = false, string $onclick = 'openModal', string|bool $value = true): DatatableCruds
    {
        $this->addButton['onclick'] = [$onclick => $value];
        if (is_string($html)) {
            $this->addButton['html'] = $isJS ? str($html)->wrap("{exec(", ")}") : $html;
        }
        $this->addButton['enabled'] = $html ? true : false;
        return $this;
    }

    public function rowEditButton(string|bool $html = null, bool $isJS = false, string $onclick = 'openModal', string|bool $value = true): DatatableCruds
    {
        $this->actions["edit"]['onclick'] = [$onclick => $value];
        if (is_string($html)) {
            $this->actions["edit"]['html'] = $isJS ? str($html)->wrap("{exec(", ")}") : $html;
        }
        $this->actions["edit"]['enabled'] = $html ? true : false;
        return $this;
    }

    public function rowDeleteButton(string|bool $html = null, bool $isJS = false, string $onclick = 'openModal', string|bool $value = true): DatatableCruds
    {
        $this->actions["delete"]['onclick'] = [$onclick => $value];
        if (is_string($html)) {
            $this->actions["delete"]['html'] = $isJS ? str($html)->wrap("{exec(", ")}") : $html;
        }
        $this->actions["delete"]['enabled'] = $html ? true : false;
        return $this;
    }

    public function rowCloneButton(string|bool $html = null, bool $isJS = false, string $onclick = 'openModal', string|bool $value = true): DatatableCruds
    {
        $this->actions["clone"]['onclick'] = [$onclick => $value];
        if (is_string($html)) {
            $this->actions["clone"]['html'] = $isJS ? str($html)->wrap("{exec(", ")}") : $html;
        }
        $this->actions["clone"]['enabled'] = $html ? true : false;
        return $this;
    }
    /**
     * Set search debounce and class name
    */
    public function search(string $debounce, string $class = 'form-control'): DatatableCruds
    {
        $this->searchBar = [
            "class" => $class,
            "debounce" => $debounce
        ];
        return $this;
    }

    public function printBtn(bool|string $print = true): DatatableCruds
    {
        $this->exports["print"] = $print;
        return $this;
    }

    public function setText(string $key, string $text): DatatableCruds
    {
        $texts = &$this->texts;

        foreach (explode('.', $key) as $key) {
            $texts = &$texts[$key];
        }

        $texts = $text;

        unset($texts);

        return $this;
    }

    public function showPagination(bool $show = true): DatatableCruds
    {
        $this->pagination["show"] = $show;
        return $this;
    }

    public function hidePaginationIfContainOnePage(bool $hide = true): DatatableCruds
    {
        $this->pagination["hideIfContainOnePage"] = $hide;
        return $this;
    }

    public function setLimits(int ...$limits): DatatableCruds
    {
        $this->limits = $limits;
        return $this;
    }

    public function formWidth(string $width): DatatableCruds
    {
        $this->formWidth = $width;
        return $this;
    }

    public function formHeight(string $height): DatatableCruds
    {
        $this->formHeight = $height;
        return $this;
    }

    public function formStoreButton(string $label = 'Create', string $color = 'primary'): DatatableCruds
    {
        $this->formStoreButton = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }

    public function formUpdateButton(string $label = 'Update', string $color = 'primary'): DatatableCruds
    {
        $this->formUpdateButton = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }

    public function formDeleteButton(string $label = 'Delete', string $color = 'danger'): DatatableCruds
    {
        $this->formDeleteButton = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }

    public function setBladeExtendsName(string $bladeExtendsName): DatatableCruds
    {
        $this->bladeExtendsName = $bladeExtendsName;
        return $this;
    }

    public function setBladeSectionName(string $bladeSectionName): DatatableCruds
    {
        $this->bladeSectionName = $bladeSectionName;
        return $this;
    }

    public function pushSectionToBlade(string $name, mixed $value): DatatableCruds
    {
        $this->bladeSections[] = ['name' => $name, 'value' => $value];
        return $this;
    }

    public function pushStackToBlade(string $name, mixed $value): DatatableCruds
    {
        $this->bladeStacks[] = ['name' => $name, 'value' => $value];
        return $this;
    }
}
