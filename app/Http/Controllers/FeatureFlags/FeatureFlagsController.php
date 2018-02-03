<?php

namespace App\Http\Controllers\FeatureFlags;

use App\FeatureFlag;
use Illuminate\Http\Request;
use App\Events\FlagWasCreated;
use App\Http\Controllers\Controller;
use App\FeatureFlags\FeatureFlagsRepository;

class FeatureFlagsController extends Controller
{
    /**
     * @var \App\FeatureFlags\FeatureFlagsRepository
     */
    private $featureFlagRepository;

    public function __construct(FeatureFlagsRepository $featureFlagRepository)
    {
        parent::__construct();

        $this->featureFlagRepository = $featureFlagRepository;
    }

    public function index()
    {
        $featureFlags = $this->featureFlagRepository->all();

        return response()->json($featureFlags);
    }

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

        $flag = new FeatureFlag([
            'flag' => $request->flag,
            'description' => $request->description,
            'value' => $request->value,
        ]);

        $this->featureFlagRepository->save($flag);

        event(new FlagWasCreated($flag));

        return response()->json($flag, 201);
    }
}
