.. index::
   single: Routing

Routing
=======

Routes are the rules that tell the framework what URLs map to what area of your application. The routing here is simple and expressive. We are using the Symfony2 routing component here, this means if you're a Symfony2 developer you already know what you're doing. If you don't know Symfony2 already, then learning the routes here will allow you to read routes from existing Symfony2 bundles out there in the wild. It's really a win/win situation.

Routes are an integral part of web application development. They make way for nice clean urls such as ``/blog/view/5543`` instead of something like ``/blog.php?Action=view&article=5543``.

By reading this routing section you'll be able to:

* Create beautiful clean routes
* Create routes that take in different parameters
* Specify complex requirements for your parameters
* Generate URLs within your controllers
* Redirect to routes within your controllers

The Details
-----------

Lets talk about the structure of a route, you have a route name, pattern, defaults and requirements.

Name
~~~~~

This is a symbolic name to easily refer to this actual from different contexts in your application. Examples of route names are ``Homepage``, ``Blog_View``, ``Profile_Edit``. These are extremely useful if you want to just redirect a user to a page like the login page, you can redirect them to User_Login. If you are in a template file and want to generate a link you can refer to the route name and it will be created for you. The good part about this is you can maintain the routes via your ``routes.yml`` file and your entire system updates.

Pattern
~~~~~~~

This is the URI pattern that if present will activate your route. In this example we're targeting the homepage. This is where you can specify params like ``{id}`` or ``{username}``. You can make URLs like ``/article/{id}`` or ``/profile/{username}``.

Defaults
~~~~~~~~

This is the important part, The syntax is ``Module:Controller:action``. So if you specify ``Application:Blog:show`` then this will execute the following class path: ``/modules/Application/Controller/Blog->showAction()``. Notice how the method has a suffix of Action, this is so you can have lots of methods on your controller but only the ones ending in ``Action()`` will be executable from a route.

Requirements
~~~~~~~~~~~~

This is where you can specify things like the request method being POST or PUT. You can also specify rules for the parameters you created above in the pattern section. Such as ``{id}`` being numeric, or ``{lang}`` being in a whitelist of values such as ``en|de|pt``.

With all this knowledge in mind, take a look at all the different examples of routes below and come back up here for reference.

Basic Routes
------------

.. code-block:: yaml

    Homepage:
    pattern: /
    defaults: { _controller: "Application:Index:index"}

    Blog_Index:
    pattern: /blog
    defaults: { _controller: "Application:Blog:index"}


Routes with parameters
-----------------------

The following example is basically ``/blog/*`` where the wildcard is the value given to title. If the URL was ``/blog/using-ppi2`` then the title variable gets the value ``using-ppi2``, which you can see being used in the Example Controller section below.

.. code-block:: yaml

    Blog_Show:
    pattern: /blog/{title}
    defaults: { _controller: "Application:Blog:show"}


This example optionally looks for the ``{pageNum}`` parameter, if it's not found it defaults to ``1``.

.. code-block:: yaml

    Blog_Show:
    pattern: /blog/{pageNum}
    defaults: { _controller: "Application:Blog:index", pageNum: 1}


Routes with requirements
------------------------

Only form submits using ``POST`` will trigger this route. This means you dont have to check this kind of stuff in your controller.

.. code-block:: yaml

    Blog_EditSave:
    pattern: /blog/edit/{id}
    defaults: { _controller: "Application:Blog:edit"}
    requirements:
        _method: POST


Checking if the ``{pageNum}`` parameter is numerical. Checking if the ``{lang}`` parameter is ``en`` or ``de``.

.. code-block:: yaml

    Blog_Show:
    pattern: /blog/{lang}/{pageNum}
    defaults: { _controller: "Application:Blog:index", pageNum: 1, lang: en}
    requirements:
        id: \d+
        lang: en|de


Checking if the page is a ``POST`` request, and that ``{id}`` is numerical.

.. code-block:: yaml

    Blog_EditSave:
    pattern: /blog/edit/{id}
    defaults: { _controller: "Application:Blog:edit"}
    requirements:
        _method: POST
        id: \d+
