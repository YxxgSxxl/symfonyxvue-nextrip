# ✈️ NexTrip !

The goal was to make me learn symfony 7 API REST.

For this project, Symfony will handle API REST requests, VueJS will handle the front-end interface.

For VueJS, Typescript, SCSS, Normalize.CSS and Pinia will be used.

Yarn is used to manage client side Application packages.

This app doesn't require an ORM to work.

## Installation

At first, clone the repository:

```
git clone https://github.com/YxxgSxxl/symfonyxvue-nextrip.git
```

Change directory to the repository:

```
cd symfonyxvue-nextrip
```

To install every depedencies that you'll need, run these:

```
cd api/
```

And

```
composer install
```

## Config

To configure the back-end environment, add your API key with this command:

```
php bin/console secrets:set API_KEY_SECRET
```

To see what you typed in (it will show the information in clear):

```
php bin/console secrets:list --reveal
```

## Rundev

To run symfony back-end (symfonyxvue-nextrip/api):

```
symfony server:start
```

To run vue front-end (symfonyxvue-nextrip/client):

```
yarn run dev
```

Then you can open the localhost URL showed in your console.
