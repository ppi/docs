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
        │   │   ├── app.yml
        │   │   ├── datasource.yml
        │   │   └── modules.yml
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
        │   │       │   ├── config.yml
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

    // Lets include PPI
    require_once 'app/init.php';

    // Create our PPI App instance
    $app = new PPI\App('production', false);

    // Configure the application (app/config/app.yml)
    $app->loadConfig('app.yml');

    // Load the application, match the URL and send an HTTP response
    $app->boot()->dispatch()->send();

Switching between environments
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

PPI supports the notion of "environments" to make the application behave differently from when you are coding and testing the application in your laptop to when you deploy it to a production server. While in *production* debug messages won't be logged, your application won't stop due to non-fatal PHP errors and we'll use caching wherever possible. In *development* you'll get everything!

To switch between the *development* and *production* environments simply set the ``PPI\App($environment, $debug)`` parameters in your front controller:

.. code-block:: php

    // Development
    $app = new PPI\App('development', true);

    // Production
    $app = new PPI\App('production', false);

.. todo::

    Show an example of using two front controllers (``index.php``, ``index_development.php``) and a symlink to switch between environments. Alternatively provide an example with setting the environment by setting Apache environment variables.

The app folder
--------------

This is where all your apps global items go such as app config, datasource config and modules config and global templates (views). You wont need to touch these out-of-the-box but it allows for greater flexibility in the future if you need it.

.. note::

    In 2.1 we changed the default configuration file format from PHP to YAML because (we think) it is less verbose and faster to type but don't worry because PHP configuration files are and will always be supported.

The app.yml file
----------------

Looking at the example config file below, you can control things here such as the enabled templating engines, the datasource connection and the logger (``monolog``).

.. configuration-block::

    .. code-block:: yaml

        imports:
            - { resource: datasource.yml }
            - { resource: modules.yml }

        framework:
            view:
                engines: ["php", "smarty", "twig"]
                globals:
                    - ga_tracking: "UA-XXXXX-X"

        skeleton.module.path: "./utils/skeleton_module"

        monolog:
            handlers:
                main:
                    type:  stream
                    path:  %app.logs_dir%/%app.environment%.log
                    level: debug

    .. code-block:: php

        <?php
        $connections = require __DIR__ . '/datasource.php';
        $modules     = require __DIR__ . 'modules.php';

        return array_merge(array(
            'framework' => array(
                'view'      => array(
                    'engines'   => array('php', 'smarty', 'twig'),
                    'globals'   => array(
                        'ga_tracking'   => 'UA-XXXXX-X',
                    ),
                ),
                'datasource' => array(
                    'connections' => $connections
                ),
            ),
            'skeleton.module.path'   => './utils/skeleton_module',

        ), $modules);

.. tip::
    The configuration shown above is not exhaustive. For a complete listing of available configuration options please check the sections in the  :doc:`/reference/index` chapter.

The datasource.yml file
-----------------------

The ``datasource.yml`` is where you setup your database connection information.

.. warning::
    Because this file may hold sensitive information consider not adding it to your source control system.

.. configuration-block::

    .. code-block:: yaml

        framework:
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
            - Framework
            - Application
            - UserModule

        module_listener_options:
            module_paths: ['./modules', './vendor']

    .. code-block:: php

        <?php
        return array(
            'modules' => array(
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