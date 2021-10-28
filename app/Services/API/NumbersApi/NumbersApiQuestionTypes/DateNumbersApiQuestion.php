<?php

declare(strict_types=1);

namespace App\Services\API\NumbersApi\NumbersApiQuestionTypes;

use Carbon\Carbon;

class DateNumbersApiQuestion extends NumbersApiQuestionAbstract
{
    // in response year is the year of date and number is the 1-indexed day of a leap year (eg. 61 would be March 1st)
    protected function setQuestionAndAnswerFromResponse(object $response)
    {
        // we get the first day of the year and add number of days needed
        $this->answer = Carbon::create($response->year)
            ->addDays($response->number - 2);
        $this->question = $response->text;
    }
}