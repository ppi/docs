Requirements
============

To easily check if your system passes all requirements, PPI provides two ways and we recommend you do both.

Why do we have both scripts? Because your CLI environment can have a separate **php.ini** file from your web environment so this will ensure you're good to go from both sides.

Requirements checking on the command-line
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: bash

    $ ./app/check
                           _
            _____   _____ |_|
           / __  | / __ | / |
          | |__| || |__| || |
          |  ___/ |  ___/ | |
          | |     | |     |_|
          |/      |/

          Framework Version 2

        -- Requirements Check --

    * Configuration file used by PHP: /etc/php/cli-php5.4/php.ini
    * Mandatory requirements **

    OK       PHP version must be at least 5.3.3 (5.4.13--pl0-gentoo installed)
    OK       PHP version must not be 5.3.16 as PPI won't work properly with it
    OK       Vendor libraries must be installed
    OK       app/cache/ directory must be writable
    OK       app/logs/ directory must be writable
    OK       date.timezone setting must be set
    OK       Configured default timezone "Europe/Lisbon" must be supported by your installation of PHP
    OK       json_encode() must be available
    OK       session_start() must be available
    OK       ctype_alpha() must be available
    OK       token_get_all() must be available
    OK       simplexml_import_dom() must be available
    OK       detect_unicode must be disabled in php.ini
    OK       xdebug.show_exception_trace must be disabled in php.ini
    OK       xdebug.scream must be disabled in php.ini
    OK       PCRE extension must be available

Watch out for the green ``OK`` markers. If they all light up, congratulations, you're good to go!

Below is the list of required and optional requirements.


Requirements checking in the browser
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The check.php script is accessible in your browser at: http://skeletonapp.ppi/check.php


Must have requirements
~~~~~~~~~~~~~~~~~~~~~~

* PHP needs to be a minimum version of PHP 5.3.3
* JSON needs to be enabled
* ctype needs to be enabled
* Your PHP.ini needs to have the date.timezone setting

Optional requirements
~~~~~~~~~~~~~~~~~~~~~

* You need to have the PHP-XML module installed
* You need to have at least version 2.6.21 of libxml
* PHP tokenizer needs to be enabled
* mbstring functions need to be enabled
* iconv needs to be enabled
* POSIX needs to be enabled (only on \*nix)
* Intl needs to be installed with ICU 4+
* APC 3.0.17+ (or another opcode cache needs to be installed)
* PHP.ini recommended settings

  * ``short_open_tag = On``
  * ``magic_quotes_gpc = Off``
  * ``register_globals = Off``
  * ``session.autostart = Off``