
# QUIZ API Using Laravel

## Description
This is a RESTFUL API project designed to create quizzes, take quizzes and publish or share your results. I use Laravel (Sanctum) to create it and I'll hopefully make the frontend very soon using Vuejs. This is my first public repo (yay! :smile:) and I hope to be doing more of this.



## Features

- User authentication
- Create and Manage quizzes
- Take quizzes
- Share results
I'll be adding more features as I continue.

## Documentation

Documentation of the API endpoints can be found [here]()


## Demo

No demo yet


## Run Locally

Clone the project

```bash
  git clone https://github.com/Nahyomee/Quiz-API
```

Go to the project directory

```bash
  cd quiz-api
```

Install composer packages

```bash
  composer install
```

Set up .env file

```bash
    cp .env.example .env
    php artisan key:generate
```
Run migrations

PUT YOUR DB IN THE .ENV FILE
```bash
    php artisan migrate
```

Start the server

```bash
  php artisan serve
```


## Tech Stack

**Server:** Laravel

