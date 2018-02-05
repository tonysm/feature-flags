<?php

namespace App\Http\Controllers\FeatureFlags;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\FeatureFlags\Events\FlagWasDisabled;
use App\FeatureFlags\Repositories\FeatureFlagsEloquentRepository;

class DisabledFlagsController extends Controller
{
    /**
     * @var FeatureFlagsEloquentRepository
     */
    private $featureFlagsEloquentRepository;

    /**
     * DisabledFlagsController constructor.
     *
     * @param FeatureFlagsEloquentRepository $featureFlagsEloquentRepository
     */
    public function __construct(FeatureFlagsEloquentRepository $featureFlagsEloquentRepository)
    {
        parent::__construct();

        $this->featureFlagsEloquentRepository = $featureFlagsEloquentRepository;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'feature_flag_id' => [
                'required',
                Rule::exists('feature_flags', 'id'),
            ],
            'confirmation' => 'required|accepted',
        ]);

        $flag = $this->featureFlagsEloquentRepository->findById($request->feature_flag_id);

        $this->featureFlagsEloquentRepository->disable($flag);

        event(new FlagWasDisabled($flag));

        return response()->json($flag, 201);
    }
}
