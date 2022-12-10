<?php

namespace Exist404\DatatableCruds\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Exist404\DatatableCruds\DatatableCruds render(array $extendsData = []): \Illuminate\Contracts\View\View|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
 * @method static \Exist404\DatatableCruds\DatatableCruds renderData(): array
 * @method static \Exist404\DatatableCruds\DatatableCruds setModel(string $model): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setPageTitle(string $title): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setDir(string|callable $dir): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setHeader(string $name, string $value): self
 * @method static \Exist404\DatatableCruds\DatatableCruds with(mixed ...$with): self
 * @method static \Exist404\DatatableCruds\DatatableCruds searchBy(mixed ...$searchBy): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setGetRoute(string $route): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setStoreRoute(string $route, string $method = 'post'): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setUpdateRoute(string $route, string $method = 'patch'): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setDeleteRoute(string $route, string $method = 'delete'): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setRequestFindByKeyName(string $findBy): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setDefaultDateFormat(string $dateFormat): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setDefaultOrder(string $orderBy = 'created_at', string $order = 'desc'): self
 * @method static \Exist404\DatatableCruds\DatatableCruds addAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds editAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds deleteAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds cloneAction(string|bool $html = null, string $onclick = 'openModal', string|bool $value = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds search(string $debounce, string $class = 'form-control'): self
 * @method static \Exist404\DatatableCruds\DatatableCruds exportCsvBtn(bool|string $csv = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds exportExcelBtn(bool|string $excel = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds printBtn(bool|string $print = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setText(string $key, string $text): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setLimits(mixed ...$limits): self
 * @method static \Exist404\DatatableCruds\DatatableCruds formWidth(string $width): self
 * @method static \Exist404\DatatableCruds\DatatableCruds formHeight(string $height): self
 * @method static \Exist404\DatatableCruds\DatatableCruds storeButton(string $label = 'Create', string $color = 'primary'): self
 * @method static \Exist404\DatatableCruds\DatatableCruds updateButton(string $label = 'Update', string $color = 'primary'): self
 * @method static \Exist404\DatatableCruds\DatatableCruds deleteButton(string $label = 'Delete', string $color = 'danger'): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setBladeExtends(string $extends): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setBladeSection(string $section): self
 * @method static \Exist404\DatatableCruds\DatatableCruds label(string $label): self
 * @method static \Exist404\DatatableCruds\DatatableCruds html(string|callable $html = ''): self
 * @method static \Exist404\DatatableCruds\DatatableCruds attributes(array $attributes): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setAttribute(string $name, string $value): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setColumns(string ...$columns): self
 * @method static \Exist404\DatatableCruds\DatatableCruds column(string|callable $name): self
 * @method static \Exist404\DatatableCruds\DatatableCruds sortable(bool|callable $sortable = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds searchable(bool|callable $searchable = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds exportable(bool|callable $exportable = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds image(string|bool|null $path = ''): self
 * @method static \Exist404\DatatableCruds\DatatableCruds date(string|bool|null $format = null): self
 * @method static \Exist404\DatatableCruds\DatatableCruds checkall(string|bool|null $label = null): self
 * @method static \Exist404\DatatableCruds\DatatableCruds actions(string|bool|null $label = null): self
 * @method static \Exist404\DatatableCruds\DatatableCruds href(string|callable $href = ''): self
 * @method static \Exist404\DatatableCruds\DatatableCruds execHtml(string|callable $js): self
 * @method static \Exist404\DatatableCruds\DatatableCruds execHref(string|callable $js): self
 * @method static \Exist404\DatatableCruds\DatatableCruds setInputs(string ...$inputs): self
 * @method static \Exist404\DatatableCruds\DatatableCruds input(string|callable $name): self
 * @method static \Exist404\DatatableCruds\DatatableCruds type(string $type): self
 * @method static \Exist404\DatatableCruds\DatatableCruds editForm(): self
 * @method static \Exist404\DatatableCruds\DatatableCruds createForm(): self
 * @method static \Exist404\DatatableCruds\DatatableCruds page(int $page): self
 * @method static \Exist404\DatatableCruds\DatatableCruds parentClass(string|callable $parentClass): self
 * @method static \Exist404\DatatableCruds\DatatableCruds labelClass(string|callable $labelClass): self
 * @method static \Exist404\DatatableCruds\DatatableCruds multiSelect(string $label = "name", string $val = "id", bool $multiple = true): self
 * @method static \Exist404\DatatableCruds\DatatableCruds select(string $label = "name", string $val = "id"): self
 * @method static \Exist404\DatatableCruds\DatatableCruds options(array $options = []): self
 * @method static \Exist404\DatatableCruds\DatatableCruds onChange(string $update, string $urlToGetOptions): self
 * @method static \Exist404\DatatableCruds\DatatableCruds dropzone(array $dropZoneAttributes = []): self
 * @method static \Exist404\DatatableCruds\DatatableCruds checkbox(bool|string|int $selectedValue = true, bool|string|int $unselectedValue = false): self
 * @method static \Exist404\DatatableCruds\DatatableCruds radio($value): self
 * @method static \Exist404\DatatableCruds\DatatableCruds tags(): self
 * @method static \Exist404\DatatableCruds\DatatableCruds editor(string|null $value = null): self
 * @method static \Exist404\DatatableCruds\DatatableCruds optionsRoute(string $optionsRoute): self
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
