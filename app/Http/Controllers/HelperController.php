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
            'value' => 'required|boolean',
            'attribute' => 'required',
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

        $column = $request->attribute;
        $value = $request->value;
        // Update the `is_active` or any other field
        $modelInstance->$column = $value;
        $modelInstance->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
        ]);
    }
}
