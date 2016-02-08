
.. index::
   single: Modules

Modules
=======

By default, one module is provided with the SkeletonApp, named **Application**. It provides a simple route pointing to the homepage. A simple controller to handle the "home" page of the application. This demonstrates using routes, controllers and views within your module.

Module Structure
----------------

Your module starts with Module.php. You can have configuration on your module. Your can have routes which result in controllers getting dispatched. Your controllers can render view templates.

.. code-block:: bash

    ├── Module.php
    ├── resources
    │   ├── config
    │   │   └── config.yml
    │   ├── routes
    │   │   ├── laravel.php
    │   │   └── symfony.yml
    │   └── views
    │       └── index
    │           └── index.html.php
    ├── src
    │   ├── Controller
    │   │   ├── Index.php
    │   │   └── Shared.php

The Module.php class
--------------------

Every PPI module looks for a ``Module.php`` class file, this is the starting point for your module.

.. code-block:: php

    <?php
    namespace Application;
    use PPI\Framework\Module\AbstractModule;

    class Module extends AbstractModule
    {
        public function getAutoloaderConfig()
        {
            return array(
                'Zend\Loader\StandardAutoloader' => array(
                    'namespaces' => array(
                        __NAMESPACE__ => __DIR__ . '/src/',
                    ),
                ),
            );
        }
    }

Init
----

The above code shows you the Module class, and the all important ``init()`` method. Why is it important? If you remember from The Skeleton Application section previously, we have defined in our ``modules.config.php`` config file an activeModules option, when PPI is booting up the modules defined activeModules it looks for each module's init() method and calls it.

The ``init()`` method is run for every page request, and should not perform anything heavy. It is considered bad practice to utilize these methods for setting up or configuring instances of application resources such as a database connection, application logger, or mailer.

Configuration
-------------

Expanding on from the previous code example, we're now adding a ``getConfig()`` method. This must return a raw PHP array. You may ``require/include`` a PHP file directly or use the ``loadConfig()`` helper that works for both PHP and YAML files. When using ``loadConfig()`` you don't need to tell the full path, just the filename.

All the modules with getConfig() defined on them will be merged together to create 'modules config' and this is merged with your global app's configuration file at ``/app/app.config.php``. Now from any controller you can get access to this config by doing ``$this->getConfig()``. More examples on this later in the Controllers section.

.. code-block:: php

    <?php
    class Module extends AbstractModule
    {
        /**
         * Returns configuration to merge with application configuration.
         *
         * @return array
         */
        public function getConfig()
        {
            return $this->loadConfig(__DIR__ . '/resources/config/config.yml');
        }
    }

.. tip::
    To help you troubleshoot the configuration loaded by the framework you may use the ``app/console config:dump`` command

Conclusion
----------

So far, we've learnt what methods to initialize our module, setup autoloading and configuration.

Lets move onto the Routing section to check out what happens next.