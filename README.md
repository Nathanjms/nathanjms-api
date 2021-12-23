## Laravel backend to my [website](https://nathanjms.co.uk). 

### Setup:

The development is now set up to use docker. To set this up, do the following steps:

1. Run `cp .env.example .env`.
2. Ensure `docker-compose` is installed (or `docker compose` can be used on Mac) and the Docker daemon is running.
    - On Linux, this is done with `systemctl start docker.service`.
    - On Mac, this is done by launching Docker Desktop.
3. Run `docker-compose build` to build from the Dockerfile (or `docker compose build` on Mac).
4. Run `docker-compose up -d` to up the containers (or `docker compose up` on Mac).
5. To install the composer packages via the docker container, run `docker exec app composer install`.
6. Then set up the project with `docker exec app php artisan key:generate`.
7. Migrate the database with `docker exec app php artisan migrate`.
8. Seed the database with `docker exec app php artisan db:seed`.
9. The API is now reachable through `localhost` or `http://api.nathanjms.local`.
    - Ideally I will add an ssl certificate to this if I find the time (and figure out how!), and also will move the  
        [frontend](https://github.com/Nathanjms/nathanjms-movies) of the application to `movies.nathanjms.local`
### Features include:

- Authentication system, where a logged in user can access the Movies api, which can:
    - Retrieve list of movies belonging to the user's group
    - Add movies to the user's group's watch list 
    - Mark movies as 'seen'
    - See list of movies on watchlist and movies that have been watched
    - Add Ratings to watched movies!
    - ~~See top IMDB Movies~~ (Removed for now)

Backend is hosted on Heroku.
