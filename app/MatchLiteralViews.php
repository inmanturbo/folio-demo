<?php

namespace App;

use Closure;
use Laravel\Folio\Pipeline\MatchedView;
use Laravel\Folio\Pipeline\State;
use Laravel\Folio\Pipeline\MatchLiteralViews as BaseMatchLiteralViews;

class MatchLiteralViews extends BaseMatchLiteralViews
{
    /**
     * Invoke the routing pipeline handler.
     */
    public function __invoke(State $state, Closure $next): mixed
    {
        return $state->onLastUriSegment() &&
            file_exists($path = $state->currentDirectory().'/'.$state->currentUriSegment().'.blade.php') || file_exists($path = $state->currentDirectory().'/'.$state->currentUriSegment().'.php')
                ? new MatchedView($path, $state->data)
                : $next($state);
    }
}
