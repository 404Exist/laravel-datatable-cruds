<?php

namespace Exist404\DatatableCruds\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Exist404\DatatableCruds\DatatableCruds render(array $extendsData = []): \Illuminate\Contracts\View\View|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
 * @method static \Exist404\DatatableCruds\DatatableCruds renderData(): array
 * @method static \Exist404\DatatableCruds\DatatableCruds dump(): mixed
 * @method static \Exist404\DatatableCruds\DatatableCruds dd(): void
 * @method static \Exist404\DatatableCruds\DatatableCruds for(\Illuminate\Contracts\Database\Eloquent\Builder|string $model): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setPageTitle(string $title): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setDir(string|callable $dir): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setHeader(string $name, string $value): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds with(string ...$with): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds searchBy(string ...$searchBy): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setGetRoute(string $route): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setStoreRoute(string $route, string $method = 'post'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setUpdateRoute(string $route, string $method = 'patch'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setDeleteRoute(string $route, string $method = 'delete'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setRequestFindByKeyName(string $findBy): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setDefaultDateFormat(string $dateFormat): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setDefaultOrder(string $orderBy = 'created_at', string $order = 'desc'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds addAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds editAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds deleteAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds cloneAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): \Exist404\DatatableCruds\DatatableCruds
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
 * @method static \Exist404\DatatableCruds\DatatableCruds storeButton(string $label = 'Create', string $color = 'primary'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds updateButton(string $label = 'Update', string $color = 'primary'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds deleteButton(string $label = 'Delete', string $color = 'danger'): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setBladeExtends(string $extends): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setBladeSection(string $section): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds label(string $label): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds html(string|callable $html = ''): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds attributes(array $attributes): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setAttribute(string $name, string $value): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setColumns(string ...$columns): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds column(string|callable $name): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds columns(self $instance): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds sortable(bool|callable $sortable = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds searchable(bool|callable $searchable = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds exportable(bool|callable $exportable = true): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds image(string|bool|null $path = ''): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds date(string|bool|null $format = null): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds checkall(string|bool|null $label = null): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds actions(string|bool|null $label = null): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds href(string|callable $href = ''): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds execHtml(string|callable $js): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds execHref(string|callable $js): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds setInputs(string ...$inputs): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds input(string|callable $name): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds inputs(self $instance): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds type(string $type): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds editForm(): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds createForm(): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds page(int $page): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds parentClass(string|callable $parentClass): \Exist404\DatatableCruds\DatatableCruds
 * @method static \Exist404\DatatableCruds\DatatableCruds labelClass(string|callable $labelClass): \Exist404\DatatableCruds\DatatableCruds
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
class DatatableCruds extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'datatablecruds';
    }
}
