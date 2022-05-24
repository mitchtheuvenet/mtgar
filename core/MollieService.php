<?php

namespace app\core;

use app\core\exceptions\NotFoundException;

use Mollie\Api\MollieApiClient;

class MollieService {

    private MollieApiClient $client;

    public function __construct(array $config) {
        $this->client = new MollieApiClient();
        $this->client->setApiKey($config['api_key']);
    }

    public function createDonation(string $amount, string $donationId): \Mollie\Api\Resources\Payment {
        if (!str_ends_with(Application::$ROOT_DIR, 'theuvenet.com')) {
            throw new NotFoundException();
        }
        
        return $this->client->payments->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => $amount
            ],
            'method' => \Mollie\Api\Types\PaymentMethod::IDEAL,
            'description' => "Donation #{$donationId}",
            'redirectUrl' => "https://www.theuvenet.com/thanks?donation_id={$donationId}", // TODO: insert dynamic URL
            'webhookUrl' => "https://www.theuvenet.com/mollie",
            'metadata' => [
                'donation_id' => $donationId
            ]
        ]);
    }

    public function processDonation(int $paymentId): bool {
        $payment = $this->client->payments->get($paymentId);
        $donationId = $payment->metadata->donation_id;

        if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargeBacks()) {
            $donation = DbDonation::findObject(['id' => ['value' => intval($donationId)]]);

            $donation->status = DbDonation::STATUS_PAID;

            return $donation->update(['status']);
        }

        return false;
    }

}