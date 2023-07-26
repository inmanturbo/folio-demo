<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Laravel\Folio\RequestHandler as BaseRequestHandler;

class RequestHandler extends BaseRequestHandler
{
    /**
     * Handle the incoming request using Folio.
     */
    public function __invoke(Request $request, string $uri): mixed
    {
        $matchedView = (new Router(
            $this->mountPath->path
        ))->match($request, $uri) ?? abort(404);

        return (new Pipeline(app()))
            ->send($request)
            ->through($this->middleware($matchedView))
            ->then(function (Request $request) use ($matchedView) {
                if ($this->onViewMatch) {
                    ($this->onViewMatch)($matchedView);
                }

                return $this->renderUsing
                    ? ($this->renderUsing)($request, $matchedView)
                    : $this->toResponse($matchedView);
            });
    }
}