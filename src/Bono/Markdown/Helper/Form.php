<?php namespace Bono\Markdown\Helper;

use Bono\App;
use Slim\View;
use RuntimeException;

class Form
{
    /**
     * App
     *
     * @var \Bono\App
     */
    protected $app;

    /**
     * View engine
     *
     * @var \Slim\View
     */
    protected $view;

    /**
     * Form Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Constructor
     */
    public function __construct($options)
    {
        $this->app = App::getInstance();

        if (isset($options['view'])) {
            if (is_callable($options['view'])) {
                $view = $options['view'];
                $this->view = $view();
            }
        } else {
            $this->view = new View;

            if (isset($options['partialTemplatePath'])) {
                $partialTemplatePath = $options['partialTemplatePath'];
            } else {
                $partialTemplatePath = dirname(dirname(dirname(dirname(__DIR__)))).'/partials';
            }

            $this->view->setTemplatesDirectory($partialTemplatePath);
        }
    }

    /**
     * Render markdown input
     *
     * @param  string $markDownSyntax Markdown syntax to put inside textarea
     * @param  string $template       Template name
     * @return string HTML string
     */
    public function renderInput($markdownSyntax = '', $template = '_markdown/form')
    {
        $this->view->set('app', $this->app);
        $this->view->set('id', $this->random());
        $this->view->set('markdown', $markdownSyntax);

        $explode = count(explode('.php', $template));

        if ($explode == 1) {
            $template .= '.php';
        }

        return $this->view->fetch($template);
    }

    /**
     * Get random string
     *
     * @param  integer $length
     * @return string
     */
    public function random($length = 8)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length * 2);

            if ($bytes === false) {
                throw new RuntimeException('Unable to generate random string.');
            }

            return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
        }

        // Use fallback
        return self::quickRandom($length);
    }

    /**
     * Quick random string
     *
     * @param  integer $length
     * @return string
     */
    public function quickRandom($length = 8)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    /**
     * Just in case you want to change your view engine, you can use this method
     *
     * @param View $view Custom view engine
     */
    public function setView(View $view)
    {
        $this->view = $view;

        return $this;
    }
}
