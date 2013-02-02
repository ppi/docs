.. index::
   single: Modules

Modules
=======

By default, one module is provided with the SkeletonApp, named **Application**. It provides a simple route pointing to the homepage. A simple controller to handle the "home" page of the application. This demonstrates using routes, controllers and views within your module.

Module Structure
-----------------

Your module starts with Module.php. You can have configuration on your module. Your can have routes which result in controllers getting dispatched. Your controllers can render view templates.

.. code-block:: bash

    modules/

        Application/

            Module.php

            Controller/
                Index.php

            resources/

                views/
                    index/index.html.php
                    index/list.html.php

                config/
                    config.php
                    routes.yml


The Module.php class
--------------------

Every PPI module looks for a ``Module.php`` class file, this is the starting point for your module.

.. code-block:: php

    <?php
    namespace Application;

    use PPI\Module\Module as BaseModule
    use PPI\Autoload;

    class Module extends BaseModule {

        protected $_moduleName = 'Application';

        function init($e) {
            Autoload::add(__NAMESPACE__, dirname(__DIR__));
        }

    }

Init
----

The above code shows you the Module class, and the all important ``init()`` method. Why is it important? If you remember from The Skeleton Application section previously, we have defined in our ``modules.config.php`` config file an activeModules option, when PPI is booting up the modules defined activeModules it looks for each module's init() method and calls it.

The ``init()`` method is run for every page request, and should not perform anything heavy. It is considered bad practice to utilize these methods for setting up or configuring instances of application resources such as a database connection, application logger, or mailer.

Your modules resources
----------------------

``/Application/resources/`` is where non-PHP-class files live such as config files (``resources/config``) and views (``resources/views``). We encourage you to put your own custom config files in ``/resources/config/`` too.

Configuration
-------------

Expanding on from the previous code example, we're now adding a ``getConfig()`` method. This must return a raw php array. All the modules with getConfig() defined on them will be merged together to create 'modules config' and this is merged with your global app's configuration file at ``/app/app.config.php``. Now from any controller you can get access to this config by doing ``$this->getConfig()``. More examples on this later in the Controllers section.

.. code-block:: php

    <?php
    class Module extends BaseModule {

    protected $_moduleName = 'Application';

        public function init($e) {
            Autoload::add(__NAMESPACE__, dirname(__DIR__));
        }

        public function getConfig() {
            return include(__DIR__ . '/resources/config/config.php');
        }

    }

Routing
-------

The getRoutes() method currently is re-using the Symfony2 routing component. It needs to return a Symfony RouteCollection instance. This means you can setup your routes using PHP, YAML or XML.

.. code-block:: php

    class Module extends BaseModule {

        protected $_moduleName = 'Application';

        public function init($e) {
            Autoload::add(__NAMESPACE__, dirname(__DIR__));
        }

        /**
        * Get the configuration for this module
        *
        * @return array
        */
        public function getConfig() {
            return include(__DIR__ . '/resources/config/config.php');
        }

        /**
        * Get the routes for this module, in YAML format.
        *
        * @return \Symfony\Component\Routing\RouteCollection
        */
        public function getRoutes() {
            return $this->loadYamlRoutes(__DIR__ . '/resources/config/routes.yml');
        }

    }

Conclusion
----------

So, what have we learnt in this section so far? We learnt how to initialize our module, and how to obtain configuration options and routes from it.

PPI will boot up all the modules and call the ``getRoutes()`` method on them all. It will merge the results together and match them against a request URI such as ``/blog/my-blog-title``. When a matching route is found it dispatches the controller specified in that route.

Lets move onto the Routing section to check out what happens next.