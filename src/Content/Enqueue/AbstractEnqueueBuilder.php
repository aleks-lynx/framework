<?php

namespace OffbeatWP\Content\Enqueue;

abstract class AbstractEnqueueBuilder
{
    /** @var string */
    protected $src = '';
    /** @var string[] */
    protected $deps = [];
    /** @var null|false|string */
    protected $version = null;

    /**
     * @param string $src The file location. Starts in theme stylesheet directory.
     * @return static
     */
    public function setSrc(string $src)
    {
        $this->src = get_stylesheet_directory_uri() . '/' . $src;
        return $this;
    }

    /**
     * @param string $filename
     * @return static
     */
    public function setAsset(string $filename)
    {
        $this->src = offbeat('assets')->getUrl($filename) ?: '';
        return $this;
    }

    /**
     * @param string $src The file location.
     * @return static
     */
    public function setAbsoluteSrc(string $src)
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @param string[] $deps An array of registered handles that this enqueue depends on.
     * @return static
     */
    public function setDeps(string ...$deps)
    {
        $this->deps = $deps;
        return $this;
    }

    /**
     * @param string $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes
     * @return static
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Add version number for cache busting equal to current installed WordPress version
     * @return static
     */
    public function setVersionToWpVersion()
    {
        $this->version = false;
        return $this;
    }

    /** @return AbstractAssetHolder|null */
    abstract public function register(string $handle);

    abstract public function enqueue(string $handle): void;
}