
<?php

namespace App\Http\Middleware;

use Stripe\Error\SignatureVerification;
use Stripe\Webhook as StripeWebhook;
use UnexpectedValueException;
use Closure, ResponseCode;

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
        if (!empty($sigHeader = $request->server->getHeaders()['STRIPE_SIGNATURE'])) {
            try {
                StripeWebhook::constructEvent($request->getContent(), $sigHeader, config('services.stripe.webhook_secret'));
            } catch(UnexpectedValueException $e) {
                abort(ResponseCode::HTTP_BAD_REQUEST, 'Invalid payload.');
            } catch(SignatureVerification $e) {
                abort(ResponseCode::HTTP_BAD_REQUEST, 'Invalid signature.');
            }
        } elseif (empty(request('key')) || request('key') != config('webhook.key')) {
            abort(ResponseCode::HTTP_UNAUTHORIZED, 'Unathorized.');
        }

        return $next($request);
    }
}
