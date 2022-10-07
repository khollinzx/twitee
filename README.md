<h1 align="center">Twitee</h1>

<p align="center">
    <strong>Twitee is a mini and substandard runoff of Twitter.</strong>
</p>

## About Twitee

Twitee is a mini and substandard runoff of Twitter. Where Individual can register, log in, and post up anything that crosses their minds.

##Documentation
Twitee is a Backend API Application built on Laravel 8.5 Framework, with the use of the following dependencies.

- [Laravel Passport](https://packagist.org/packages/laravel/passport) and [Lcobucci JWT](https://packagist.org/packages/lcobucci/jwt) for authentication and authorization.
- [Mail Gun](https://app.mailgun.com/) for email dispatching.
- [MySQL Database](https://www.mysql.com/) for data storage.
- [Ramsey UUID](https://packagist.org/packages/ramsey/uuid) for generating unique random strings.

## Installation
Kindly take to following step to get the application up and running on your local device.

 - Clone the application from the related branch.
```bash
git clone https://github.com/khollinzx/twitee.git
```

 - Exist into the folder directory and run the command ```php artisan key:generate```.

 - Install required dependencies via [Composer]. Run the following
command to install the package and add it as a requirement to your project's
`composer.json`.

```bash
composer install
```

 - Log the `.evn.example` file, make duplicate and name it `.env`.

 - Make necessary changes to the `.env` as listed.
```bash
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

 - Create a MailGun Account, make changes to the variables on `.env` as listed.
```bash
MAIL_KEY=
MAIL_SENDER=
```
 - Run the `php artisan migrate` command to initialise migration file and create tables on your database.

 - Run the `php artisan serve` command to run your application.


## Purpose
This Application was built as an assessment test for [FBIS Technologies](https://fbistech.com/) - The first company to develop and launch a Trade Automation Mobile App
(RETOPA) that captured both B2B and B2C in the Telecom industry in Nigeria.


## Gratitude

Thanks to [FBIS Technologies](https://fbistech.com/) for giving me the privilege to showcase my skills.
