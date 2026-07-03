<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    public function sync(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            ['full_name' => $request->string('display_name')->value() ?: $user->name],
        );
        $profile->load('user');

        return $this->success([
            'user' => (new UserResource($user))->resolve($request),
            'profile' => (new ProfileResource($profile))->resolve($request),
        ], 'Akun Firebase berhasil disinkronkan.');
    }
}
