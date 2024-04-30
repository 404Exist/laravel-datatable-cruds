<?php

namespace Exist404\DatatableCruds\Traits;

use Closure;
use Exist404\DatatableCruds\DatatableCruds;
use Exist404\DatatableCruds\Exceptions\MethodNotAllowedWithCurrentInstance;

trait Columns
{
    /**
     * Create a new column
    */
    public function column(string|Closure $name, ?Closure $callback = null): DatatableCruds
    {
        if ($callback) {
            $this->create('column', $name);
            $this->setColumnValue(["key" => 'callback', "value" => $callback]);
            return $this;
        }
        return $this->create('column', $name);
    }
    /**
     * Push columns from other instance
    */
    public function columns(self $instance): DatatableCruds
    {
        $this->addCurrentInstance();

        $instance->addCurrentInstance();

        $this->columns = $instance->columns;

        return $this;
    }
    /**
     * Set sortable for current column
    */
    public function sortable(bool|Closure $sortable = true): DatatableCruds
    {
        $this->setColumnValue(["key" => 'sortable', "value" => $sortable]);
        return $this;
    }
    /**
     * Set searchable for current column
    */
    public function searchable(bool|Closure $searchable = true): DatatableCruds
    {
        $this->setColumnValue(["key" => 'searchable', "value" => $searchable]);
        return $this;
    }
    /**
     * Set exportable for current column
    */
    public function exportable(bool|Closure $exportable = true): DatatableCruds
    {
        $this->setColumnValue(["key" => 'exportable', "value" => $exportable]);
        return $this;
    }
    /**
     * Specify that current column is image
    */
    public function image(string|bool|null $path = ''): DatatableCruds
    {
        $isImage = !$path && $path !== '' ? false : true;

        $this->setColumnValue(["key" => 'isImage', "value" => $isImage, "calledMethodName" => __FUNCTION__]);

        if ($isImage) {
            $this->setColumnValue('path', $path);
        }
        return $this;
    }

    public function href(string|Closure $href = '', string $target = "_self"): DatatableCruds
    {
        $this->setColumnValue(["key" => 'href', "value" => $href]);
        $this->setColumnValue(["key" => 'target', "value" => $target]);
        return $this;
    }
    /**
     * Specify that current column is date
    */
    public function date(string|bool|null $format = null, string $invalid = 'Invalid Date'): DatatableCruds
    {
        $isDate = $format === false ? false : true;

        $this->setColumnValue(["key" => 'isDate', "value" => $isDate, "calledMethodName" => __FUNCTION__]);
        $this->setColumnValue(["key" => 'invaildDateText', "value" => $invalid, "calledMethodName" => __FUNCTION__]);

        $this->setColumnValue('format', $this->dateFormat);
        if ($format) {
            $this->setColumnValue('format', str($format)->wrap("format(", ")"));
        }
        return $this;
    }
    /**
     * Specify that current column is checkall
    */
    public function checkall(string|bool|null $label = null): DatatableCruds
    {
        $isSelect = $label === false ? false : true;
        $this->setColumnValue(["key" => 'isSelect', "value" => $isSelect, "calledMethodName" => __FUNCTION__]);

        if ($label) {
            $this->label($label);
        }
        return $this;
    }
    /**
     * Specify that current column is actions
    */
    public function actions(string|bool|null $label = null): DatatableCruds
    {
        $isAction = $label === false ? false : true;
        $this->setColumnValue(["key" => 'isAction', "value" => $isAction, "calledMethodName" => __FUNCTION__]);

        if ($label) {
            $this->label($label);
        }
        return $this;
    }
    /**
     * Execute javascript functions and push returned value to (html)
    */
    public function jsToHtml(string|Closure $js): DatatableCruds
    {
        $this->setColumnValue([
            "key" => "html",
            "value" => str($js)->wrap("{exec(", ")}"),
            "calledMethodName" => __FUNCTION__,
            "shouldAppend" => true
        ]);
        return $this;
    }
    /**
     * Execute javascript functions and push returned value to (href)
    */
    public function jsToHref(string|Closure $js): DatatableCruds
    {
        $this->setColumnValue([
            "key" => "href",
            "value" => str($js)->wrap("{exec(", ")}"),
            "calledMethodName" => __FUNCTION__,
            "shouldAppend" => true
        ]);
        return $this;
    }

    private function setColumnValue(array|string $data, string|null $value = null): void
    {
        $data = is_array($data) ? $data : ["key" => $data, "value" => $value];

        if ($this->instance == "input") {
            throw MethodNotAllowedWithCurrentInstance::create("input", $data["calledMethodName"] ?? $data["key"]);
        }

        $this->setValue($data["key"], $data["value"], $data["shouldAppend"] ?? false);
    }
}
