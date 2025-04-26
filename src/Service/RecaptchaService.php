<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class RecaptchaService
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        #[Autowire(env: 'GOOGLE_RECAPTCHA_SECRET')]
        private readonly ?string $secretKey,
    ) {
    }

    public function verifyRecaptcha(?string $recaptchaResponse): bool
    {
        if (empty($recaptchaResponse)) {
            throw new \Exception('Unable to verify recaptcha');
        }

        $response = $this->httpClient->request('POST', self::VERIFY_URL, [
            'body' => [
                'secret' => $this->secretKey,
                'response' => $recaptchaResponse
            ],
        ]);

        $verified = json_decode($response->getContent())->success ?? false;

        return $verified;
    }
}
