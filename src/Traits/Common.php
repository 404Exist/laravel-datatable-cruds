<?php

namespace Exist404\DatatableCruds\Traits;

use Exist404\DatatableCruds\DatatableCruds;

trait Common
{
    /**
     * Set label for current instance
    */
    public function label(string $label): DatatableCruds
    {
        $this->setValue('label', $label);

        $this->setInputPlaceholderIfNotExist($label);

        return $this;
    }
    /**
     * Use html tags with current instance
    */
    public function html(string|callable $html = ''): DatatableCruds
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
    */
    public function attributes(array $attributes): DatatableCruds
    {
        if (!isset($this->{$this->instance}['attributes'])) {
            $this->{$this->instance}['attributes'] = [];
        }

        $this->setValue('attributes', $attributes, true);
        return $this;
    }
    /**
     * Apply html tag attributes to current instance
    */
    public function setAttribute(string $name, string $value): DatatableCruds
    {
        $this->attributes([$name => $value]);
        return $this;
    }

    protected function setInputPlaceholderIfNotExist(string $placeholder): void
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
