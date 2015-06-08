
.. index::
   single: Routing

Routing
=======

Routes are the rules that tell the framework what URLs map to what actions of your application.

PPI will boot up all the modules and call the ``getRoutes()`` method on them all. The routes returned by each module will be added into a stack and PPI will match each set of routes in the order that your modules are loaded.


Using Symfony Router
--------------------

In your PPI module you can use the powerful symfony router. The router here is 100% like-for-like as it is in the symfony framework itself. We directly re-use the entire symfony component and thus there's no new knowledge to be learned here.

This also means you can take routes from existing symfony code and apply them here and they will work just fine.

Loading symfony routes
~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    class Module extends AbstractModule
    {
        // ....
        public function getRoutes()
        {
            return $this->loadSymfonyRoutes(__DIR__ . '/routes/symfony.yml');
        }
    }


The yaml routes list
~~~~~~~~~~~~~~~~~~~~

.. code-block:: yaml

    Blog_Index:
        pattern:  /blog
        defaults: { _controller: "BlogModule:Blog:index"}

    Blog_Get_Recent_Comments:
        pattern:  /blog/get_recent_comments
        defaults: { _controller: "BlogModule:Blog:getRecentComments"}

    Blog_Show_Posts:
        pattern: /blog/posts/{pageNum}
        defaults: { _controller: "Application:Blog:posts", pageNum: 1}

    Blog_EditSave:
        pattern: /blog/create
        defaults: { _controller: "Application:Blog:edit"}
        requirements:
            _method: POST

    Blog_Show_Posts:
        pattern: /blog/{lang}/{pageNum}
        defaults: { _controller: "Application:Blog:index", pageNum: 1, lang: en}
        requirements:
            id: \d+
            lang: en|de



Using Aura Router
-----------------



Loading aura routes
~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    class Module extends AbstractModule
    {
        // ....
        public function getRoutes()
        {
            return $this->loadAuraRoutes(__DIR__ . '/resources/routes/aura.php');
        }
    }


The php routes list
~~~~~~~~~~~~~~~~~~~

Looking at the following definitions, you can specify your controller and action using "Path\To\Controller::action" and it will resolve that way.

Alternatively, you can specify the Module:Controller:action syntax, like in symfony, to resolve your controller and action that way.

.. code-block:: php

    <?php
    // add a simple named route without params
    $router->add('Homepage', '/')
        ->addValues(array(
            'controller' => 'Application\Controller\Index::indexAction'
        ));

    // add a named route with an extended specification
    $router->add('blog.read', '/blog/read/{id}{format}')
        ->addTokens(array(
            'id'     => '\d+',
            'format' => '(\.[^/]+)?',
        ))
        ->addValues(array(
            'controller' => 'Application:Index:index',
            'format'     => '.html',
        ));

    // Important to return the router back to PPI, so it can mediate the router over to Aura
    return $router;

