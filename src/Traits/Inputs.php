<?php

namespace Exist404\DatatableCruds\Traits;

use Exist404\DatatableCruds\Exceptions\MethodNotAllowedWithCurrentInstance;

trait Inputs
{
    /**
     * Set View Inputs.
     *
     * @param mixed ...$inputs
     * @return $this
    */
    public function setInputs(mixed ...$inputs)
    {
        $this->instance = 'input';
        $this->executeMethodsFromStr(...$inputs);
        return $this;
    }
    /**
     * Create a new input
     *
     * @param string $name
     * @return $this
    */
    public function input(string $name)
    {
        return $this->create('input', $name);
    }
    /**
     * Set type for current input
     *
     * @param string $type
     * @return $this
    */
    public function type(string $type)
    {
        $this->setInputValue('type', $type);
        return $this;
    }
    /**
     * Push input to the edit form only
     *
     * @return $this
    */
    public function editForm()
    {
        $this->setInputValue(["key" => 'form', "value" => "Edit", "calledMethodName" => __FUNCTION__]);
        return $this;
    }
    /**
     * Push input to the create form only
     *
     * @return $this
    */
    public function createForm()
    {
        $this->setInputValue(["key" => 'form', "value" => "Add", "calledMethodName" => __FUNCTION__]);
        return $this;
    }
    /**
     * Set current input page
     *
     * @param int $page
     * @return $this
    */
    public function page(int $page)
    {
        for ($i = 0; $i <= $page; $i++) {
            $this->pages[$i] = [];
        }
        $this->setInputValue("page", $page);
        return $this;
    }
    /**
     * Set class for current input parent
     *
     * @param string $parentClass
     * @return $this
    */
    public function parentClass(string $parentClass)
    {
        $this->setInputValue("parentClass", $parentClass);
        return $this;
    }
    /**
     * Set class for current input label
     *
     * @param string $labelClass
     * @return $this
    */
    public function labelClass(string $labelClass)
    {
        $this->setInputValue("labelClass", $labelClass);
        return $this;
    }
    /**
     * Make multi select input
     *
     * @param string $label
     * @param string $val
     * @param bool $multiple
     * @return $this
    */
    public function multiSelect(string $label = "name", string $val = "id", bool $multiple = true)
    {
        $this->setInputValue(["key" => 'type', "value" => "multi-select", "calledMethodName" => __FUNCTION__]);
        $this->attributes([
            'multiple' => $multiple,
            'internal-search' => true,
            'hide-selected' => false,
            'close-on-select' => false
        ]);
        $this->options([]);
        $this->setInputValue('optionVal', $val);
        $this->setInputValue('optionLabel', $label);
        return $this;
    }
    /**
     * Make select input
     *
     * @param string $label
     * @param string $val
     * @return $this
    */
    public function select(string $label = "name", string $val = "id")
    {
        $this->setInputValue(["key" => 'type', "value" => "select", "calledMethodName" => __FUNCTION__]);
        $this->options([]);
        $this->setInputValue('optionVal', $val);
        $this->setInputValue('optionLabel', $label);
        return $this;
    }
    /**
     * Set options for current input (select)
     *
     * @param array $options
     * @return $this
    */
    public function options(array $options = [])
    {
        $this->setInputValue("options", $options);
        return $this;
    }
    /**
     * Add onchange event to current input (select)
     *
     * @param string $update input (select) name to update it with data onchange current select
     * @param string $getDataFrom url to get data
     * @return $this
    */
    public function onChange(string $update, string $getDataFrom)
    {
        $this->setInputValue('onChange', ['update' => $update, 'getDataFrom' => $getDataFrom]);
        return $this;
    }
    /**
     * Make dropzone input
     *
     * @param array $dropZoneAttributes
     * @return $this
    */
    public function dropzone(array $dropZoneAttributes = [])
    {
        $this->setInputValue(["key" => 'type', "value" => "drop_zone", "calledMethodName" => __FUNCTION__]);
        $this->setInputValue('dropZoneAttributes', $dropZoneAttributes);
        $this->setInputValue('oldVal', $dropZoneAttributes["idColumn"] ?? "id");
        $this->setInputValue('path', $dropZoneAttributes["path"] ?? "");
        return $this;
    }

    /**
     * Make checkbox input
     *
     * @param $selectedValue = true
     * @param $unselectedValue = false
     * @return $this
    */
    public function checkbox($selectedValue = true, $unselectedValue = false)
    {
        $this->setInputValue(["key" => 'type', "value" => "checkbox", "calledMethodName" => __FUNCTION__]);
        $this->attributes([
            'value' => $selectedValue,
            'unSelectValue' => $unselectedValue,
        ]);
        return $this;
    }

    /**
     * Make radio input
     *
     * @param $value
     * @return $this
    */
    public function radio($value)
    {
        $this->setInputValue(["key" => 'type', "value" => "radio", "calledMethodName" => __FUNCTION__]);
        $this->attributes([
            'name' => $this->input["name"],
            'value' => $value,
        ]);
        return $this;
    }

    /**
     * Make tags input
     *
     * @return $this
    */
    public function tags()
    {
        $this->setInputValue(["key" => 'type', "value" => "tags", "calledMethodName" => __FUNCTION__]);
        return $this;
    }
    /**
     * Make editor input
     *
     * @param string|null $value
     * @return $this
    */
    public function editor(string|null $value = null)
    {
        $value = $value ?: $this->input['label'];
        $this->setInputValue(["key" => 'type', "value" => "editor", "calledMethodName" => __FUNCTION__]);
        $this->attributes(['value' => $this->dynamicLabel($value)]);
        return $this;
    }
    /**
     * To fill multiselect from $optionsRoute url with data on search
     *
     * @param string $optionsRoute
     * @return $this
    */
    public function optionsRoute(string $optionsRoute)
    {
        $this->setInputValue(["key" => 'getDataFrom', "value" => $optionsRoute, "calledMethodName" => __FUNCTION__]);
        return $this;
    }

    private function setInputValue($data, $value = null)
    {
        $data = is_array($data) ? $data : ["key" => $data, "value" => $value];
        if ($this->instance == "column") {
            throw MethodNotAllowedWithCurrentInstance::create("column", $data["calledMethodName"] ?? $data["key"]);
        }
        $this->setValue($data["key"], $data["value"]);
    }
}
