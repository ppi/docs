
.. index::
   single: Routing

Routing
=======

Routes are the rules that tell the framework what URLs map to what actions of your application.

When PPI is booting up it will take call ```getRoutes()``` on each module and register its entry within the main ```ChainRouter```, which is a router stack.
PPI will call ```match``` on each router in the order that your modules have been defined in your application config.

PPI provides bindings for popular PHP routers which you will see examples of below. Review the documentation for each router to learn more about using them.

Using Symfony Router
--------------------

.. code-block:: php

    <?php
    // Module.php
    class Module extends AbstractModule
    {
        // ....
        public function getRoutes()
        {
            return $this->loadSymfonyRoutes(__DIR__ . '/routes/symfonyroutes.yml');
        }
    }

.. code-block:: yaml

    # resources/config/symfonyroutes.yml
    BlogModule_Index:
        pattern:  /blog
        defaults: { _controller: "BlogModule:Blog:index"}

Using Aura Router
-----------------

.. code-block:: php

    <?php
    // Module.php
    class Module extends AbstractModule
    {
        // ....
        public function getRoutes()
        {
            return $this->loadAuraRoutes(__DIR__ . '/resources/config/auraroutes.php');
        }
    }

.. code-block:: php

    <?php
    // resources/config/auraroutes.php
    $router
        ->add('BlogModule_Index', '/blog')
        ->addValues(array(
            'controller' => 'BlogModule\Controller\Index',
            'action' => 'indexAction'
        ));

    // add a named route using symfony controller name syntax
    $router->add('BlogModule_View', '/blog/view/{id}')
        ->addTokens(array(
            'id' => '\d+'
        ))
        ->addValues(array(
            'controller' => 'BlogModule:Index:view'
        ));

    return $router;

Using FastRoute Router
----------------------

.. code-block:: php

    <?php
    // Module.php
    class Module extends AbstractModule
    {
        public function getRoutes()
        {
            return $this->loadFastRouteRoutes(__DIR__ . '/resources/routes/fastroutes.php');
        }
    }


.. code-block:: php

    <?php
    // resources/config/fastroutes.php
    /**
     * @var \FastRoute\RouteCollector $r
     */
    $r->addRoute('GET', '/blog', 'BlogModule\Controller\Index');

