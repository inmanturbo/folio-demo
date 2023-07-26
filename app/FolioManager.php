<?php

namespace App;

use Closure;
use Illuminate\Http\Request;
use Laravel\Folio\FolioManager as BaseFolioManager;
use Laravel\Folio\MountPath;
use Laravel\Folio\Pipeline\MatchedView;

class FolioManager extends BaseFolioManager
{
    /**
     * Get the Folio request handler function.
     */
    protected function handler(MountPath $mountPath): Closure
    {
        return function (Request $request, string $uri = '/') use ($mountPath) {
            return (new RequestHandler(
                $mountPath,
                $this->renderUsing,
                fn (MatchedView $matchedView) => $this->lastMatchedView = $matchedView,
            ))($request, $uri);
        };
    }
}