<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function validateResourceId($id, $resource = 'Resource'): int|JsonResponse
    {
        if (!is_numeric($id)) {
            return response()->json(['message' => "$resource ID must be a number"], 400);
        }
        $id = (int) $id;
        if ($id <= 0) {
            return response()->json(['message' => "Invalid $resource ID"], 400);
        }
        return $id;
    }
}
