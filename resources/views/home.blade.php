@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>

                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            SOMETHING_ELSE:
                            {{ \App\FeatureFlags\Facades\FeatureFlags::isEnabled('SOMETHING_ELSE') ? 'Enabled' : 'Disabled' }}
                        </div>
                        <div class="list-group-item">
                            SOMETHING_ELSE_EVEN:
                            {{ \App\FeatureFlags\Facades\FeatureFlags::isEnabled('SOMETHING_ELSE_EVEN') ? 'Enabled' : 'Disabled' }}
                        </div>
                        <div class="list-group-item">
                            MISSING_KEY:
                            {{ \App\FeatureFlags\Facades\FeatureFlags::isEnabled('MISSING_KEY') ? 'Enabled' : 'Disabled' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
