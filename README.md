# INSIDER CHAMPIONSHIP

## About the project

This project aims to simulate English Premier League (EPL) season with 4 teams. English Premier League rules are followed. 

The project was built using Laravel at the backend, VueJS for the frontend, and Tailwind CSS for styling.

API-FOOTBALL is used to fetch EPL teams, which is a free API that provides information about world-wide football leagues.
## Installation

- Install PHP dependencies via composer
```bash
    composer install
```
- Install frontend dependencies via npm
```bash
    npm install
```
## Running up the project

- You can use your own development environment, or instead you can easily create a docker development environment via following commands. Note that you should have docker installed.
```bash
    ./vendor/bin/sail up
    ./vendor/bin/sail migrate
    ./vendor/bin/sail db:seed
```

## TODO
- [ ] Write unit tests 
- [ ] Allow to edit competition results.
