.. index::
   single: Skeleton Application

.. _`skeleton-application`:

Skeleton Application
====================

The skeleton application is a fully-functional application that we have pre-built for you to get up and running as quickly as possible. Inside you'll find the PHP libraries (``vendor`` dir), a selection of useful modules, our recommended directory structure and some default configuration.

First, lets review the file structure of the PPI skeleton application:

.. code-block:: bash

    www/                                        # your web root directory
    |
    └── skeleton/                               # the unpacked archive
        |
        ├── app/
        │   ├── console                         # CLI script to help debug the application
        │   ├── init.php
        │   ├── config/                         # application configuration files
        │   │   ├── base/                       # base configuration to be extended by other environments
        │   │   │   ├── app.yml
        │   │   │   └── datasource.yml
        │   │   ├── dev/                        # configuration for the development environment (``dev``)
        │   │   │   └── app.yml
        │   │   ├── prod/                       # configuration for the production environment (``prod``)
        │   │   │   └── app.yml
        │   ├── cache/                          # application cache (must be writable by the web server)
        │   ├── logs/                           # application logs  (must be writable by the web server)
        │   └── views/                          # global template (view) files
        │       └── base.html.php
        │
        ├── modules/                            # application modules
        │   ├── Application/
        │   │   ├── Classes/
        │   │   │   └── SomeClass.php
        │   │   ├── Controller/
        │   │   │   ├── Index.php
        │   │   │   └── Shared.php
        │   │   ├── Module.php
        │   │   └── resources/
        │   │       ├── config/
        │   │       │   ├── config.php
        │   │       │   └── routes.yml          # routing rules
        │   │       └── views/
        │   │           └── index/
        │   │               └── index.html.php
        │   ├── Framework/
        │   └── UserModule/
        │
        ├── public/
        │   ├── index.php                       # front controller
        │   ├── css/
        │   ├── images/
        │   └── js/
        │
        └── vendor/                             # libraries installed by Composer

Lets break it down into parts:

The public folder
-----------------

The structure above shows you the ``/public/`` folder. Anything outside of ``/public/`` i.e: all your business code will be secure from direct URL access. In your development environment you don't need a virtualhost file, you can directly access your application like so: http://localhost/skeleton/public/. In your production environment this will be http://www.mysite.com/. All your publicly available asset files should be here, CSS, JS, Images.

The public index.php file
-------------------------

The ``/public/index.php`` is also known as your bootstrap file, or front controller and is presented below:

.. code-block:: php

    <?php

    // All relative paths start from the main directory, not from /public/
    chdir(dirname(__DIR__));

    // Setup autoloading and include PPI
    require_once 'app/init.php';

    // Set the environment
    $env     = getenv('PPI_ENV') ?: 'dev';
    $debug   = getenv('PPI_DEBUG') !== '0'  && $env !== 'prod';

    // Create our PPI App instance
    $app = new PPI\App(array(
        'environment'   => $env,
        'debug'         => $debug
    ));

    // Configure the application
    $app->loadConfig($app->getEnvironment().'/app.php');

    // Load the application, match the URL and send an HTTP response
    $app->boot()->dispatch()->send();


Environments
------------

PPI supports the notion of "environments" to make the application behave differently from when you are coding and
testing the application in your laptop to when you deploy it to a production server. While in *production* debug
messages won't be logged, your application won't stop due to non-fatal PHP errors and we'll use caching wherever
possible. In *development* you'll get everything!

Switching between environments
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To switch between the *development* (``dev``) and *production* (``prod``) environments simply set the
``PPI\App(array $options)`` parameters in your front controller:

.. code-block:: php

    // file: public/index.php

    // Development
    $app = new PPI\App(array(
        'environment'   => 'dev',
        'debug'         => true
    ));

    // Production
    $app = new PPI\App(array(
        'environment'   => 'prod',
        'debug'         => false
    ));

Auto-set the environment using web server variables
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Editing ``index.php`` whenever you want to test the application in another environment can be tedious.
An alternative is to set environment variables in your web server on a per vhost basis.

If you're using Apache, environment variables can be set using the `SetEnv <http://httpd.apache.org/docs/current/env.html>`_ directive.

**Production** VirtualHost configuration:

.. code-block:: apache

    <VirtualHost *:80>
           ServerName    prod.skeletonapp.ppi.localhost
           DocumentRoot  "/var/www/skeleton/public"
           SetEnv        PPI_ENV prod
           SetEnv        PPI_DEBUG false
           ...

And a **development** VirtualHost configuration:

.. code-block:: apache

    <VirtualHost *:80>
           ServerName    dev.skeletonapp.ppi.localhost
           DocumentRoot  "/var/www/skeleton/public"
           SetEnv        PPI_ENV dev
           SetEnv        PPI_DEBUG true
           ...

The front controller (``index.php``) needs to be slightly edited to load these environment variables:

.. code-block:: php

    // file: public/index.php

    // Set the environment
    $env     = getenv('PPI_ENV') ?: 'dev';
    $debug   = getenv('PPI_DEBUG') !== '0'  && $env !== 'prod';

    // Create our PPI App instance
    $app = new PPI\App(array(
        'environment'   => $env,
        'debug'         => $debug
    ));

After this change ``http://prod.skeletonapp.ppi.localhost/`` will use production settings while
``http://dev.skeletonapp.ppi.localhost/`` is configured to work in development mode.

Creating a new environment
~~~~~~~~~~~~~~~~~~~~~~~~~~

You don't need to be restricted to the ``dev`` and ``prod`` environments. To create a new environment with a special
configuration, let's call it ``staging``, just copy the folder contents of an existing environment to the new one
and edit the ``app.yml`` file inside the ``staging`` dir.

.. code-block:: bash

    $ cd /path/to/skeletonapp/app/config
    $ cp -r prod staging
    $ vim staging/app.yml

Now make sure ``public/index.php`` is picking up your new environment:

.. code-block:: php

    <?php
    // ...

    // Staging
    $app = new PPI\App(array(
        'environment'   => 'staging',
        'debug'         => true
    ));

    $app->loadConfig($app->getEnvironment().'/app.yml');

    // ...

.. note::

    PPI creates cache and log files associated with each environment. For this new ``staging`` environment cache files
    will be available under ``app/cache/staging/`` and the log file is available at ``app/logs/staging.log``.

The app folder
--------------

This is where all your apps global items go such as app config, datasource config and modules config and global
templates (views). You wont need to touch these out-of-the-box but it allows for greater flexibility in the future if
you need it.

The app/config folder
---------------------

Starting with version 2.1 all the application configuration lives inside ``app/config/<env>/`` folders. Each ``<env>``
folder holds configuration for a specific environment: ``dev``, ``prod``.

Supported configuration formats
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

PPI supports both PHP and `YAML <http://yaml.org/>`_ formats. PHP is more powerful whereas YAML is more clean and readable.
It is up to you to pick the format of your liking.

.. note::

    In 2.1 we changed the default configuration file format from PHP to YAML because (we think) it is less verbose and
    faster to type but don't worry because PHP configuration files are and will always be supported.

YAML imports/include
~~~~~~~~~~~~~~~~~~~~

The YAML language doesn't natively provide the capability to include other YAML files like a PHP ``include`` or ``require`` statement.
To overcome this limitation PPI supports two special syntaxes: ``imports`` and ``@include``.

.. note::

    One of the goals of the PPI Framework is to provide an environment familiar to users coming from or going to the
    Symfony and Zend frameworks (among others). We support these two variants so these users do not need to worry about
    learning new syntaxes.


**imports**:

Available in the `Symfony framework <http://symfony.com/doc/current/book/page_creation.html#environment-configuration>`_. Works like a PHP include statement providing base configuration to be tweaked in
the current file. It is usually added at the top of the file.

    .. code-block:: yaml

        imports:
            - { resource: ../base/app.yml }

**@include**:

Available in the `Zend framework <http://framework.zend.com/manual/2.2/en/modules/zend.config.reader.html#zend-config-reader-yaml>`_. Similar to the ``imports`` syntax but can be used also in a subelement of a value.

    .. code-block:: yaml

        framework:
            @include: ../base/datasource.yml



The app.yml file
----------------

Looking at the example config file below, you can control things here such as the enabled templating engines, the datasource connection and the logger (``monolog``).

.. configuration-block::

    .. code-block:: yaml

        imports:
            - { resource: datasource.yml }
            - { resource: modules.yml }

        framework:
            templating:
                engines: ["php", "smarty", "twig"]
            skeleton_module:
                path: "./utils/skeleton_module"

        monolog:
            handlers:
                main:
                    type:  stream
                    path:  %app.logs_dir%/%app.environment%.log
                    level: debug

    .. code-block:: php

        <?php
        $config = array();

        $config['framework'] = array(
            'templating' => array(
                'engines'     => array('php', 'smarty', 'twig'),
            ),
            'skeleton_module'   => array(
                'path'  => './utils/skeleton_module'
            )
        );

        $config['datasource'] => array(
            'connections' = require __DIR__ . '/datasource.php'
        );

        $config['modules'] = require __DIR__ . 'modules.php';

        return $config;

.. tip::
    The configuration shown above is not exhaustive. For a complete listing of the available configuration options please check the sections in the  :doc:`/reference/index` chapter.

The datasource.yml file
-----------------------

The ``datasource.yml`` is where you setup your database connection information.

.. warning::
    Because this file may hold sensitive information consider not adding it to your source control system.

.. configuration-block::

    .. code-block:: yaml

        datasource:
            connections:
                main:
                    type:   'pdo_mysql'
                    host:   'localhost'
                    dbname: 'ppi2_skeleton'
                    user:   'root'
                    pass:   'secret'

    .. code-block:: php

        <?php
        return array(
            'main' => array(
                'type'   => 'pdo_mysql',    // This can be any pdo driver. i.e: pdo_sqlite
                'host'   => 'localhost',
                'dbname' => 'ppi2_skeleton',
                'user'   => 'root',
                'pass'   => 'secret'
            )
        );


The modules.yml file
--------------------

The example below shows that you can control which modules are active and a list of directories module_paths that PPI will scan for your modules. Pay close attention to the order in which your modules are loaded. If one of your modules relies on resources loaded by another module. Make sure the module loading the resources is loaded before the others that depend upon it.

.. configuration-block::

    .. code-block:: yaml

        modules:
            active_modules:
                - Framework
                - Application
                - UserModule

            module_listener_options:
                module_paths: ['./modules', './vendor']

    .. code-block:: php

        <?php
        return array(
            'active_modules' => array(
                'Framework',
                'Application',
                'UserModule',
            ),
            'module_listener_options' => array(
                'module_paths' => array('./modules', './vendor')
            ),
        );

The app/views folder
--------------------

This folder is your applications global views folder. A global view is one that a multitude of other module views extend from. A good example of this is your website's template file. The following is an example of ``/app/views/base.html.php``:

.. code-block:: html+php

    <html>
        <body>
            <h1>My website</h1>
            <div class="content">
                <?php $view['slots']->output('_content'); ?>
            </div>
        </body>
    </html>

You'll notice later on in the Templating section to reference and extend a global template file, you will use the following syntax in your modules template.

.. code-block:: html+php

    <?php $view->extend('::base.html.php'); ?>

Now everything from your module template will be applied into your base.html.php files _content section demonstrated above.

The modules folder
-------------------

This is where we get stuck into the real details, we're going into the ``/modules/`` folder. Click the next section to proceed.