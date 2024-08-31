<?php

namespace App\Services\Log;

use App\Models\StatusChangeLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LoggingService
{
    public function logActivity(Model $model, string $activity)
    {
        StatusChangeLog::create([
            'user_id' => Auth::id(),
            'loggable_type' => get_class($model),
            'loggable_id' => $model->getKey(),
            'activity' => $activity,
        ]);
    }
}
