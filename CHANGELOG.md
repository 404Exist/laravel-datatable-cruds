# Release Notes
## [v1.1.3](https://github.com/404Exist/datatable-cruds/compare/v1.1.2...v1.1.3) - 2022-12-17

### Added
- `php artisan datatablecruds:for {Model}` command has been added
### Fixed

- IDE autocompelete methods names issue fixed
- fixQueryWheresColumnsNames in ModelDataTable fixed
- Order & search by relations of relation fixed
### Removed

- `editor()` input has been removed because it's script is too large and you can set your custom one if needed.
## [v1.1.2](https://github.com/404Exist/datatable-cruds/compare/v1.1.1...v1.1.2) - 2022-12-16

### Updated

- `setModel()` updated to `for()` and it now accepts `Model::query()` or `Model::class`
## [v1.1.1](https://github.com/404Exist/datatable-cruds/compare/v1.1.0...v1.1.1) - 2022-12-16

### Added

- `dd()` & `dump()` methods have been added.
- `columns()` & `inputs()` methods have been added.
- `showPagination()` & `hidePaginationIfContainOnePage()` methods have been added.
- `datatableCruds()` helper method have been added, it returns an instance of DatatableCruds class.
- Access |current_page| and |per_page| in custom exports html.
- Ability to set exported filename.
- Ability to send model query to `dataTableOf()` helper function.
- Ability to set nested text data with `setText('delete.title', 'Delete')`.
### Fixed

- `setBladeExtends()` & `setBladeSection()` methods have been fixed.
## [v1.1.0](https://github.com/404Exist/datatable-cruds/compare/v1.0.9...v1.1.0) - 2022-12-15

### Added

- Caching script file to response
- Ability to order by morphs relations
## [v1.0.9](https://github.com/404Exist/datatable-cruds/compare/v1.0.8...v1.0.9) - 2022-12-10

### Added

- Ability to render multiple datatables in same page
- `renderData()` method have been added.
- `@datatable()` directive have been added.

## [v1.0.8](https://github.com/404Exist/datatable-cruds/compare/v1.0.7...v1.0.8) - 2022-09-23

### Added

- `formHeight()` method have been added.
- `exportCsvBtn()` method have been added.
- `exportExcelBtn()` method have been added.
- `printBtn()` method have been added.
- `exportable()` method have been added.
- `editForm()` method have been added.
- `createForm()` method have been added.
- `execHtml()` method have been added.
- `execHref()` method have been added.
- 



### Removed

- `fillColumns()` method have been removed.
- `fillInputs()` method have been removed.
- `sort()` method have been removed.
- `except()` method have been removed.
- `after()` method have been removed.
- `before()` method have been removed.
- `form()` method have been removed.
- `exec()` method have been removed.
- `add()` method have been removed.


## [v1.0.6](https://github.com/404Exist/datatable-cruds/compare/v1.0.5...v1.0.6) - 2022-04-10

### Added

- `setBladeExtends()` and `setBladeSection()` methods have been added.
### Updated

- `render()` method now accepts one parameter only which is an array of variables that can be accessed in the layout blade file.
## [v1.0.5](https://github.com/404Exist/datatable-cruds/compare/v1.0.3...v1.0.5) - 2022-04-9

### Added

- `setColumns()` and `setInputs()` methods have been added.

