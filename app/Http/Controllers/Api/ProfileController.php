<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends ApiController
{
    public function show(Request $request): JsonResponse
    {
        $profile = $this->profileFor($request);

        return $this->success(
            (new ProfileResource($profile))->resolve($request),
            'Profil berhasil diambil.',
        );
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $profile = $this->profileFor($request);
        $profile->update($request->validated());

        return $this->success(
            (new ProfileResource($profile->fresh('user')))->resolve($request),
            'Profil berhasil diperbarui.',
        );
    }

    public function uploadPhoto(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        $profile = $this->profileFor($request);

        if ($profile->photo_url) {
            $oldPath = str($profile->photo_url)->after('/storage/')->value();
            Storage::disk('public')->delete($oldPath);
        }

        $path = $validated['photo']->store('profiles', 'public');
        $profile->update(['photo_url' => Storage::url($path)]);

        return $this->success(
            (new ProfileResource($profile->fresh('user')))->resolve($request),
            'Foto profil berhasil diperbarui.',
        );
    }

    private function profileFor(Request $request): Profile
    {
        $profile = Profile::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['full_name' => $request->user()->name],
        );

        return $profile->load('user');
    }
}
