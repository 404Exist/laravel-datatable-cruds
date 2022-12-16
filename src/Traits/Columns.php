<?php

namespace Exist404\DatatableCruds\Traits;

use Exist404\DatatableCruds\Exceptions\MethodNotAllowedWithCurrentInstance;
use Illuminate\Support\Arr;

trait Columns
{
    /**
     * Set View Columns.
    */
    public function setColumns(string ...$columns): self
    {
        $this->instance = 'column';
        $this->executeMethodsFromStr(...$columns);
        return $this;
    }
    /**
     * Create a new column
    */
    public function column(string|callable $name): self
    {
        return $this->create('column', $name);
    }
    /**
     * Push columns from other instance
    */
    public function columns(self $instance): self
    {
        $this->addCurrentInstance();
        $instance->addCurrentInstance();

        if (array_diff(Arr::dot($this->columns), Arr::dot($instance->columns))) {
            $instance->columns = array_merge($this->columns, $instance->columns);
        }

        $this->columns = $instance->columns;

        return $this;
    }
    /**
     * Set sortable for current column
    */
    public function sortable(bool|callable $sortable = true): self
    {
        $this->setColumnValue(["key" => 'sortable', "value" => $sortable]);
        return $this;
    }
    /**
     * Set searchable for current column
    */
    public function searchable(bool|callable $searchable = true): self
    {
        $this->setColumnValue(["key" => 'searchable', "value" => $searchable]);
        return $this;
    }
    /**
     * Set exportable for current column
    */
    public function exportable(bool|callable $exportable = true): self
    {
        $this->setColumnValue(["key" => 'exportable', "value" => $exportable]);
        return $this;
    }
    /**
     * Specify that current column is image
    */
    public function image(string|bool|null $path = ''): self
    {
        $isImage = !$path && $path !== '' ? false : true;

        $this->setColumnValue(["key" => 'isImage', "value" => $isImage, "calledMethodName" => __FUNCTION__]);

        if ($isImage) {
            $this->setColumnValue('path', $path);
        }
        return $this;
    }

    public function href(string|callable $href = ''): self
    {
        $this->setColumnValue(["key" => 'href', "value" => $href]);
        return $this;
    }
    /**
     * Specify that current column is date
    */
    public function date(string|bool|null $format = null): self
    {
        $isDate = $format === false ? false : true;

        $this->setColumnValue(["key" => 'isDate', "value" => $isDate, "calledMethodName" => __FUNCTION__]);

        $this->setColumnValue('format', $this->dateFormat);
        if ($format) {
            $this->setColumnValue('format', str($format)->wrap("format(", ")"));
        }
        return $this;
    }
    /**
     * Specify that current column is checkall
    */
    public function checkall(string|bool|null $label = null): self
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
    public function actions(string|bool|null $label = null): self
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
    public function execHtml(string|callable $js): self
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
    public function execHref(string|callable $js): self
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
