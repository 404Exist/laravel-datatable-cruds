<?php

namespace Exist404\DatatableCruds\Traits;

Trait Inputs {
    /**
     * Fill $this->inputs with current model all fillable columns.
     *
     * @param  integer|null $maxInputsPerPage
     * @param  string|null $parentClass
     * @return $this
    */
    public function fillInputs($maxInputsPerPage = null, $parentClass = "mb-3")
    {
        if (!$this->model) return $this->exception('You must send valid model class to use fillInputs method');
        $this->instance = 'input';
        $fillables = $this->getFillableByTypes('array', 'object');
        foreach($fillables as $index => $fillable) {
            if ($maxInputsPerPage && count($fillables) > intval($maxInputsPerPage)) {
                $index += 1;
                $page = ceil($index / intval($maxInputsPerPage));
            }
            array_push($this->inputs, [
                'label' => $this->dynamicLabel($fillable),
                'name' => $fillable,
                'type' => $fillable == 'password' ? 'password' : ($fillable == 'email' ? 'email' : 'text'),
                'attributes' => ['placeholder' => $this->dynamicLabel($fillable)],
                'page' => isset($page) ? $page : '',
                'parentClass' => $parentClass ? $parentClass : ''
            ]);
        }
        if (isset($page)) {
            for ($i = 0; $i < $page; $i++) {
                $this->pages[$i] = [];
            }
        }
        return $this;
    }
    /**
     * Create a new input
     *
     * @return $this->create('input')
    */
    public function input($name) {
        return $this->create('input', $name);
    }
    /**
     * Update an exist input
     *
     * @param  string|null $name
     * @return $this->update('input', $name)
    */
    public function updateInput($name) {
        return $this->update('input', $name);
    }
    /**
     * Delete an exist input
     *
     * @param  string|null $name
     * @return $this->delete('input', $name)
    */
    public function deleteInput($name) {
        return $this->delete('input', $name);
    }
    /**
     * Set type for current input
     *
     * @param  string $type
     * @return $this
    */
    public function type($type) {
        $this->instanceMethod('column', 'type');
        $this->setValue('type', $type);
        return $this;
    }
    /**
     * Form to add input with
     *
     * @param  string $form (add, edit)
     * @return $this
    */
    public function form($form) {
        $this->instanceMethod('column', 'form');
        $this->setValue('form', trim(ucwords(strtolower($form))));
        return $this;
    }
    /**
     * Set current input page
     *
     * @param  int $page
     * @return $this
    */
    public function page(int $page) {
        $this->instanceMethod('column', 'page');
        for ($i = 0; $i <= $page; $i++) {
            $this->pages[$i] = [];
        }
        $this->setValue('page', $page);
        return $this;
    }
    /**
     * Set class for current input parent
     *
     * @param  string $parentClass
     * @return $this
    */
    public function parentClass($parentClass) {
        $this->instanceMethod('column', 'parentClass');
        $this->setValue('parentClass', $parentClass);
        return $this;
    }
    /**
     * Set class for current input label
     *
     * @param  string $labelClass
     * @return $this
    */
    public function labelClass($labelClass) {
        $this->instanceMethod('column', 'labelClass');
        $this->setValue('labelClass', $labelClass);
        return $this;
    }
    /**
     * Make multi select input
     *
     * @param  string $label
     * @param  string $val
     * @param  bool $multiple
     * @return $this
    */
    public function multiSelect($label = "name", $val = "id", $multiple = true) {
        $this->instanceMethod('column', 'multiSelect');
        $this->type('multi-select');
        $attributes = [
            'multiple' => $multiple,
            'internal-search' => true,
            'hide-selected' => false,
            'close-on-select' => false
        ];
        $this->attributes($attributes);
        $this->options([]);
        $this->setValue('optionVal', $val);
        $this->setValue('optionLabel', $label);
        return $this;
    }
    /**
     * Make select input
     *
     * @param  string $label
     * @param  string $val
     * @return $this
    */
    public function select($label = "name", $val = "id") {
        $this->instanceMethod('column', 'select');
        $this->type('select');
        $this->options([]);
        $this->setValue('optionVal', $val);
        $this->setValue('optionLabel', $label);
        return $this;
    }
    /**
     * Set options for current input (select)
     *
     * @param  array $options
     * @return $this
    */
    public function options($options = []) {
        $this->instanceMethod('column', 'options');
        $this->setMethodName('options');
        $this->setValue('options', $options);
        return $this;
    }
    /**
     * Add onchange event to current input (select)
     *
     * @param  string $update input (select) name to update it with data onchange current select
     * @param  string $getDataFrom url to get data
     * @return $this
    */
    public function onChange($update, $getDataFrom) {
        $this->instanceMethod('column', 'onChange');
        $this->methodsToApplyAfter('onChange', 'options');
        $this->setValue('onChange', [
            'update' => $update,
            'getDataFrom' => $getDataFrom,
        ]);
        return $this;
    }
    /**
     * Make dropzone input
     *
     * @param  array $dropZoneAttributes
     * @return $this
    */
    public function dropzone($dropZoneAttributes = []) {
        $this->setMethodName('dropzone');
        $this->instanceMethod('column', 'dropzone');
        $this->type('drop_zone');
        $this->setValue('dropZoneAttributes', $dropZoneAttributes);
        $this->setValue('oldVal', $dropZoneAttributes["idColumn"] ?? "id");
        $this->setValue('path', $dropZoneAttributes["path"] ?? "");
        return $this;
    }

    public function checkbox($selectedValue = true, $unselectedValue = false) {
        $this->instanceMethod('column', 'checkbox');
        $this->type('checkbox');
        $attributes = [
            'value' => $selectedValue,
            'unSelectValue' => $unselectedValue,
        ];
        $this->attributes($attributes);
        return $this;
    }

    public function radio($value = "") {
        $this->instanceMethod('column', 'radio');
        $this->type('radio');
        $attributes = [
            'name' => $this->{$this->instance}["name"],
            'value' => $value,
        ];
        $this->attributes($attributes);
        return $this;
    }

    public function tags() {
        $this->instanceMethod('column', 'tags');
        $this->type('tags');
        return $this;
    }
    public function editor($value = null) {
        $value = $value ?: $this->{$this->instance}['label'];
        $this->instanceMethod('column', 'editor');
        $this->type('editor');
        $this->attributes(['value' => $this->dynamicLabel($value)]);
        return $this;
    }
    /**
     * To fill multiselect from $getDataFrom url with data on search
     *
     * @param  string $getDataFrom
     * @return $this
    */
    public function optionsRoute($optionsRoute) {
        $this->instanceMethod('column', 'optionsRoute');
        if ($this->input['type'] != 'multi-select' && $this->input['type'] != 'multiselect')
            return $this->exception('optionsRoute method works with input type multiselect only');
        $this->setValue('getDataFrom', $optionsRoute);
        return $this;
    }
}
