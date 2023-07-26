<?php

namespace App;

use Closure;
use Illuminate\Support\Str;
use Laravel\Folio\Pipeline\MatchedView;
use Laravel\Folio\Pipeline\MatchWildcardViews as BaseMatchWildcardViews;
use Laravel\Folio\Pipeline\State;

class MatchWildcardViews
{
    use FindsWildcardViews;

    /**
     * Invoke the routing pipeline handler.
     */
    public function __invoke(State $state, Closure $next): mixed
    {
        if ($state->onLastUriSegment() &&
            $path = $this->findWildcardView($state->currentDirectory())) {
            return Str::of($path)->contains('.blade.php')
                ? $this->matchedView($state, $path, '.blade.php')
                : $this->matchedView($state, $path, '.php');
        }

        return $next($state);
    }

    protected function matchedView(State $state, string $path, string $extension): MatchedView
    {
        return new MatchedView($state->currentDirectory().'/'.$path, $state->withData(
            Str::of($path)
                ->before($extension)
                ->match('/\[(.*)\]/')->value(),
            $state->currentUriSegment(),
        )->data);
    }
}
