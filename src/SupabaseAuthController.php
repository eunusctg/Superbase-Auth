<?php

namespace forumez\SupabaseAuth;

use Flarum\Api\Controller\AbstractShowController;
use Psr\Http\Message\ServerRequestInterface;
use Flarum\User\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laminas\Diactoros\Response\JsonResponse;

class SupabaseAuthController extends AbstractShowController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $jwt = $body['access_token'];

        try {
            $decoded = JWT::decode($jwt, new Key('YOUR_SUPABASE_JWT_SECRET', 'HS256'));
            $email = $decoded->email;

            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::register($email, null);
                $user->is_email_confirmed = true;
                $user->save();
            }

            // Set the session or generate Flarum API token here
            return new JsonResponse(['status' => 'success', 'user_id' => $user->id]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 401);
        }
    }
}
