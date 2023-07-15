<?php

namespace App\Actions\Permissions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CreateRoleAction
{
    public function __invoke(Request $request): JsonResponse
    {
        Cache::forget('roles');
        $name = $request->input('name');
        $guardApi = $request->input('guardApi');
        $guardWeb = $request->input('guardWeb');

        $createdRoles = [];

        if ($guardApi === 'true') {
            $roleApi = Role::findOrCreate($name, 'api');
            $createdRoles[] = $roleApi;
        }
        if ($guardWeb === 'true') {
            $roleWeb = Role::findOrCreate($name, 'web');
            $createdRoles[] = $roleWeb;
        }

        if (count($createdRoles) > 0) {
            return response()->json([
                'message' => 'Roles created successfully',
                'roles' => $createdRoles,
            ], ResponseAlias::HTTP_CREATED);
        } else {
            return response()->json([
                'message' => 'No roles were created',
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}
