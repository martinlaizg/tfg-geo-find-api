<?php
namespace App\Http\Middleware;

use App\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Monolog\Logger;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $log = new Logger(__METHOD__);
        $token = $request->bearerToken();

        if (!$token) {
            $log->debug('No token');
            // Unauthorized response if token not there
            return response()->json(['type' => 'auth', 'message' => 'Token not provided'], 401);
        }
        try {
            $log->debug('Decode token='.$token);
            $log->debug('Token secret='.env('JWT_SECRET'));
            // Decode the recived token
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            $log->debug('Expired token');
            return response()->json(['type' => 'expired', 'message' => 'Provided token is expired'], 401);
        } catch (Exception $e) {
            $log->debug('Invalid token ');
            return response()->json(['type' => 'token', 'message' => 'Invalid token'], 401);
        }

        $user = User::find($credentials->sub);
        if ($user == null) {
            return response()->json(['type' => 'user', 'message' => 'The JTW user do not exist'], 404);
        }
        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;

        // Continue request
        $response = $next($request);

        // Generate the new token
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + (60 * 60 * 24 * 7), // Expiration date 60s * 60min * 24h * 7d
        ];
        $newToken = JWT::encode($payload, env('JWT_SECRET'));
        return $response->header('Authorization', $newToken);
    }
}
