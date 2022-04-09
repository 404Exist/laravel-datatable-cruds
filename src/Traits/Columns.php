<?php

namespace Exist404\DatatableCruds\Traits;

use Illuminate\Support\Facades\Schema;

Trait Columns {
    /**
     * Fill $this->columns with current model all columns.
     *
     * @return $this
    */
    public function fillColumns()
    {
        if (!$this->model) return $this->exception('You must send valid model class to use fillColumns method');
        $this->instance = 'column';
        $model = new $this->model();
        $columns = Schema::getColumnListing($model->getTable());
        foreach($columns as $column) {
            $isDate = false;
            if (explode('_', $column)[count(explode('_', $column)) - 1] == 'at') $isDate = true;
            if (!$isDate && $this->checkCast($column, 'datetime', 'date')) $isDate = true;
            if (!in_array($column, $model->getHidden()) && !$this->checkCast($column, 'array', 'object')) {
                array_push($this->columns, [
                    'label' => $this->dynamicLabel($column),
                    'name' => $column,
                    'isDate' => $isDate,
                    'sortable' => true,
                    'searchable' => true,
                ]);
            }
        }
        $this->column("datatablecruds-checkall")->checkall("Select")->add()->column("datatablecruds-actions")->actions("Action")->add();
        return $this;
    }
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
     * @param  string $name
     * @return $this->create('column')
    */
    public function column(string $name) {
        return $this->create('column', $name);
    }
    /**
     * Update an exist column
     *
     * @param  string $name
     * @return $this->update('column', $name)
    */
    public function updateColumn(string $name) {
        return $this->update('column', $name);
    }
    /**
     * Delete an exist column
     *
     * @param  string $name
     * @return $this->delete('column', $name)
    */
    public function deleteColumn(string $name) {
        return $this->delete('column', $name);
    }
    /**
     * Set sortable for current column
     *
     * @param  bool $sortable
     * @return $this
    */
    public function sortable(bool $sortable = true) {
        $this->instanceMethod('input', 'sortable');
        $this->setValue('sortable', $sortable);
        return $this;
    }
    /**
     * Set searchable for current column
     *
     * @param  bool $searchable
     * @return $this
    */
    public function searchable(bool $searchable = true) {
        $this->instanceMethod('input', 'searchable');
        $this->setValue('searchable', $searchable);
        return $this;
    }
    /**
     * Specify that current column is image
     *
     * @param  string|bool|null $path
     * @return $this
    */
    public function image(string|bool|null $path = '') {
        $this->setMethodName('image');
        $this->instanceMethod('input', 'image');
        $isImage = !$path && $path !== '' ? false : true;
        if ($isImage) $this->setValue('path', $path);
        $this->setValue('isImage', $isImage);
        return $this;
    }
    /**
     * Add href to current column
     *
     * @param  string $href
     * @return $this
    */
    public function href(string $href = '') {
        $this->instanceMethod('input', 'href');
        $this->setMethodName('href');
        $this->setValue('href', $href);
        return $this;
    }
    /**
     * Specify that current column is date
     *
     * @param  string|bool|null $format
     * @return $this
    */
    public function date(string|bool|null $format = null) {
        $this->instanceMethod('input', 'date');
        $isDate = $format === false ? false : true;
        $this->setValue('isDate', $isDate);
        if ($format) $this->setValue('format', 'format('.$format.')');
        else $this->setValue('format', 'fromNow()');
        return $this;
    }
    /**
     * Specify that current column is checkall
     *
     * @param  string|bool|null $label
     * @return $this
    */
    public function checkall(string|bool|null $label = null) {
        $this->instanceMethod('input', 'checkall');
        $isSelect = $label === false ? false : true;
        $this->setValue('isSelect', $isSelect);
        if ($label) $this->label($label);
        return $this;
    }
    /**
     * Specify that current column is actions
     *
     * @param  string|bool|null $label
     * @return $this
    */
    public function actions(string|bool|null $label = null) {
        $this->instanceMethod('input', 'actions');
        $isAction = $label === false ? false : true;
        $this->setValue('isAction', $isAction);
        if ($label) $this->label($label);
        return $this;
    }
    /**
     * Execute javascript functions and push returned value to (html ||  href) for current instance
     *
     * @param  string $js
     * @param  string|null $to (html ||  href)
     * @return $this
    */
    public function exec(string $js, string|null $to = null) {
        $this->instanceMethod('input', 'exec');
        $to = $to ?: $this->methodName;
        if (!$to) $this->methodsToApplyAfter('exec', 'html', 'href');
        $js = '{exec('.$js.')}';
        $this->setValue($to, $js, true);
        return $this;
    }
}
