<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use JustSteveKing\StatusCode\Http;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::query()
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get()
        )->additional([
            'status_code' => Http::OK,
            'message' => 'OK'
        ]);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json([
            'status_code' => Http::CREATED,
            'message' => 'CREATE',
            'data' => UserResource::make($user)
        ], Http::CREATED());
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'status_code' => Http::OK,
            'message' => 'OK',
            'data' => UserResource::make($user)
        ], Http::OK());
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return response()->json([
            'status_code' => Http::OK,
            'message' => 'OK',
            'data' => UserResource::make($user)
        ], Http::OK());
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'status_code' => Http::OK,
            'message' => 'OK',
        ], Http::OK());
    }
}
