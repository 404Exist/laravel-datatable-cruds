<?php

namespace Exist404\DatatableCruds\Traits;

use Exist404\DatatableCruds\Exceptions\MethodNotAllowedWithCurrentInstance;

trait Inputs
{
    /**
     * Set View Inputs.
    */
    public function setInputs(string ...$inputs): self
    {
        $this->instance = 'input';
        $this->executeMethodsFromStr(...$inputs);
        return $this;
    }
    /**
     * Create a new input
    */
    public function input(string|callable $name): self
    {
        return $this->create('input', $name);
    }
    /**
     * Set type for current input
    */
    public function type(string $type): self
    {
        $this->setInputValue('type', $type);
        return $this;
    }
    /**
     * Push input to the edit form only
    */
    public function editForm(): self
    {
        $this->setInputValue(["key" => 'form', "value" => "Edit", "calledMethodName" => __FUNCTION__]);
        return $this;
    }
    /**
     * Push input to the create form only
    */
    public function createForm(): self
    {
        $this->setInputValue(["key" => 'form', "value" => "Add", "calledMethodName" => __FUNCTION__]);
        return $this;
    }
    /**
     * Set current input page
    */
    public function page(int $page): self
    {
        for ($i = 0; $i <= $page; $i++) {
            $this->pages[$i] = [];
        }
        $this->setInputValue("page", $page);
        return $this;
    }
    /**
     * Set class for current input parent
    */
    public function parentClass(string|callable $parentClass): self
    {
        $this->setInputValue("parentClass", $parentClass);
        return $this;
    }
    /**
     * Set class for current input label
    */
    public function labelClass(string|callable $labelClass): self
    {
        $this->setInputValue("labelClass", $labelClass);
        return $this;
    }
    /**
     * Make multi select input
    */
    public function multiSelect(string $label = "name", string $val = "id", bool $multiple = true): self
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
    */
    public function select(string $label = "name", string $val = "id"): self
    {
        $this->setInputValue(["key" => 'type', "value" => "select", "calledMethodName" => __FUNCTION__]);
        $this->options([]);
        $this->setInputValue('optionVal', $val);
        $this->setInputValue('optionLabel', $label);
        return $this;
    }
    /**
     * Set options for current input (select)
    */
    public function options(array $options = []): self
    {
        $this->setInputValue("options", $options);
        return $this;
    }
    /**
     * Add onchange event to current input (select) that changes other select options
     *
     * @param string $update input (select) name to update it with data onchange current select
     * @param string $urlToGetOptions url to get data
    */
    public function onChange(string $update, string $urlToGetOptions): self
    {
        $this->setInputValue('onChange', ['update' => $update, 'getDataFrom' => $urlToGetOptions]);
        return $this;
    }
    /**
     * Make dropzone input
    */
    public function dropzone(array $dropZoneAttributes = []): self
    {
        $this->setInputValue(["key" => 'type', "value" => "drop_zone", "calledMethodName" => __FUNCTION__]);
        $this->setInputValue('dropZoneAttributes', $dropZoneAttributes);
        $this->setInputValue('oldVal', $dropZoneAttributes["idColumn"] ?? "id");
        $this->setInputValue('path', $dropZoneAttributes["path"] ?? "");
        return $this;
    }

    /**
     * Make checkbox input
    */
    public function checkbox(bool|string|int $selectedValue = true, bool|string|int $unselectedValue = false): self
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
    */
    public function radio($value): self
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
    */
    public function tags(): self
    {
        $this->setInputValue(["key" => 'type', "value" => "tags", "calledMethodName" => __FUNCTION__]);
        return $this;
    }
    /**
     * Make editor input
    */
    public function editor(string|null $value = null): self
    {
        $value = $value ?: $this->input['label'];
        $this->setInputValue(["key" => 'type', "value" => "editor", "calledMethodName" => __FUNCTION__]);
        $this->attributes(['value' => $this->dynamicLabel($value)]);
        return $this;
    }
    /**
     * To fill multiselect from $optionsRoute url with data on search
    */
    public function optionsRoute(string $optionsRoute): self
    {
        $this->setInputValue(["key" => 'getDataFrom', "value" => $optionsRoute, "calledMethodName" => __FUNCTION__]);
        return $this;
    }

    private function setInputValue($data, $value = null): void
    {
        $data = is_array($data) ? $data : ["key" => $data, "value" => $value];
        if ($this->instance == "column") {
            throw MethodNotAllowedWithCurrentInstance::create("column", $data["calledMethodName"] ?? $data["key"]);
        }
        $this->setValue($data["key"], $data["value"]);
    }
}
