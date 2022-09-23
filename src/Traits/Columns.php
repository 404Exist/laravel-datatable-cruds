<?php

namespace Exist404\DatatableCruds\Traits;

use Exist404\DatatableCruds\Exceptions\MethodNotAllowedWithCurrentInstance;

trait Columns
{
    /**
     * Set View Columns.
     *
     * @param mixed ...$columns
     * @return $this
    */
    public function setColumns(mixed ...$columns)
    {
        $this->instance = 'column';
        $this->executeMethodsFromStr(...$columns);
        return $this;
    }
    /**
     * Create a new column
     *
     * @param string $name
     * @return $this
    */
    public function column(string $name)
    {
        return $this->create('column', $name);
    }
    /**
     * Set sortable for current column
     *
     * @param bool $sortable
     * @return $this
    */
    public function sortable(bool $sortable = true)
    {
        $this->setColumnValue(["key" => 'sortable', "value" => $sortable]);
        return $this;
    }
    /**
     * Set searchable for current column
     *
     * @param bool $searchable
     * @return $this
    */
    public function searchable(bool $searchable = true)
    {
        $this->setColumnValue(["key" => 'searchable', "value" => $searchable]);
        return $this;
    }
    /**
     * Set exportable for current column
     *
     * @param bool $exportable
     * @return $this
    */
    public function exportable(bool $exportable = true)
    {
        $this->setColumnValue(["key" => 'exportable', "value" => $exportable]);
        return $this;
    }
    /**
     * Specify that current column is image
     *
     * @param string|bool|null $path
     * @return $this
    */
    public function image(string|bool|null $path = '')
    {
        $isImage = !$path && $path !== '' ? false : true;

        $this->setColumnValue(["key" => 'isImage', "value" => $isImage, "calledMethodName" => __FUNCTION__]);

        if ($isImage) {
            $this->setColumnValue('path', $path);
        }
        return $this;
    }
    /**
     * Add href to current column
     *
     * @param string $href
     * @return $this
    */
    public function href(string $href = '')
    {
        $this->setColumnValue(["key" => 'href', "value" => $href]);
        return $this;
    }
    /**
     * Specify that current column is date
     *
     * @param string|bool|null $format
     * @return $this
    */
    public function date(string|bool|null $format = null)
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
     *
     * @param string|bool|null $label
     * @return $this
    */
    public function checkall(string|bool|null $label = null)
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
     *
     * @param string|bool|null $label
     * @return $this
    */
    public function actions(string|bool|null $label = null)
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
     *
     * @param string $js
     * @return $this
    */
    public function execHtml(string $js)
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
     *
     * @param string $js
     * @return $this
    */
    public function execHref(string $js)
    {
        $this->setColumnValue([
            "key" => "href",
            "value" => str($js)->wrap("{exec(", ")}"),
            "calledMethodName" => __FUNCTION__,
            "shouldAppend" => true
        ]);
        return $this;
    }

    private function setColumnValue($data, $value = null)
    {
        $data = is_array($data) ? $data : ["key" => $data, "value" => $value];

        if ($this->instance == "input") {
            throw MethodNotAllowedWithCurrentInstance::create("input", $data["calledMethodName"] ?? $data["key"]);
        }

        $this->setValue($data["key"], $data["value"], $data["shouldAppend"] ?? false);
    }
}
