# ✈️ NexTrip !

This project is to make me learn symfony 7 API REST.

For this project, Symfony will handle API REST requests, VueJS will handle the front-end interface.

For VueJS, Typescript, SCSS, Normalize.CSS and Pinia will be used.

Yarn is used to manage client side Application packages

## Installation

To install every depedencies that you'll need, run these:

```
composer install
```

## Config

To config the back-end environment, add your API key with this command:

```
php bin/console secrets:set API_KEY_SECRET
```

To see what you typed in (it will show the information in clear):

```
php bin/console secrets:list --reveal
```

## Rundev

To run vue (symfonyxvue-nextrip/client):

```
yarn run dev
```

To run symfony (symfonyxvue-nextrip/api):

```
symfony server:start
```
