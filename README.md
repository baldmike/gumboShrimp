# gumboShrimp

## About gumboShrimp

GumboShrimp is an eCommerce application that offers an online store, "Chango Cart". Admin users may log in and add and update items and see the dashboard which has current orders. Stripe is used for credit card processing. This is a Laravel 7 and Vue 2 application that runs in a Docker container.

### How to Use (WIP?)

1. when you click 'add item', the 'addItem' method dispatches an action which commits a mutation to CHANGE STATE === adds item to cart.
2. item gets added to cart (like you have), no double lines/entries
3. the cart icon at the top (on the navbar?) should have a number next to it, that shows how many items are in the cart (like a normal website).
4. when user clicks on 'cart' the router takes the user to CartComponent, which displays the items in the cart, with the ability to increase or decrease amounts
5. use 4242 4242 4242 4242 4/20 424 as your credit card creds

### Developer Setup (WIP)

You'll need Docker Desktop running, and the app runs at localhost:8088

1. Pull this repository
    - `git clone git@github.com:git@github.com:baldmike/gumboShrimp.git`
2. Copy `.env.example` file to `.env` and fill in necessary environment variables
    - `cp .env.example .env`
    - Populate `HOST_UID` with your user ID via `id -u`
3. Build your local images
    - `docker compose build`
4. Fire it up
    - `docker compose up -d`
5. Do the Composer dance
    - `docker compose exec php composer update`
    - `docker compose exec php composer install`
    - `docker compose exec php composer upgrade`
6. Do some Laravel stuff
    - `docker compose exec php php artisan key:generate`
    - `docker compose exec php php artisan migrate`
    - `docker compose exec php php artisan db:seed`
    - `docker compose exec php php artisan passport:install`
        - ** Add one of the returned Client IDs and Secrets to your `.env` for `PASSPORT_CLIENT_ID` & `PASSPORT_CLIENT_SECRET` **
    ##### this will give you an admin user `admin@example.com` with the password `password`
7. Make a new symbolic link for the uploaded images
    - get into your container `docker compose exec -it php sh`
    - cd into the public folder `cd public`
    - remove the existing storage folder `rm storage`
    - get back to root `cd ..`
    - create the symlink `php artisan storage:link`
    - exit out `exit`
8. Run tests
    - `docker compose exec php ./vendor/bin/phpunit`
9. Surf your way to localhost:8088

### Helpful Hints
- `php artisan optimize` will get you out of some jams. You'll have to run it in your docker box, so `docker exec -it php php artisan optimize`
- you could save having to type 'docker compose' every time, and just go into your docker box with `docker exec -it php sh` 
- FOR FRONT END DEVELOPMENT!!!!!!  MAKE SURE YOU RUN `npm run watch` from within the container to compile your changes real time

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

The gumboShrimp software is currently inheriting the [MIT license](https://opensource.org/licenses/MIT).
