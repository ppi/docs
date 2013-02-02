
Skeleton Application
==========================

First, lets review the file structure of the PPI skeleton application that we have pre-built for you to get up and running as quickly as possible.::

    www/ <- your web root directory

    skeleton/ <- the unpacked archive
        app/
            app.config.php
            cache/
            views/
            ...

        public/
            index.php
            css/
            js/
            images/
            ...

        modules/
            Application/
                Module.php
                Controller/
                resources/
                    config/
                    views/
                    ...


Lets break it down into parts:

The public folder
-----------------

The structure above shows you the ``/public/`` folder. Anything outside of ``/public/`` i.e: all your business code will be secure from direct URL access. In your development environment you don't need a virtualhost file, you can directly access your application like so: http://localhost/skeleton/public/. In your production environment this will be http://www.mysite.com/. All your publicly available asset files should be here, CSS, JS, Images.

The public index.php file
-------------------------

The /public/index.php is also known are your bootstrap file, or front controller is explained in-depth below

.. code-block:: php

    <?php

    // All relative paths start from the main directory, not from /public/
    chdir(dirname(__DIR__));

    // Lets include PPI
    include('app/init.php');

    // Initialise our PPI App
    $app = new PPI\App();
    $app->moduleConfig = include 'app/modules.config.php';
    $app->config = include 'app/app.config.php';

    // If you are using the DataSource component, enable this
    //$app->useDataSource = true;

    $app->boot()->dispatch();

If you uncomment the ``useDataSource`` line, it is going to look for your ``/app/datasource.config.php`` and load that into the DataSource component for you. Databases are not a requirement in PPI so if you dont need one then you wont need to bother about this. More in-depth documentation about this in the DataSource chapter.

The app folder
--------------

This is where all your apps global items go such as app config, datasource config and modules config and global templates (views). You wont need to touch these out-of-the-box but it allows for greater flexibility in the future if you need it.

The app.config.php file
-----------------------

Looking at the example config file below, you can control things here such as the environment, templating engine and datasource connection.

.. code-block:: php

    <?php
    $config = array(
        'environment' => 'development', // <-- Change this depending on your environment
        'templating.engine' => 'php', // <-- The default templating engine
        'datasource.connections' => include (__DIR__ . '/datasource.config.php')
    );

    // Are we in debug mode ?
    if($config['environment'] !== 'development') { // <-- You can also check the env from your controller using
        $this->getEnv()
        $config['debug'] = $config['environment'] === 'development';
        $config['cache_dir'] = __DIR__ . '/cache';
    }

    return $config; // Very important

The ``return $config`` line gets pulled into your ``index.php``'s ``$app->config`` variable.

The modules.config.php file
---------------------------

The example below shows that you can control which modules are active and a list of directories module_paths that PPI will scan for your modules. Pay close attention to the order in which your modules are loaded. If one of your modules relies on resources loaded by another module. Make sure the module loading the resources is loaded before the others that depend upon it.

.. code-block:: php

    <?php
    return array(
        'activeModules'   => array('Application', 'User'),
        'listenerOptions' => array('module_paths' => array('./modules')),
    );

Note that this file returns an array too, which is assigned against your ``index.php``'s $app->moduleConfig variable

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

This is where we get stuck into the real details, we're going into the ``/modules/`` folder. Click the next section to proceed