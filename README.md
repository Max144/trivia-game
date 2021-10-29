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