<?php
namespace OffbeatWP\Assets;

class AssetsManager
{
    public $actions = [];
    public $manifest = null;
    public $entrypoints = null;

    public function getUrl($filename)
    {
        if ($this->getEntryFromAssetsManifest($filename) !== false) {
            $path = $this->getEntryFromAssetsManifest($filename);

            if (strpos($path, 'http') === 0) {
                return $path;
            }

            return $this->getAssetsUrl($path);
        }

        return false;
    }

    public function getPath($filename)
    {
        if ($this->getEntryFromAssetsManifest($filename) !== false) {
            return $this->getAssetsPath($this->getEntryFromAssetsManifest($filename));
        }

        return false;
    }

    public function getEntryFromAssetsManifest($filename)
    {
        return $this->getAssetsManifest()->$filename ?? false;
    }

    public function getAssetsManifest() {
        if ($this->manifest === null && file_exists($this->getAssetsPath('manifest.json'))) {
            $this->manifest = json_decode(file_get_contents($this->getAssetsPath('manifest.json')));
        }

        return $this->manifest;
    }

    public function getAssetsEntryPoints() {
        if ($this->entrypoints === null && file_exists($this->getAssetsPath('entrypoints.json'))) {
            $this->entrypoints = json_decode(file_get_contents($this->getAssetsPath('entrypoints.json')));
        }

        return $this->entrypoints;
    }

    public function getAssetsByEntryPoint($entry, $key)
    {
        $entrypoints = $this->getAssetsEntryPoints();

        if (empty($entrypoints->entrypoints->$entry->$key)) {
            return false;
        }

        return $entrypoints->entrypoints->$entry->$key;
    }

    public function getAssetsPath($path = '')
    {
        $path = ltrim($path, '/');
        $path = ($path) ? "/{$path}" : '';

        if ($basepath = config('app.assets.path'))  {
            return $basepath . $path;
        }

        return get_template_directory() . '/assets' . $path; 
    }

    public function getAssetsUrl($path = '')
    {
        if (strpos($path, 'http') === 0) {
            return $path;
        }

        $path = ltrim($path, '/');
        $path = ($path) ? "/{$path}" : '';

        if ($url = config('app.assets.url'))  {
            return $url . $path;
        }

        return get_template_directory_uri() . '/assets' . $path; 
    }

    public function enqueueStyles($entry) {
        $assets = $this->getAssetsByEntryPoint($entry, 'css');

        if ($assets) {
            foreach ($assets as $key => $asset) {
                $asset = ltrim($asset, './');
                $assetKey = 'css-' . $entry . '-' . ($key > 0 ? $key : '');
                wp_enqueue_style($assetKey, $this->getAssetsUrl($asset), [], false, false);
            }

            return;
        }

        wp_enqueue_style('theme-style' . $entry, $this->getUrl($entry . '.css'), [], false);
    }

    public function enqueueScripts($entry) {
        $assets = $this->getAssetsByEntryPoint($entry, 'js');

        if ($assets) {
            foreach ($assets as $key => $asset) {
                $asset = ltrim($asset, './');
                $assetKey = 'js-' . $entry . '-' . ($key > 0 ? $key : '');
                wp_enqueue_script($assetKey, $this->getAssetsUrl($asset), ['jquery'], false, true);
            }

            return;
        }

        wp_enqueue_script('theme-script-' . $entry, $this->getUrl($entry . '.js'), ['jquery'], false, true);
    }
}
