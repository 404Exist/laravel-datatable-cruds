<?php

namespace Exist404\DatatableCruds\Traits;

Trait Inputs {
    /**
     * Fill $this->inputs with current model all fillable columns.
     *
     * @param  int|null $maxInputsPerPage
     * @param  string|null $parentClass
     * @return $this
    */
    public function fillInputs(int|null $maxInputsPerPage = null, string|null $parentClass = "mb-3")
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
     * @return $this->create('input')
    */
    public function input(string $name) {
        return $this->create('input', $name);
    }
    /**
     * Update an exist input
     *
     * @param  string|null $name
     * @return $this->update('input', $name)
    */
    public function updateInput(string $name) {
        return $this->update('input', $name);
    }
    /**
     * Delete an exist input
     *
     * @param  string|null $name
     * @return $this->delete('input', $name)
    */
    public function deleteInput(string $name) {
        return $this->delete('input', $name);
    }
    /**
     * Set type for current input
     *
     * @param  string $type
     * @return $this
    */
    public function type(string $type) {
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
    public function form(string $form) {
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
    public function parentClass(string $parentClass) {
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
    public function labelClass(string $labelClass) {
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
    public function multiSelect(string $label = "name", string $val = "id", bool $multiple = true) {
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
    public function select(string $label = "name", string $val = "id") {
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
    public function options(array $options = []) {
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
    public function onChange(string $update, string $getDataFrom) {
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
    public function dropzone(array $dropZoneAttributes = []) {
        $this->setMethodName('dropzone');
        $this->instanceMethod('column', 'dropzone');
        $this->type('drop_zone');
        $this->setValue('dropZoneAttributes', $dropZoneAttributes);
        $this->setValue('oldVal', $dropZoneAttributes["idColumn"] ?? "id");
        $this->setValue('path', $dropZoneAttributes["path"] ?? "");
        return $this;
    }

    /**
     * Make checkbox input
     *
     * @param  bool $selectedValue = true
     * @param  bool $unselectedValue = false
     * @return $this
    */
    public function checkbox(bool $selectedValue = true, bool $unselectedValue = false) {
        $this->instanceMethod('column', 'checkbox');
        $this->type('checkbox');
        $attributes = [
            'value' => $selectedValue,
            'unSelectValue' => $unselectedValue,
        ];
        $this->attributes($attributes);
        return $this;
    }

    /**
     * Make radio input
     *
     * @param  $value
     * @return $this
    */
    public function radio($value) {
        $this->instanceMethod('column', 'radio');
        $this->type('radio');
        $attributes = [
            'name' => $this->{$this->instance}["name"],
            'value' => $value,
        ];
        $this->attributes($attributes);
        return $this;
    }

    /**
     * Make tags input
     *
     * @return $this
    */
    public function tags() {
        $this->instanceMethod('column', 'tags');
        $this->type('tags');
        return $this;
    }
    /**
     * Make editor input
     *
     * @param string|null $value
     * @return $this
    */
    public function editor(string|null $value = null) {
        $value = $value ?: $this->{$this->instance}['label'];
        $this->instanceMethod('column', 'editor');
        $this->type('editor');
        $this->attributes(['value' => $this->dynamicLabel($value)]);
        return $this;
    }
    /**
     * To fill multiselect from $optionsRoute url with data on search
     *
     * @param  string $optionsRoute
     * @return $this
    */
    public function optionsRoute(string $optionsRoute) {
        $this->instanceMethod('column', 'optionsRoute');
        if ($this->input['type'] != 'multi-select' && $this->input['type'] != 'multiselect')
            return $this->exception('optionsRoute method works with input type multiselect only');
        $this->setValue('getDataFrom', $optionsRoute);
        return $this;
    }
}
