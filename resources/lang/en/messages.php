<?php

return [
    "actions" => [
        "page" => [
            "add" => "Bookmark Page",
            "remove" => "Remove Bookmark",
            "notification" => [
                "add" => [
                    "title" => "Bookmark",
                    "body" => "Bookmark added successfully"
                ],
                "remove" => [
                    "title" => "Bookmark",
                    "body" => "Bookmark deleted successfully"
                ]
            ],
            "modal" => [
                "add" => "Bookmark Page",
                "remove" => "Remove Bookmark"
            ]
        ],
        "table" => [
            "add" => "Bookmark Record",
            "remove" => "Remove Bookmark",
            "modal" => [
                "add" => "Bookmark Record",
                "remove" => "Remove Bookmark"
            ]
        ],
        "bulk" => [
            "add" => "Bookmark Records",
            "remove" => "Clear Bookmarks",
            "modal" => [
                "add" => "Bookmark Selected Records",
                "remove" => "Clear Selected Bookmarks"
            ]
        ],
        "types" => [
            "form" => [
                "type" => "Bookmark Type",
                "bookmark" => "Bookmark"
            ]
        ]
    ],
    "page" => [
        "title" => "Bookmarks",
        "table" => [
            "name" => "Name",
            "url" => "Bookmark",
            "actions" => [
                "remove" => [
                    "lable" => "Remove Bookmark",
                    "modal" => "Remove Bookmark",
                    "notification" => [
                        "title" => "Bookmark",
                        "body" => "Bookmark deleted successfully"
                    ]
                ],
                "bulk" => [
                    "label" => "Remove Bookmarks",
                    "modal" => "Clear Selected Bookmarks",
                    "notification" => [
                        "title" => "Bookmark Removed",
                        "body" => "The bookmark has been removed successfully."
                    ]
                ]
            ]
        ],
        "actions" => [
            "edit" =>[
                "label" => "Edit",
                "modal" => "Edit Bookmark Collection",
                "notification" => [
                    "title" => "Bookmark Collection",
                    "body" => "The bookmark collection has been updated successfully."
                ],
                "form" => [
                    "name" => "Name",
                    "color" => "Color",
                    "icon" => "Icon",
                    "is_private" => "Is Private?",
                ]
            ],
            "delete"=> [
                "label" => "Delete",
                "modal" => "Delete Bookmark Collection",
                "notification" => [
                    "title" => "Bookmark Collection",
                    "body" => "The bookmark collection has been deleted successfully."
                ]
            ]
        ]
    ],
    "components" => [
        "total" => "Total Bookmarks",
        "folders" => "Folders",
    ],
    "livewire" => [
        "create" => [
            "label" => "Create Collection",
            "modal" => "Add Bookmark Collection",
            "form" => [
                "name" => "Name"
            ],
            "notification" => [
                "title" => "Bookmark Collection",
                "body" => "The bookmark collection has been created successfully."
            ]
        ]
    ]
];
