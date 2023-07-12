<?php
use Waka\WakaJob\Models\Job;

Route::get('/api/wajajobs/jobs', function () {
    $user = BackendAuth::getUser();
    if (!$user) {
        return null;
    }
    $jobList = Job::where('user_id', $user->id);
    return response()->json([
        'error' => Job::OnlyUser()->state('error')->count(),
        'end' => Job::OnlyUser()->state('end')->count(),
        'run' => Job::OnlyUser()->state('run')->count(),
    ], 200);
})->middleware('web');
