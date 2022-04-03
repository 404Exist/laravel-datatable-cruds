<?php

namespace Exist404\DatatableCruds\Traits;

Trait Common {
    /**
     * Sort columns or inputs.
     *
     * @param  mixed $sorts
     * @return $this
    */
    public function sort(...$sorts)
    {
        foreach ($sorts as $index => $sort) {
            $out = array_splice($this->{$this->instance.'s'}, array_search($sort, array_column($this->{$this->instance.'s'}, 'name')), 1);
            array_splice($this->{$this->instance.'s'}, $index, 0, $out);
        }
        return $this;
    }
    /**
     * Except columns or inputs.
     *
     * @param  mixed $excepts
     * @return $this
    */
    public function except(...$excepts)
    {
        foreach ($excepts as $except) {
            array_splice($this->{$this->instance.'s'}, array_search($except, array_column($this->{$this->instance.'s'}, 'name')), 1);
            if (isset($this->{$this->instance.'s'}[0], $this->{$this->instance.'s'}[0]['page']) && $this->{$this->instance.'s'}[0]['page'] != 1) {
                foreach($this->{$this->instance.'s'} as $index => $except) {
                    $this->{$this->instance.'s'}[$index]['page'] -= 1;
                }
            }
        }
        return $this;
    }
    /**
     * Set label for current instance
     *
     * @param  string $label
     * @return $this
    */
    public function label($label) {
        $this->setValue('label', $label);
        if ($this->instance == 'input' && !isset($this->input['attributes'], $this->input['attributes']['placeholder'])) {
            $this->setValue('attributes', ['placeholder' => $label], true);
        }
        return $this;
    }
    /**
     * Use html tags with current instance
     *
     * @param  string $html
     * @return $this
    */
    public function html($html = '') {
        $this->setMethodName('html');
        $this->setValue('isDate', false);
        $this->setValue('isImage', false);
        $this->setValue('isAction', false);
        $this->setValue('isSelect', false);
        $this->setValue('html', $html, true);
        return $this;
    }
    /**
     * Apply html tag attributes to current instance
     *
     * @param  array $attributes
     * @return $this
    */
    public function attributes($attributes)
    {
        if (!isset($this->{$this->instance}['attributes'])) $this->{$this->instance}['attributes'] = [];
        $this->setValue('attributes', $attributes, true);
        return $this;
    }
    /**
     * Apply html tag attributes to current instance
     *
     * @param  string $name
     * @param  string $value
     * @return $this
    */
    public function setAttribute($name, $value)
    {
        $this->attributes([$name => $value]);
        return $this;
    }
    /**
     * Set input or column name to push current instance after it
     *
     * @param string $after
     * @return $this
    */
    public function after($after)
    {
        if ($after && $this->instancePosition($after) !== false) {
            $this->position = $this->instancePosition($after);
            if ($this->instancePosition() > $this->instancePosition($after)) {
                $this->position = $this->instancePosition($after) + 1;
            }
            if (isset($this->{$this->instance.'s'}[$this->instancePosition($after)]['page']))
                $this->{$this->instance}['page'] = $this->{$this->instance.'s'}[$this->instancePosition($after)]['page'];
            if ($this->methodAction == 'update') {
                $this->moveElement();
            }
        }
        return $this;
    }
    /**
     * Set input or column name to push current instance after it
     *
     * @param string $after
     * @return $this
    */
    public function before($before)
    {
        if ($before && $this->instancePosition($before) !== false) {
            $this->position = $this->instancePosition($before);
            if ($this->instancePosition() < $this->instancePosition($before)) {
                $this->position = $this->instancePosition($before) - 1;
            }
            if (isset($this->{$this->instance.'s'}[$this->position]['page']))
                $this->{$this->instance}['page'] = $this->{$this->instance.'s'}[$this->position]['page'];
            if ($this->methodAction == 'update') {
                $this->moveElement();
            }
        }
        return $this;
    }
    /**
     * To push current instance to ($this->inputs, $this->columns)
     *
     * @return $this
    */
    public function add() {
        if ($this->methodAction != 'create') return $this->exception('You can use add method at create only');
        if ($this->position !== null) {
            array_splice($this->{$this->instance.'s'}, $this->position, 0, [$this->{$this->instance}]);
            $this->position = null;
        } else {
            array_push($this->{$this->instance.'s'}, $this->{$this->instance});
        }
        $this->{$this->instance} = [];
        return $this;
    }
}
