<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\StatsService;
use Illuminate\Support\Facades\Auth;

class OverallStatController extends Controller
{
    public function index(StatsService $statsService)
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $stats = $statsService->calculate($user);

        return response()->json($stats);
    }
}
