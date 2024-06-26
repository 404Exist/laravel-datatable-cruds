<?php

namespace Exist404\DatatableCruds;

/**
 * @method static \Exist404\DatatableCruds\DatatableCruds render(array|string $extendsData = []): \Illuminate\Contracts\View\View|\Illuminate\Contracts\Pagination\LengthAwarePaginator
 * @method static \Exist404\DatatableCruds\DatatableCruds renderData(): array
 * @method static \Exist404\DatatableCruds\DatatableCruds table(): \Illuminate\Contracts\Support\Htmlable
 * @method static \Exist404\DatatableCruds\DatatableCruds getResults(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
 * @method static \Exist404\DatatableCruds\DatatableCruds isXhr(): bool
 * @method static \Exist404\DatatableCruds\DatatableCruds dump(): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds dd(): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds for(\Illuminate\Contracts\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|string $model): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setPageTitle(string $title): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setDir(string|\Closure $dir): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setHeader(string $name, string $value): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds with(string ...$with): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds searchBy(string ...$searchBy): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds selectFilter(string $filterBy, array $options, string $label, string $defaultValue = null): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setGetRoute(string $route): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setStoreRoute(string $route, string $method = 'post'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setUpdateRoute(string $route, string $method = 'patch'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setDeleteRoute(string $route, string $method = 'delete'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setRequestFindByKeyName(string $findBy): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setDefaultDateFormat(string $dateFormat): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setDefaultOrder(string $orderBy = 'created_at', string $order = 'desc'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds rowAddButton(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds rowEditButton(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds rowDeleteButton(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds rowCloneButton(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds search(string $debounce, string $class = 'form-control'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds exportCsvBtn(bool|string $csv = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds exportExcelBtn(bool|string $excel = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds printBtn(bool|string $print = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setText(string $key, string $text): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds showPagination(bool $show = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds hidePaginationIfContainOnePage(bool $hide = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setLimits(int ...$limits): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds formWidth(string $width): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds formHeight(string $height): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds formStoreButton(string $label = 'Create', string $color = 'primary'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds formUpdateButton(string $label = 'Update', string $color = 'primary'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds formDeleteButton(string $label = 'Delete', string $color = 'danger'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setBladeExtendsName(string $bladeExtendsName): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setBladeSectionName(string $bladeSectionName): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds pushSectionToBlade(string $name, mixed $value): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds pushStackToBlade(string $name, mixed $value): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds label(string $label): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds html(string|\Closure $html = ''): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds attributes(array $attributes): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setAttribute(string $name, string $value): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds column(string|\Closure $name): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds indexColumn(string $name): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds columns(self $instance): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds sortable(bool|\Closure $sortable = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds searchable(bool|\Closure $searchable = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds exportable(bool|\Closure $exportable = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds image(string|bool|null $path = ''): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds date(string|bool|null $format = null, string $invalid = 'Invalid Date'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds checkall(string|bool|null $label = null): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds actions(string|bool|null $label = null): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds href(string|\Closure $href = '', string $target = "_self"): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds jsToHtml(string|\Closure $js): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds jsToHref(string|\Closure $js): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds input(string|\Closure $name): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds inputs(self $instance): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds type(string $type): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds placeholder(string $placeholder): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds editForm(): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds createForm(): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds page(int $page): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds parentClass(string|\Closure $parentClass): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds labelClass(string|\Closure $labelClass): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds multiSelect(string $label = "name", string $val = "id", bool $multiple = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds select(string $label = "name", string $val = "id"): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds options(array $options = []): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds onChange(string $update, string $urlToGetOptions): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds dropzone(array $dropZoneAttributes = []): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds checkbox(bool|string|int $selectedValue = true, bool|string|int $unselectedValue = false): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds radio($value): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds tags(): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds optionsRoute(string $optionsRoute): \Exist404\DatatableCruds\DatatableCruds
 *
 * @see \Exist404\DatatableCruds\DatatableCruds
*/
abstract class AbstractDatatable
{
    protected DatatableCruds $datatable;

    public function __construct()
    {
        if (! isset($this->datatable)) {
            $this->datatable = new DatatableCruds();
            $this->datatable = $this->init();
        }
    }

    abstract public function init(): DatatableCruds;

    public function __call($name, $arguments)
    {
        if (! method_exists(__CLASS__, $name)) {
            return $this->datatable->$name(...$arguments);
        }
    }
}
