<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\GenerateTransactionReportJob;
use Illuminate\Support\Facades\Validator;
use App\DTO\UserDto;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\ErrorResource;

class TransactionReportController extends Controller
{
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
            'format' => 'required|in:pdf,excel',
        ]);

        if ($validator->fails()) {
            return new ErrorResource($validator->errors());
        }

        GenerateTransactionReportJob::dispatch(UserDto::fromEloquentModel(auth()->user()), $request->format, $request->start_date, $request->end_date);
        return new SuccessResource(['message' => 'Report generation has been queued.']);
    }
}
