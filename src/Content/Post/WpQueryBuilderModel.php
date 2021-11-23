<?php
namespace OffbeatWP\Content\Post;

use OffbeatWP\Exceptions\ModelTypeMismatchException;
use WP_Post;

class WpQueryBuilderModel extends WpQueryBuilder
{
    protected $model;

    /**
     * @throws ModelTypeMismatchException
     * @param class-string<PostModel> $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->model = $modelClass;

        if (defined("{$modelClass}::POST_TYPE")) {
            $this->wherePostType($modelClass::POST_TYPE);
        } elseif ($modelClass !== PostModel::class) {
            throw new ModelTypeMismatchException('The POST_TYPE constant must be defined on any model that is not the base PostModel.');
        }

        $order = null;
        $orderDirection = null;

        if (defined("{$modelClass}::ORDER_BY")) {
            $order = $modelClass::ORDER_BY;
        }

        if (defined("{$modelClass}::ORDER")) {
            $orderDirection = $modelClass::ORDER;
        }

        $this->order($order, $orderDirection);
    }

    /**
     * @param WP_Post|int|null $post
     * @return PostModel
     */
    public function postToModel($post)
    {
        return new $this->model($post);
    }
}
