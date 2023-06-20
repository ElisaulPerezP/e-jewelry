<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PlaceToPayPaymentService
{
    public function pay(Order $order, string $ip, string $userAgent): Order
    {
        $result = Http::post(
            'https://checkout-co.placetopay.dev/api/session',
            $this->createSession($order, $ip, $userAgent)
        );
        if ($result->ok()) {
            $order->request_id = $result->json()['requestId'];
            $order->process_url = $result->json()['processUrl'];
            $order->state = 'pending';
            $order->save();

            return $order;
        }

        throw new Exception($result->body());
    }
    /**
     * @return array<string, array<string>>
     */
    private function createSession(Order $order, string $ipAddress, string $userAgent): array
    {
        return [
            'auth' => $this->getAuth(),
            'buyer' => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'payment' => [
                'reference' => $order->id,
                'description' => $order->description,
                'amount' => [
                    'currency' => $order->currency,
                    'total' => $order->total,
                ],
            ],
            'expiration' => Carbon::now()->addHour()->toIso8601String(),
            'returnUrl' => 'http://127.0.0.1:8000/' . 'order/' . $order->id . '/show',
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
        ];
    }
    /**
     * @return array<string, string>
     */
    private function getAuth(): array
    {
        $nonce = Str::random();
        $seed = date('c');

        return [
            'login' => 'e3bba31e633c32c48011a4a70ff60497',
            'tranKey' => base64_encode(
                hash(
                    'sha256',
                    $nonce . $seed . 'ak5N6IPH2kjljHG3',
                    true
                )
            ),
            'nonce' => base64_encode($nonce),
            'seed' => $seed,
        ];
    }

    /**
     * @throws Exception
     */
    public function getRequestInformation(Order $order): RedirectResponse
    {
        $result = Http::post('https://checkout-co.placetopay.dev/api/session/' . $order->request_id, [
                'auth' => $this->getAuth(),
            ]);

        if ($result->ok()) {
            $status = $result->json()['status']['status'];
            if ($status == 'APPROVED') {
                $order->approved();
            } elseif ($status == 'REJECTED') {
                $order->rejected();
            }

            return redirect(route('orders.show', $order));
        }

        throw  new Exception($result->body());
    }
}
