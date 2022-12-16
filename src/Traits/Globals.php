<?php

namespace Exist404\DatatableCruds\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;

trait Globals
{
    /**
     * Datatable model
    */
    public function for(Builder|string $model): self
    {
        if (is_string($model)) {
            $model = (new $model())->query();
        }

        $this->model = $model;
        $this->tableName = $model->getModel()->getTable();

        if (!$this->pageTitle) {
            $this->setPageTitle(str($this->tableName)->headline());
        }
        return $this;
    }
    /**
     * Datatable page title
    */
    public function setPageTitle(string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;
        return $this;
    }
    /**
     * Datatable direction
    */
    public function setDir(string|callable $dir): self
    {
        $this->dir = $dir;
        return $this;
    }
    /**
     * Request Headers
    */
    public function setHeader(string $name, string $value): self
    {
        $this->headers = array_merge($this->headers, [$name => $value]);
        return $this;
    }
    /**
     * Relations to load with model
    */
    public function with(mixed ...$with): self
    {
        foreach ($with as $index => $value) {
            $this->with .= $index == 0 ? $value : '|' . $value;
        }
        return $this;
    }
    /**
     * Columns names to search by
    */
    public function searchBy(mixed ...$searchBy): self
    {
        $this->searchBy = array_merge($this->searchBy, $searchBy);
        return $this;
    }
    /**
     * Set get route
    */
    public function setGetRoute(string $route): self
    {
        $this->routes['get'] = $route;
        return $this;
    }
    /**
     * Set store route
    */
    public function setStoreRoute(string $route, string $method = 'post'): self
    {
        $this->routes['store'] = $route;
        $this->routesMethods['store'] = $method;
        return $this;
    }
    /**
     * Set update route
    */
    public function setUpdateRoute(string $route, string $method = 'patch'): self
    {
        $this->routes['update'] = $route;
        $this->routesMethods['update'] = $method;
        return $this;
    }
    /**
     * Set delete route
    */
    public function setDeleteRoute(string $route, string $method = 'delete'): self
    {
        $this->routes['delete'] = $route;
        $this->routesMethods['delete'] = $method;
        return $this;
    }
    /**
     * Request FindBy Key Name
    */
    public function setRequestFindByKeyName(string $findBy): self
    {
        $this->findBy = $findBy;
        return $this;
    }
    /**
     * Default Date Format
    */
    public function setDefaultDateFormat(string $dateFormat): self
    {
        $this->dateFormat = str($dateFormat)->wrap("format(", ")");
        return $this;
    }
    /**
     * Datatable order
    */
    public function setDefaultOrder(string $orderBy = 'created_at', string $order = 'desc'): self
    {
        $this->request['order'] = $order;
        $this->request['orderBy'] = $orderBy;
        return $this;
    }
    /**
     * Update addAction
    */
    public function addAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): self
    {
        $this->addButton['onclick'] = [$onclick => $value];
        $this->addButton['html'] = $html;
        if ($html === false) {
            $this->addButton['enabled'] = false;
        }
        return $this;
    }
    /**
     * Update editAction
    */
    public function editAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): self
    {
        $this->actions["edit"]['onclick'] = [$onclick => $value];
        $this->actions["edit"]['html'] = $html;
        if ($html === false) {
            $this->actions["edit"] = false;
        }
        return $this;
    }
    /**
     * Update deleteAction
    */
    public function deleteAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): self
    {
        $this->actions["delete"]['onclick'] = [$onclick => $value];
        $this->actions["delete"]['html'] = $html;
        if ($html === false) {
            $this->actions["delete"] = false;
        }
        return $this;
    }
    /**
     * Update cloneAction
    */
    public function cloneAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): self
    {
        $this->actions["clone"]['onclick'] = [$onclick => $value];
        $this->actions["clone"]['html'] = $html;
        if ($html === false) {
            $this->actions["clone"] = false;
        }
        return $this;
    }
    /**
     * Set search debounce and class name
    */
    public function search(string $debounce, string $class = 'form-control'): self
    {
        $this->searchBar = [
            "class" => $class,
            "debounce" => $debounce
        ];
        return $this;
    }
    /**
     * Datatable export csv
    */
    public function exportCsvBtn(bool|string $csv = true, string $filename = null): self
    {
        $this->exports["csv"]["html"] = $csv;
        $this->exports["csv"]["filename"] = $filename;
        return $this;
    }
    /**
     * Datatable export excel
    */
    public function exportExcelBtn(bool|string $excel = true, string $filename = null): self
    {
        $this->exports["excel"]["html"] = $excel;
        $this->exports["excel"]["filename"] = $filename;
        return $this;
    }
    /**
     * Datatable print
    */
    public function printBtn(bool|string $print = true): self
    {
        $this->exports["print"] = $print;
        return $this;
    }
    /**
     * Default Texts
    */
    public function setText(string $key, string $text): self
    {
        $texts = &$this->texts;

        foreach (explode('.', $key) as $key) {
            $texts = &$texts[$key];
        }

        $texts = $text;

        unset($texts);

        return $this;
    }

    public function showPagination(bool $show = true): self
    {
        $this->pagination["show"] = $show;
        return $this;
    }

    public function hidePaginationIfContainOnePage(bool $hide = true): self
    {
        $this->pagination["hideIfContainOnePage"] = $hide;
        return $this;
    }
    /**
     * Set view limits
    */
    public function setLimits(mixed ...$limits): self
    {
        $this->limits = $limits;
        return $this;
    }
    /**
     * Set form width
    */
    public function formWidth(string $width): self
    {
        $this->formWidth = $width;
        return $this;
    }
    /**
     * Set form height
    */
    public function formHeight(string $height): self
    {
        $this->formHeight = $height;
        return $this;
    }
    /**
     * Update form store button
    */
    public function storeButton(string $label = 'Create', string $color = 'primary'): self
    {
        $this->storeButton = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }
    /**
     * Update form update button
    */
    public function updateButton(string $label = 'Update', string $color = 'primary'): self
    {
        $this->updateButton = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }
    /**
     * Update form delete button
    */
    public function deleteButton(string $label = 'Delete', string $color = 'danger'): self
    {
        $this->deleteButton = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }
    /**
     * Set blade extends
    */
    public function setBladeExtends(string $bladeExtends): self
    {
        $this->bladeExtends = $bladeExtends;
        return $this;
    }
    /**
     * Set blade section
    */
    public function setBladeSection(string $bladeSection): self
    {
        $this->bladeSection = $bladeSection;
        return $this;
    }
}
