<?php

namespace App\Actions\Permissions;

use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class DeleteRoleAction
{
    public function execute(Role $role): JsonResponse
    {
        $response = $role->delete();

        if ($response) {
            return response()->json([
                'message' => 'Role deleted successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to delete role',
            ], 500);
        }
    }
}
