<?php
namespace OffbeatWP\Support\Wordpress;

use OffbeatWP\Hooks\AbstractAction;
use OffbeatWP\Hooks\AbstractFilter;

class Hooks
{
    /**
     * @param string $filter
     * @param class-string<AbstractFilter>|callable $callback
     * @param int $priority
     * @param int $acceptArguments
     */
    public function addFilter(string $filter, $callback, int $priority = 10, int $acceptArguments = 1): void
    {
        add_filter($filter, function (...$parameters) use ($callback) {
            $callback = (is_string($callback)) ? [$callback, 'filter'] : $callback;
            return container()->call($callback, $parameters);
        }, $priority, $acceptArguments);
    }

    public function applyFilters(string $filter, ...$parameters)
    {
        return apply_filters_ref_array($filter, $parameters);
    }

    /**
     * @param string $action
     * @param class-string<AbstractAction>|callable $callback
     * @param int $priority
     * @param int $acceptArguments
     */
    public function addAction(string $action, $callback, int $priority = 10, int $acceptArguments = 1): void
    {
        add_action($action, function (...$parameters) use ($callback) {
            $callback = (is_string($callback)) ? [$callback, 'action'] : $callback;
            return container()->call($callback, $parameters);
        }, $priority, $acceptArguments);
    }

    public function doAction(string $action, ...$args): void
    {
        do_action_ref_array($action, $args);
    }
}
