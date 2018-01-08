<?php

namespace App\Http\Middleware;

use Stripe\Error\SignatureVerification;
use Stripe\Webhook as StripeWebhook;
use Closure, UnexpectedValueException, ResponseCode;

class AuthenticateWebhook
{
    protected $except = ['/webhook/typeform'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = $request->server->getHeaders();
        $sigHeader = $headers['STRIPE_SIGNATURE'] ?? false;

        if (!empty($sigHeader)) {
            try {
                StripeWebhook::constructEvent($request->getContent(), $sigHeader, config('services.stripe.webhook_secret'));
            } catch (UnexpectedValueException $e) {
                abort(ResponseCode::HTTP_BAD_REQUEST, $e->getMessage());
            } catch (SignatureVerification $e) {
                abort(ResponseCode::HTTP_BAD_REQUEST, $e->getMessage());
            }
        } elseif ((empty(request('key')) || request('key') != config('webhook.key')) && !in_array($request->getPathInfo(), $this->except)) {
            abort(ResponseCode::HTTP_UNAUTHORIZED, 'Unauthorized.');
        }

        return $next($request);
    }
}
