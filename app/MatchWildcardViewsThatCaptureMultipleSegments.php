<?php

namespace App;

use Closure;
use Illuminate\Support\Str;
use Laravel\Folio\Pipeline\MatchedView;
use Laravel\Folio\Pipeline\MatchWildcardViewsThatCaptureMultipleSegments as BaseMatchWildcardViewsThatCaptureMultipleSegments;
use Laravel\Folio\Pipeline\State;

class MatchWildcardViewsThatCaptureMultipleSegments extends BaseMatchWildcardViewsThatCaptureMultipleSegments
{
    use FindsWildcardViews;

    /**
     * Invoke the routing pipeline handler.
     */
    public function __invoke(State $state, Closure $next): mixed
    {
        if ($path = $this->findWildcardMultiSegmentView($state->currentDirectory())) {
            return Str::of($path)->contains('.blade.php')
                ? $this->matchedView($state, $path, '.blade.php')
                : $this->matchedView($state, $path, '.php');
        }

        return $next($state);
    }

    protected function matchedView(State $state, string $path, string $extention): MatchedView
    {
        return new MatchedView($state->currentDirectory().'/'.$path, $state->withData(
            Str::of($path)
                ->before($extention)
                ->match('/\[\.\.\.(.*)\]/')->value(),
            array_slice(
                $state->segments,
                $state->currentIndex,
                $state->uriSegmentCount()
            )
        )->data);
    }
}
