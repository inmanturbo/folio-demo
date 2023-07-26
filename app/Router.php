<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Laravel\Folio\Pipeline\ContinueIterating;
use Laravel\Folio\Pipeline\EnsureNoDirectoryTraversal;
use Laravel\Folio\Pipeline\MatchedView;
use Laravel\Folio\Pipeline\MatchLiteralDirectories;
use Laravel\Folio\Pipeline\MatchWildcardDirectories;
use Laravel\Folio\Pipeline\SetMountPathOnMatchedView;
use Laravel\Folio\Pipeline\State;
use Laravel\Folio\Pipeline\StopIterating;
use Laravel\Folio\Router as BaseRouter;

class Router extends BaseRouter
{
    /**
     * Resolve the given URI via page based routing at the given mount path.
     */
    protected function matchAtPath(string $mountPath, Request $request, string $uri): ?MatchedView
    {
        $state = new State(
            uri: $uri,
            mountPath: $mountPath,
            segments: explode('/', $uri)
        );

        for ($i = 0; $i < $state->uriSegmentCount(); $i++) {
            $value = (new Pipeline)
                ->send($state->forIteration($i))
                ->through([
                    new EnsureNoDirectoryTraversal,
                    new TransformModelBindings($request),
                    new SetMountPathOnMatchedView,
                    // ...
                    new MatchRootIndex,
                    new MatchDirectoryIndexViews,
                    new MatchWildcardViewsThatCaptureMultipleSegments,
                    new MatchLiteralDirectories,
                    new MatchWildcardDirectories,
                    new MatchLiteralViews,
                    new MatchWildcardViews,
                ])->then(fn () => new StopIterating);

            if ($value instanceof MatchedView) {
                return $value;
            } elseif ($value instanceof ContinueIterating) {
                $state = $value->state;

                continue;
            } elseif ($value instanceof StopIterating) {
                break;
            }
        }

        return null;
    }
}