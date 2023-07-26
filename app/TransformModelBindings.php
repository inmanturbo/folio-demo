<?php

namespace App;

use Illuminate\Support\Str;
use Laravel\Folio\Pipeline\MatchedView;
use Laravel\Folio\Pipeline\TransformModelBindings as BaseTransformModelBindings;
use Stringable;

class TransformModelBindings extends BaseTransformModelBindings
{
    /**
     * Get the bindable path segments for the matched view.
     */
    protected function bindablePathSegments(MatchedView $view): array
    {
        $path = Str::of($view->path);

        return $path->contains('.blade.php')
            ? $this->explodeViewPath($path, '.blade.php')
            : $this->explodeViewPath($path, '.php');
    }

    protected function explodeViewPath(Stringable $path, string $extension): array
    {
        return explode('/', (string) Str::of($path)
            ->beforeLast($extension)
            ->trim('/'));
    }
}