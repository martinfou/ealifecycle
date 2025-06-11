<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *     title="EALifecycle API",
 *     version="1.0.0",
 *     description="API documentation for EALifecycle"
 * )
 */
abstract class Controller
{
    use AuthorizesRequests;
}
