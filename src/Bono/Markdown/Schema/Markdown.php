<?php namespace Bono\Markdown\Schema;

use Norm\Schema\Field;
use Bono\App;

class Markdown extends Field
{
    protected $partial = '_markdown/form';

    public function formatReadonly($value, $entry = null)
    {
        return App::getInstance()->container['markdown']->render($value);
    }

    public function formatInput($value, $entry = null)
    {
        return App::getInstance()->container['markdown.form']->renderInput($value, $this->partial);
    }
}
