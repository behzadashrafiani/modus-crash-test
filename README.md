Modus Create company vehicles crash test
========================

This is an assignment of Modus Create company

1) Installing
----------------------------------

When it comes to installing this project, you have the
following options.

### Use Composer (*recommended*)

As Symfony uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the `create-project` command to generate a new Symfony application:

    php composer.phar create-project  behzadashrafiani/modus-crash-test --stability=dev path/to/install

Composer will install Symfony and all its dependencies under the
`path/to/install` directory.

### Download an Archive File

To quickly test Symfony, you can also download an [archive][3] of the Standard
Edition and unpack it somewhere under your web server root directory.

If you downloaded an archive "without vendors", you also need to install all
the necessary dependencies. Download composer (see above) and run the
following command:

    php composer.phar install

2) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the `check.php` script from the command line:

    php app/check.php

Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.

3) Browsing the Demo Application
--------------------------------

Congratulations! You're now ready to use this project.
Use:
    
    php bin\console server:run

to run local server.

you can run commands to test the API as well:

    http "http://localhost:8000/vehicles/{MODELYEAR]/{MANUFACTURER}/{MODEL}"
    http "http://localhost:8000/vehicles/{MODELYEAR]/{MANUFACTURER}/{MODEL}?withRating=true"
    http POST "http://localhost:8000/vehicles/"