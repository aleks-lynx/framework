<?php
namespace OffbeatWP\Support\Wordpress;

use OffbeatWP\Content\Post\PostTypeBuilder;
use OffbeatWP\Content\Post\PostModel;

class PostType
{
    const DEFAULT_POST_MODEL = PostModel::class;

    private $postTypeModels = [];

    public static function make($name, $pluralName, $singleName): PostTypeBuilder
    {
        return (new PostTypeBuilder)->make($name, $pluralName, $singleName);
    }

    public function registerPostModel($postType, $modelClass)
    {
        $this->postTypeModels[$postType] = $modelClass;
    }

    public function getModelByPostType($postType)
    {
        if (isset($this->postTypeModels[$postType]))
            return $this->postTypeModels[$postType];

        return self::DEFAULT_POST_MODEL;
    }

    public function getPostTypeByModel($model)
    {
        return array_search($model, $this->postTypeModels);
    }
}
