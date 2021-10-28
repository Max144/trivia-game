<?php

declare(strict_types=1);

namespace Tests\Fakes;

use App\Services\API\NumbersApi\NumbersApiClient;

class NumbersApiClientFake extends NumbersApiClient
{
    protected NumbersApiResponseFake $numbersApiResponse;

    /**
     * @param NumbersApiResponseFake $numbersApiResponse
     */
    public function setNumbersApiResponse(NumbersApiResponseFake $numbersApiResponse): void
    {
        $this->numbersApiResponse = $numbersApiResponse;
    }

    protected function getNewQuestionData(string $type)
    {
        return $this->numbersApiResponse;
    }
}