<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function updateStatus(Request $request)
    {
        $request = $request->all();
        $nameRepository = (\Request::segment(2)) . 'Repository';
        $record  = $this->$nameRepository
                     ->updateStatus(Arr::only($request, ['id', 'status']));
        return response()->json([
            'success' => true,
            'msg'     => __('messages.update_success', ['attribute' => 'status']),
        ]);
    }
    
    public function updateOrdering(Request $request)
    {
        $request  = $request->all();
        $nameRepository = (\Request::segment(2)) . 'Repository';
        // Setup the validator
        $rules = ['ordering' => 'required|integer'];
        $messages = [
                    'ordering.required'    => __('messages.required', ['attribute' => 'Vị trí']),
                    'ordering.integer'     => __('messages.integer', ['attribute' => 'Vị trí']),
        ];
        $validator = Validator::make($request, $rules, $messages);

        // Validate the input and return correct response
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()->first()

            ], 422); // 400 being the HTTP code for an invalid request.
        }
        $record   = $this->$nameRepository
                     ->updateOrdering(Arr::only($request, ['id', 'ordering']));

        return response()->json([
            'success'  => true,
            'msg'     => __('messages.update_success', ['attribute' => 'vị trí']),
        ]);
    }
}
