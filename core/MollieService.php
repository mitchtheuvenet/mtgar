<?php

namespace app\core;

use Mollie\Api\MollieApiClient;

class MollieService {

    private MollieApiClient $client;

    public function __construct(array $config) {
        $this->client = new MollieApiClient();
        $this->client->setApiKey($config['api_key']);
    }

    public function createDonation(string $amount, string $donationId): \Mollie\Api\Resources\Payment {
        return $this->client->payments->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => $amount
            ],
            'method' => \Mollie\Api\Types\PaymentMethod::IDEAL,
            'description' => "Donation #{$donationId}",
            'redirectUrl' => "{WEBSITE_PATH_HERE}/thanks?donation_id={$donationId}", // TODO: insert dynamic URL
            'webhookUrl' => "{WEBSITE_PATH_HERE}/mollie",
            'metadata' => [
                'donation_id' => $donationId
            ]
        ]);
    }

}