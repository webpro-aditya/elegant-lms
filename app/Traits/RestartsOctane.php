<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Laravel\Octane\RoadRunner\ServerProcessInspector as RoadRunnerServerProcessInspector;

trait RestartsOctane
{
    /**
     * Reload Octane workers
     *
     * @return bool
     */
    protected function reloadOctane(): bool
    {

        try {
            $inspector = app(RoadRunnerServerProcessInspector::class);
            if (! $inspector->serverIsRunning()) {
                return false;
            } else {
                $inspector->reloadServer();
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Failed to reload Octane: ' . $e->getMessage());
            return false;
        }
    }
}
