<?php

namespace App\Traits;

trait HasPermissionCheck
{
    protected function setupPermissionMiddleware(string $resource)
    {
        $this->middleware('permission:' . $resource . ' view')->only(['index']);
        $this->middleware('permission:' . $resource . ' create')->only(['create', 'store']);
        $this->middleware('permission:' . $resource . ' edit')->only(['edit', 'update']);
        $this->middleware('permission:' . $resource . ' delete')->only(['destroy']);
    }
}
