<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * we can use spatie health check package
 * also laravel 11 includes a health check endpoint!
 */
class HealthCheckController extends Controller
{
    public function check()
    {
        $status = 200;

        $response = [
            'status' => true,
            'time' => (string) Carbon::now(),
            'db' => false,
            'cache' => false,
        ];

        try {
            /** @var \Illuminate\Database\MySqlConnection $connection */
            $connection = DB::connection();
            $connection->getPdo();
            $response['db'] = true;
        } catch (\Exception $e) {
            $status = 400;
            Log::error('Database connection failed', ['error' => $e->getMessage()]);
        }

        try {
            Redis::ping();
            $services['cache'] = 'OK';
        } catch (\Exception $e) {
            $services['cache'] = false;
            $status = 400;
            Log::error('Redis connection failed', ['error' => $e->getMessage()]);
        }

        if ($status !== 200) {
            http_response_code($status);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        return view('health');
    }
}
