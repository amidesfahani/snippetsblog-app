<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        ];

        try {
            /** @var \Illuminate\Database\MySqlConnection $connection */
    		$connection = DB::connection();
    		$connection->getPdo();
            $response['db'] = true;
        } catch (\Exception $e) {
            $status = 400;
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
