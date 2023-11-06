<div style="text-align: center;"><h1> Trivia game test project </h1> </div>

<p>
    This project is using <a href="http://numbersapi.com/"> Numbers API </a> to get questions and generate quizzes
</p>
<p>
    For quiz questions it also generates wrong answers depending on type of the question from numbers API 
</p>

<h3> setup instructions: </h3>
<ul>
    <li>composer install</li>
    <li>cp .env.example .env</li>
    <li>php artisan key:generate</li>
    <li>fill database credentials in .env</li>
    <li>php artisan migrate</li>
    <li>php artisan l5-swagger:generate (to generate api documentation on /api/documentation)</li>
    <li>npm install</li>
    <li>npm run prod</li>
</ul>
That's it