# Pitch Demo

This repository holds the code and script
for the(https://symfonycasts.com/screencast/api-platform) course on SymfonyCasts.  

## Setup

By default, the demo uses sqlite.  If you want to use another datatabase, configure DATABASE_URL in .env.local

**Download and Setup**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
git clone https://github.com/ph-fritsche/pitch-demo.git 
cd pitch-demo
composer install
```

You may alternatively need to run `php composer.phar install`, depending
on how you installed Composer.

**Configure the .env (or .env.local) File**

The default database is sqlite, so the ext-sqlite should be installed, or set DATABASE_URL in .env.local to use another database.

**Setup the Database**

Again, make sure `.env` is setup for your computer. Then, create
the database & tables!

```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

**Start the built-in web server**

You can use Nginx or Apache, but Symfony's local web server
works even better.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve
```

(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

Now check out the site at `https://localhost:8000`

## Dependencies

phf/collection is a utility to express someType[]. It is not necessary to use it at all, just a convenient method to express a List of typed elements.

pitch/symfony-adr provides the separation of Response and Action. Just like you define classes for entities that you store in the database, you should create entity classes for payload created at runtime, e.g. a paginated table page as response to a search endpoint. Then you add handlers to convert these entities into a response that is acceptable for the client. For a browser view this might be HTML and for a JS client library that might be JSON and for a spreadsheet export this might be XLSX.

pitch/form allows you to remove the reoccurring form handling from controllers and just deal with the submitted and valid form there. If you use it with pitch/symfony-adr, you can add handlers to convert the FormInterface for GET and invalid POST requests into responses that are acceptable for the client.
