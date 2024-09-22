<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelperController extends Controller
{
    public function toggleStatus(Request $request)
    {

        // Validate the model and id are present
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        // Resolve the fully qualified model class name dynamically
        $model = "App\\Models\\" . ucfirst(Str::singular($request->model));
        if (!class_exists($model)) {
            return response()->json([
                'success' => false,
                'message' => 'Model not found!',
            ], 404);
        }

        // Find the model record by ID
        $modelInstance = $model::find($request->id);

        if (!$modelInstance) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found!',
            ], 404);
        }

        // Update the `is_active` or any other field
        $modelInstance->is_active = $request->is_active;
        $modelInstance->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
        ]);
    }
}
