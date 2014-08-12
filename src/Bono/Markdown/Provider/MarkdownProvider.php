<?php namespace Bono\Markdown\Provider;

use Bono\Provider\Provider;
use Bono\Helper\URL;
use Ciconia\Ciconia;
use Ciconia\Extension\Gfm;
use Bono\Markdown\Helper;

class MarkdownProvider extends Provider
{
    /**
     * Initialize the provider, register classes to the container
     *
     * @return void
     */
    public function initialize()
    {
        $ciconia = new Ciconia;
        $that = $this;

        if ($this->gfmEnabled()) {
            $ciconia->addExtension(new Gfm\FencedCodeBlockExtension);
            $ciconia->addExtension(new Gfm\TaskListExtension);
            $ciconia->addExtension(new Gfm\InlineStyleExtension);
            $ciconia->addExtension(new Gfm\WhiteSpaceExtension);
            $ciconia->addExtension(new Gfm\TableExtension);
            $ciconia->addExtension(new Gfm\UrlAutoLinkExtension);
        }

        $this->app->container->singleton('markdown', function () use ($ciconia) {
            return $ciconia;
        });

        $this->app->container->singleton('markdown.form', function () use ($that) {
            return new Helper\Form($that->options);
        });

        $this->app->container->singleton('markdown.config', function () use ($that) {
            return $that;
        });

        if ($this->serviceEnabled()) {
            $this->registerService();
        }
    }

    /**
     * Determine if provider enabling the GFM extension
     *
     * @return bool
     */
    protected function gfmEnabled()
    {
        $gfm = false;

        if (isset($this->options['gfm'])) {
            $gfm = $this->options['gfm'];
        }

        return $gfm;
    }

    /**
     * Determine if provider enabling web service
     *
     * @return bool
     */
    protected function serviceEnabled()
    {
        $service = false;

        if (isset($this->options['service'])) {
            $service = $this->options['service'];
        }

        return $service;
    }

    /**
     * Get default endpoint of web service
     *
     * @return string
     */
    public function getDefaultEndPoint()
    {
        $endpoint = '/md';

        if (isset($this->options['endpoint'])) {
            $endpoint = $this->options['endpoint'];
        }

        return $endpoint;
    }

    /**
     * Get default request key name for webservice
     *
     * @return string
     */
    public function getDefaultRequestKeyName()
    {
        $requestKeyName = 'markdown';

        if (isset($this->options['requestKeyName'])) {
            $requestKeyName = $this->options['requestKeyName'];
        }

        return $requestKeyName;
    }

    /**
     * Get full URL of web service endpoint
     *
     * @see MarkdownProvider::getDefaultEndPoint
     * @return string
     */
    public function getServiceUrl()
    {
        return URL::site($this->getDefaultEndPoint());
    }

    /**
     * Register our webservice
     *
     * @return void
     */
    protected function registerService()
    {
        $endpoint = $this->getDefaultEndPoint();
        $requestKeyName = $this->getDefaultRequestKeyName();
        $app = $this->app;

        $app->post($endpoint, function () use ($app, $requestKeyName) {
            echo $app->container['markdown']->render($app->request->post($requestKeyName));
        });
    }
}
