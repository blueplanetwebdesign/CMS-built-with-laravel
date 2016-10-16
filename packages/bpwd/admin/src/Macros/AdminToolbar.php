<?php

Form::macro('adminToolbarRebuildButton', function($view)
{
    return '
    <button id="toolbar-save" type="button" class="btn btn-default" aria-label="Left Align" onclick="submitToolbarButton(\'rebuild\', \''.$view.'\')">
        <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
        Rebuild
    </button>
    ';
});

Form::macro('adminToolbarSaveButton', function()
{
    return '
    <button id="toolbar-save" type="button" class="btn btn-success" aria-label="Left Align" onclick="submitToolbarButton(\'save\')">
        <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
        Save
    </button>
    ';
});

Form::macro('adminToolbarSaveAndCloseButton', function()
{
    return '
    <button id="toolbar-save-and-close" type="button" class="btn btn-default" aria-label="Left Align" onclick="submitToolbarButton(\'save-and-close\')">
        <span class="glyphicon glyphicon-saved" aria-hidden="true"></span>
        Save & Close
    </button>
    ';
});

Form::macro('adminToolbarSaveAndNewButton', function()
{
    return '
    <button id="toolbar-save-and-new" type="button" class="btn btn-default" aria-label="Left Align" onclick="submitToolbarButton(\'save-and-new\')">
        <span class="glyphicon glyphicon-export" aria-hidden="true"></span>
        Save & New
    </button>
    ';
});

Form::macro('adminToolbarSaveAsCopyButton', function()
{
    return '
    <button id="toolbar-save-as-copy" type="button" class="btn btn-default" aria-label="Left Align" onclick="submitToolbarButton(\'save-as-copy\')">
        <span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
        Save as Copy
    </button>
    ';
});

Form::macro('adminToolbarCloseButton', function()
{
    return '
    <button id="toolbar-close" type="button" class="btn btn-default" aria-label="Left Align" onclick="submitToolbarButton(\'close\')">
        <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
        Close
    </button>
    ';
});

Form::macro('adminToolbarEditButton', function($view)
{
    return '
    <button id="toolbar-edit" type="button" class="btn btn-default" aria-label="Left Align" onclick="submitToolbarButton(\'edit\', \''.$view.'\')">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        Edit
    </button>
    ';
});

Form::macro('adminToolbarPublishButton', function()
{
    return '
    <button id="toolbar-publish" type="button" class="btn btn-default" aria-label="Left Align" onclick="submitToolbarButton(\'publish\')">
        <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
        Publish
    </button>
    ';
});

Form::macro('adminToolbarUnpublishButton', function()
{
    return '
    <button id="toolbar-unpublish" type="button" class="btn btn-default" aria-label="Left Align" onclick="submitToolbarButton(\'unpublish\')">
        <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
        Unpublish
    </button>
    ';
});

Form::macro('adminToolbarDeleteButton', function()
{
    return '
    <button id="toolbar-delete" type="button" class="btn btn-default" aria-label="Left Align" onclick="submitToolbarButton(\'delete\')">
        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
        Delete
    </button>
    ';
});

Form::macro('adminToolbarNewButton', function($view)
{
    return '
    <button id="toolbar-new" type="button" class="btn btn-success" aria-label="Left Align" onclick="submitToolbarButton(\'new\', \''.$view.'\')">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        New
    </button>
    ';
});