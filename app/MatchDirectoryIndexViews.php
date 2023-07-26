<?php

namespace App;

use Closure;
use Laravel\Folio\Pipeline\MatchDirectoryIndexViews as BaseMatchDirectoryIndexViews;
use Laravel\Folio\Pipeline\MatchedView;
use Laravel\Folio\Pipeline\State;

class MatchDirectoryIndexViews extends BaseMatchDirectoryIndexViews
{
    /**
     * Invoke the routing pipeline handler.
     */
    public function __invoke(State $state, Closure $next): mixed
    {
        return $state->onLastUriSegment() &&
            $state->currentUriSegmentIsDirectory() &&
            file_exists($path = $state->currentUriSegmentDirectory().'/index.blade.php') || file_exists($path = $state->currentUriSegmentDirectory().'/index.php')
                ? new MatchedView($path, $state->data)
                : $next($state);
    }
}
