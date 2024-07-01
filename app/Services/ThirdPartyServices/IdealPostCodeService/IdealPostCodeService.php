<?php

namespace App\Services\ThirdPartyServices\IdealPostCodeService;

use App\Services\ThirdPartyServices\IdealPostCodeService\Contracts\AddressVerificationService\AddressVerificationService;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class IdealPostCodeService implements AddressVerificationService
{
    private string $apiKey;

    private array $parameters;

    private ?string $requestUrl;

    private PendingRequest $client;

    private string $dtoClass;

    /**
     * IdealPostCodeService constructor.
     */
    public function __construct()
    {
        $this->apiKey = config('address-verification.ideal_postcodes.api_key');
        $this->client = Http::withOptions([
            'base_uri' => config('address-verification.ideal_postcodes.url'),
            'timeout' => config('address-verification.ideal_postcodes.timeout'),
        ]);
    }

    /**
     * @throws Exception
     */
    public function execute(): array
    {
        if (! $this->apiKey) {
            throw new \Exception('Providing an API key might be a better start.', Response::HTTP_UNAUTHORIZED);
        }
        if (! $this->requestUrl) {
            throw new \Exception('Providing an request url key might be a better start.', Response::HTTP_UNAUTHORIZED);
        }

        $response = $this->client->acceptJson()->get($this->requestUrl, $this->parameters);
        if ($response->failed()) {
            $response->throw();
        }
        $response = $response->json();

        return (new $this->dtoClass($response, $this->parameters['query']))->getReadableData();
    }

    public function setRequestParams(array $params): IdealPostCodeService
    {
        $this->resetRequestParams();
        $this->parameters = [
            'api_key' => $this->apiKey,
            ...$params,
        ];

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function resetRequestParams(): self
    {
        $this->parameters = [];

        return $this;
    }

    public function setRequestUrl(?string $url): IdealPostCodeService
    {
        $this->requestUrl = $url;

        return $this;
    }

    public function getRequestUrl(): ?string
    {
        return $this->requestUrl;
    }

    public function using(string $dtoClass): static
    {
        $this->dtoClass = $dtoClass;

        return $this;
    }
}
