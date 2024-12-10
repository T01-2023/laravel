<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct(private readonly ResponseFactory $responseFactory) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');

        /** @var User $user */
        $user = User::query()->firstWhere('email', $email);

        if (! Hash::check($password, $user->password)) {
            return response()->json([
                'error' => 'Invalid credentials',
            ], 401);

        }
        $token = $user->createToken('web');

        return $this->responseFactory->json([
            'token' => $token->plainTextToken,
        ]);
    }
}
