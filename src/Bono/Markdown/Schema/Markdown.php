<?php namespace Bono\Markdown\Schema;

use Norm\Schema\Field;
use Bono\App;

class Markdown extends Field
{
    /**
     * Partial template to render
     *
     * @var string
     */
    protected $partial = '_markdown/form';

    /**
     * Prepare markdown, enabling mongo to store HTML script
     *
     * @param  string HTML string
     * @return string Encoded HTML string
     */
    public function prepare($value)
    {
        return htmlentities($value);
    }

    /**
     * Render readonly schema, useful for read action
     *
     * @param  string      $value Encoded HTML string
     * @param  \Norm\Model $entry Norm Model
     * @return string      HTML string
     */
    public function formatReadonly($value, $entry = null)
    {
        return App::getInstance()->container['markdown']->render(html_entity_decode(html_entity_decode($value)));
    }

    /**
     * Render read+write schema, useful for create and update action
     *
     * @param  string      $value Encoded HTML string
     * @param  \Norm\Model $entry Norm Model
     * @return string      HTML string
     */
    public function formatInput($value, $entry = null)
    {
        return App::getInstance()->container['markdown.form']->renderInput(html_entity_decode(html_entity_decode($value)), $this->partial);
    }
}
