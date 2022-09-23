<?php

namespace Exist404\DatatableCruds\Traits;

trait Common
{
    /**
     * Set label for current instance
     *
     * @param string $label
     * @return $this
    */
    public function label(string $label)
    {
        $this->setValue('label', $label);

        $this->setInputPlaceholderIfNotExist($label);

        return $this;
    }
    /**
     * Use html tags with current instance
     *
     * @param string $html
     * @return $this
    */
    public function html(string $html = '')
    {
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
     * @param array $attributes
     * @return $this
    */
    public function attributes(array $attributes)
    {
        if (!isset($this->{$this->instance}['attributes'])) {
            $this->{$this->instance}['attributes'] = [];
        }

        $this->setValue('attributes', $attributes, true);
        return $this;
    }
    /**
     * Apply html tag attributes to current instance
     *
     * @param string $name
     * @param string $value
     * @return $this
    */
    public function setAttribute(string $name, string $value)
    {
        $this->attributes([$name => $value]);
        return $this;
    }

    protected function setInputPlaceholderIfNotExist(string $placeholder)
    {
        if (
            $this->instance == 'input' &&
            !isset($this->input['attributes'], $this->input['attributes']['placeholder'])
        ) {
            $this->setInputValue([
                "key" => 'attributes',
                "value" => ['placeholder' => $placeholder],
                "shouldAppend" => true
            ]);
        }
    }
}
