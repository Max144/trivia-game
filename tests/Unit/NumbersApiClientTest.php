<?php

namespace Tests\Unit;

use App\Exceptions\TriviaApiParseException;
use App\Exceptions\TriviaApiWrongTypeException;
use App\Services\API\NumbersApi\NumbersApiClient;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\DateNumbersApiQuestion;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\MathNumbersApiQuestion;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\TriviaNumbersApiQuestion;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\YearNumbersApiQuestion;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Fakes\NumbersApiResponseFake;
use Tests\TestCase;
use Tests\Fakes\NumbersApiClientFake;

class NumbersApiClientTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->swap(NumbersApiClient::class, new NumbersApiClientFake());
    }

    private function resolveNumbersApiClient(): NumbersApiClientFake
    {
        return resolve(NumbersApiClient::class);
    }

    public function test_get_trivia_question()
    {
        $numbersApiClient = $this->resolveNumbersApiClient();

        $questionText = $this->faker->text;
        $answer = $this->faker->randomNumber();

        $numbersApiClient->setNumbersApiResponse(new NumbersApiResponseFake($questionText, $answer));
        $questionObject = $numbersApiClient->getNewTriviaQuestion();

        $this->assertEquals(TriviaNumbersApiQuestion::class, get_class($questionObject));
        $this->assertEquals($questionObject->getAnswer(), $answer);
        $this->assertEquals($questionObject->getQuestion(), $questionText);
    }

    public function test_get_math_question()
    {
        $numbersApiClient = $this->resolveNumbersApiClient();

        $questionText = $this->faker->text;
        $answer = $this->faker->randomNumber();

        $numbersApiClient->setNumbersApiResponse(new NumbersApiResponseFake($questionText, $answer));
        $questionObject = $numbersApiClient->getNewMathQuestion();

        $this->assertEquals(MathNumbersApiQuestion::class, get_class($questionObject));
        $this->assertEquals($questionObject->getAnswer(), $answer);
        $this->assertEquals($questionObject->getQuestion(), $questionText);
    }

    public function test_get_date_question()
    {
        $numbersApiClient = $this->resolveNumbersApiClient();

        $questionText = $this->faker->text;
        $date = Carbon::parse($this->faker->date);
        $answer = $date->diffInDays($date->copy()->startOfYear(), $date) + 2;
        $year = $date->year;

        $numbersApiClient->setNumbersApiResponse(
            new NumbersApiResponseFake($questionText, $answer, true, $year)
        );
        $questionObject = $numbersApiClient->getNewDateQuestion();

        $this->assertEquals(DateNumbersApiQuestion::class, get_class($questionObject));
        $this->assertEquals($questionObject->getAnswer(), $date);
        $this->assertEquals($questionObject->getQuestion(), $questionText);
    }

    public function test_get_year_question()
    {
        $numbersApiClient = $this->resolveNumbersApiClient();

        $questionText = $this->faker->text;
        $answer = $this->faker->randomNumber();

        $numbersApiClient->setNumbersApiResponse(new NumbersApiResponseFake($questionText, $answer));
        $questionObject = $numbersApiClient->getNewYearQuestion();

        $this->assertEquals(YearNumbersApiQuestion::class, get_class($questionObject));
        $this->assertEquals($questionObject->getAnswer(), $answer);
        $this->assertEquals($questionObject->getQuestion(), $questionText);
    }

    public function test_get_random_type_question_types_are_right()
    {
        $numbersApiClient = $this->resolveNumbersApiClient();
        $questionText = $this->faker->text;
        $answer = $this->faker->randomNumber();
        $year = $this->faker->year();

        $numbersApiClient->setNumbersApiResponse(new NumbersApiResponseFake($questionText, $answer, true, $year));
        for ($i = 0; $i < 10; $i++) {
            $numberOfAllowedTypes = $this->faker->numberBetween(1, count(NumbersApiClient::QUESTION_TYPES));

            $allowedTypes = array_rand(NumbersApiClient::QUESTION_TYPES, $numberOfAllowedTypes);
            if (!is_array($allowedTypes)) {
                $allowedTypes = [$allowedTypes];
            }
            $allowedReturnedClassNames = array_map(
                fn($type) => NumbersApiClient::QUESTION_TYPES_QUESTION_CLASSES_MAPPER[$type],
                $allowedTypes
            );

            $questionObject = $numbersApiClient->getNewRandomTypeQuestion($allowedTypes);
            $this->assertTrue(in_array(get_class($questionObject), $allowedReturnedClassNames));
        }
    }

    public function test_get_question_response_not_found()
    {
        $numbersApiClient = $this->resolveNumbersApiClient();

        $questionText = $this->faker->text;
        $answer = $this->faker->randomNumber();

        $numbersApiClient->setNumbersApiResponse(new NumbersApiResponseFake($questionText, $answer, false));
        $this->expectException(TriviaApiParseException::class);
        $numbersApiClient->getNewRandomTypeQuestion();
    }
}