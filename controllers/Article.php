<?php namespace Pensoft\Articles\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Backend\Widgets\Form;
use BackendMenu;

class Article extends Controller
{
    public $implement = [
        ListController::class,
        FormController::class,
    ];

    public string $listConfig = 'config_list.yaml';
    public string $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Pensoft.Articles', 'articles', 'side-menu-media-articles');
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