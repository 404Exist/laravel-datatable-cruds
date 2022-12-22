<?php

return [
    "bladeExtendsName" => "app",
    "bladeSectionName" => "content",
    "dir" => "ltr",
    "headers" => [],
    "formWidth" => "100%",
    "formHeight" => "100vh",
    "dateFormat" => "fromNow()",
    "findBy" => "findBy",
    "addButton" => [
        "enabled" => true,
        "html" => false,
        "onclick" => ["openModal" => true],
    ],
    "limits" => [10, 25, 50, 100],
    "searchBar" => [
        "debounce" => "500ms",
        "class" => "form-control",
    ],
    "request" => [
        "order" => "desc",
        "orderBy" => "created_at",
    ],
    "storeButton" => [
        'label' => "Create",
        'color' => "primary",
    ],
    "exports" => [
        "excel" => [
            "html" => true, // bool || html
            "filename" => null
        ],
        "csv" => [
            "html" => true, // bool || html
            "filename" => null
        ],
        "print" => true, // bool || html
    ],
    "formStoreButton" => [
        'label' => "Create",
        'color' => "primary",
    ],
    "formUpdateButton" => [
        'label' => "Update",
        'color' => "primary",
    ],
    "formDeleteButton" => [
        'label' => "Delete",
        'color' => "danger",
    ],
    "actions" => [
        "edit" => [
            "onclick" => ["openModal" => true],
            "html" => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 2L5.5 2C4.09554 2 3.39331 2 2.88886 2.33706C2.67048 2.48298 2.48298 2.67048 2.33706 2.88886C2 3.39331 2 4.09554 2 5.5L2 14.5C2 15.9045 2 16.6067 2.33706 17.1111C2.48298 17.3295 2.67048 17.517 2.88886 17.6629C3.39331 18 4.09554 18 5.5 18L14.5 18C15.9045 18 16.6067 18 17.1111 17.6629C17.3295 17.517 17.517 17.3295 17.6629 17.1111C18 16.6067 18 15.9045 18 14.5L18 11"></path><path d="M15.4978 3.06223C15.7795 2.78051 16.1616 2.62224 16.56 2.62224C16.9584 2.62224 17.3405 2.78051 17.6222 3.06223C17.9039 3.34394 18.0622 3.72604 18.0622 4.12445C18.0622 4.52286 17.9039 4.90495 17.6222 5.18667L10.8948 11.9141L8.06219 12.6222L8.77034 9.78964L15.4978 3.06223Z"></path></svg>'
        ],
        "clone" => [
            "onclick" => ["openModal" => true],
            "html" => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 512 512" fill="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M0 224C0 188.7 28.65 160 64 160H128V288C128 341 170.1 384 224 384H352V448C352 483.3 323.3 512 288 512H64C28.65 512 0 483.3 0 448V224zM224 352C188.7 352 160 323.3 160 288V64C160 28.65 188.7 0 224 0H448C483.3 0 512 28.65 512 64V288C512 323.3 483.3 352 448 352H224z"/></svg>'
        ],
        "delete" => [
            "onclick" => ["openModal" => true],
            "html" => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="cs-icon cs-icon-error-square icon"><path d="M13.5 3C14.9045 3 15.6067 3 16.1111 3.33706C16.3295 3.48298 16.517 3.67048 16.6629 3.88886C17 4.39331 17 5.09554 17 6.5L17 13.5C17 14.9045 17 15.6067 16.6629 16.1111C16.517 16.3295 16.3295 16.517 16.1111 16.6629C15.6067 17 14.9045 17 13.5 17L6.5 17C5.09554 17 4.39331 17 3.88886 16.6629C3.67048 16.517 3.48298 16.3295 3.33706 16.1111C3 15.6067 3 14.9045 3 13.5L3 6.5C3 5.09554 3 4.39331 3.33706 3.88886C3.48298 3.67048 3.67048 3.48298 3.88886 3.33706C4.39331 3 5.09554 3 6.5 3L13.5 3Z"></path><path d="M7.99994 12 12 7.99994M7.99994 7.99994 12 12"></path></svg>'
        ],
    ],
    "texts" => [
        "info" => 'Showing |from| to |to| of |total| entries',
        "entries" => 'Entries',
        "filesUpload" => 'Uploading files...',
        "noResult" => 'There are no data!!',
        "multiselect_noResult" => 'Oops! No elements found. Consider changing the search query.',
        "add" => [
            "title" => null
        ],
        "delete" => [
            "message" => 'Are you sure you want to delete |id| ?',
            "title" => null
        ],
        "deleteAll" => [
            "message" => 'Are you sure you want to delete these |id| ?',
            "title" => null
        ],
    ],
    "pagination" => [
        "show" => true,
        "hideIfContainOnePage" => true,
    ],
    "script_file_url" => "/_datatablecrudsminfindjs",
];
