<?php

namespace App\Http\Controllers\FeatureFlags;

use App\Events\FlagWasReEnabled;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\FeatureFlags\Repositories\FeatureFlagsEloquentRepository;

class EnabledFlagsController extends Controller
{
    /**
     * @var FeatureFlagsEloquentRepository
     */
    private $repository;

    /**
     * EnabledFlagsController constructor.
     *
     * @param FeatureFlagsEloquentRepository $repository
     */
    public function __construct(FeatureFlagsEloquentRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'feature_flag_id' => [
                'required',
                Rule::exists('feature_flags', 'id'),
            ],
            'confirmed' => 'accepted',
        ]);

        $flag = $this->repository->findById($request->feature_flag_id);

        $this->repository->reenable($flag);

        event(new FlagWasReEnabled($flag));

        return response()->json($flag, 201);
    }
}
