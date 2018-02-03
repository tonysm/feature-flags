<?php

namespace App\Http\Controllers\FeatureFlags;

use App\FeatureFlag;
use Illuminate\Http\Request;
use App\Events\FlagWasCreated;
use App\Http\Controllers\Controller;

class FeatureFlagsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'flag' => [
                'required',
                'string',
            ],
            'value' => 'required|boolean',
            'description' => 'string',
        ]);

        $flag = FeatureFlag::create([
            'flag' => $request->flag,
            'description' => $request->description,
            'value' => $request->value,
        ]);

        event(new FlagWasCreated($flag));

        return response()->json($flag, 201);
    }
}
