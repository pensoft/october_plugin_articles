<?php namespace Pensoft\Articles\Controllers;

use Backend\Classes\Controller;
use Backend\Widgets\Form;

class Article extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
    }

    public function formExtendFields(Form $form)
    {
        // Ensure this only applies in the 'create' or 'update' form context
        if (!$form->model) {
            return;
        }

        // Dynamically adjust the required state of fields based on 'published' field
        $form->bindEvent('form.extendFieldsBefore', function () use ($form) {
            $isPublished = $form->data['published'] ?? false;

            // Make 'cover' and 'published_at' required if 'published' is checked
            $form->getField('cover')->required = (bool) $isPublished;
            $form->getField('published_at')->required = (bool) $isPublished;
        });
    }
    
}
