
.. index::
   single: Templating

Templating
==========

As discovered in the previous chapter, a controller's job is to process each HTTP request that hits your web application. Once your controller has finished its processing it usually wants to generate some output content. To achieve this it hands over responsibility to the templating engine. The templating engine will load up the template file you tell it to, and then generate the output you want, his can be in the form of a redirect, HTML webpage output, XML, CSV, JSON; you get the picture!

**In this chapter you'll learn:**

* How to create a base template
* How to load templates from your controller
* How to pass data into templates
* How to extend a parent template
* How to use template helpers

Base Templates
--------------

**What are base templates?**

Why do we need base templates? well you don't want to have to repeat HTML over and over again and perform repetitive steps for every different type of page you have. There's usually some commonalities between the templates and this commonality is your base template. The part that's usually different is the content page of your webpage, such as a users profile or a blog post.

So lets see an example of what we call a base template, or somethings referred to as a master template. This is all the HTML structure of your webpage including headers and footers, and the part that'll change will be everything inside the page-content section.

**Where are they stored?**

Base templates are stored in the ``./app/views/`` directory. You can have as many base templates as you like in there.

This file is ``./app/views/base.html.php``

**Example base template:**

.. code-block:: html+php

    <!DOCTYPE html>
    <html>
        <head>
            <title>Welcome to Symfony!</title>
        </head>
        <body>
            <div id="header">...</div>
            <div id="page-content">
                <?php $view['slots']->output('_content'); ?>
            </div>
            <div id="footer">...</div>
        </body>
    </html>

Lets recap a little, you see that slots helper outputting something called _content? Well this is actually injecting the resulting output of the CHILD template belonging to this base template. Yes that means we have child templates that extend parent/base templates. This is where things get interesting! Keep on reading.

Extending Base Templates
------------------------

On our first line we extend the base template we want. You can extend literally any template you like by specifying its ``Module:folder:file.format.engine`` naming syntax. If you miss out the Module and folder sections, such as ``::base.html.php`` then it's going to take the global route of ``./app/views/``.

.. code-block:: html+php

    <?php $view->extend('::base.html.php'); ?>
    <div class="user-registration-page">
        <h1>Register for our site</h1>
        <form>...</form>
    </div>


The resulting output
--------------------

If you remember that the extend call is really just populating a slots section named _content then the injected content into the parent template looks like this.

.. code-block:: html+php

    <!DOCTYPE html>
    <html>
        <head>
            <title>Welcome to Symfony!</title>
        </head>
        <body>
            <div id="header">...</div>
            <div id="page-content">

                <div class="user-registration-page">
                    <h1>Register for our site</h1>
                    <form>...</form>
                </div>

            </div>
            <div id="footer">...</div>
        </body>
    </html>


Example scenario
----------------

Consider the following scenario. We have the route ``Blog_Show`` which executes the action ``Application:Blog:show``. We then load up a template named ``Application:blog:show.html.php`` which is designed to show the user their blog post.

The route
~~~~~~~~~

.. code-block:: yaml

    Blog_Show:
        pattern: /blog/{id}
        defaults: { _controller: "Application:Blog:show"}


The controller
~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function showAction() {

            $blogID = $this->getRouteParam('id');
            $bs     = $this->getBlogStorage();

            if(!$bs->existsByID($blogID)) {
                $this->setFlash('error', 'Invalid Blog ID');
                return $this->redirectToRoute('Blog_Index');
            }

            // Get the blog post for this ID
            $blogPost = $bs->getByID($blogID);

            // Render our blog post page, passing in our $blogPost article to be rendered
            $this->render('Application:blog:show.html.php', compact('blogPost'));
        }
    }


The template
~~~~~~~~~~~~

So the name of the template loaded is Application:blog:show.html.php then this is going to translate to ``./modules/Application/blog/show.html.php``. We also passed in a ``$blogPost`` variable which can be used locally within the template that you'll see below.

.. code-block:: html+php

    <?php $view->extend('::base.html.php'); ?>

    <div class="blog-post-page">
        <h1><?=$blogPost->getTitle();?></h1>
        <p class="created-by"><?=$blogPost->getCreatedBy();?></p>
        <p class="content"><?=$blogPost->getContent();?></p>
    </div>


Using the slots helper
----------------------

We have a bunch of template helpers available to you, the helpers are stored in the $view variable, such as ``$view['slots']`` or ``$view['assets']``. So what is the purpose of using slots? Well they're really for segmenting the templates up into named sections and this allows the child templates to specify content that the parent is going to inject for them.

Review this example it shows a few examples of using the slots helper for various different reasons.

The base template
~~~~~~~~~~~~~~~~~

.. code-block:: html+php

    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?php $view['slots']->output('title', 'PPI Skeleton Application') ?></title>
        </head>
        <body>
            <div id="page-content">
                <?php $view['slots']->output('_content') ?>
            </div>
        </body>
    </html>

The child template
~~~~~~~~~~~~~~~~~~

.. code-block:: html+php

    <?php $view->extend('::base.html.php'); ?>

    <div class="blog-post-page">
        <h1><?=$blogPost->getTitle();?></h1>
        <p class="created-by"><?=$blogPost->getCreatedBy();?></p>
        <p class="content"><?=$blogPost->getContent();?></p>
    </div>

    <?php $view['slots']->start('title'); ?>
    Welcome to the blog page
    <?php $view['slots']->stop(); ?>


**What's going on?**

The slots key we specified first was title and we gave the output method a second parameter, this means when the child template does not specify a slot section named title then it will default to "PPI Skeleton Application".

Using the assets helper
------------------------

So why do we need an assets helper? Well one main purpose for it is to include asset files from your project's ``./public/`` folder such as images, css files, javascript files. This is useful because we're never hard-coding any baseurl's anywhere so it will work on any environment you host it on.

Review this example it shows a few examples of using the slots helper for various different reasons such as including CSS and JS files.

.. code-block:: html+php

    <?php $view->extend('::base.html.php'); ?>

    <div class="blog-post-page">

        <h1><?=$blogPost->getTitle();?></h1>

        <img src="<?=$view['assets']->getUrl('images/blog.png');?>" alt="The Blog Image">

        <p class="created-by"><?=$blogPost->getCreatedBy();?></p>
        <p class="content"><?=$blogPost->getContent();?></p>

        <?php $view['slots']->start('include_js'); ?>
        <script type="text/javascript" src="<?=$view['assets']->getUrl('js/blog.js');?>"></script>
        <?php $view['slots']->stop(); ?>

        <?php $view['slots']->start('include_css'); ?>
        <link href="<?=$view['assets']->getUrl('css/blog.css');?>" rel="stylesheet">
        <?php $view['slots']->stop(); ?>

    </div>


**What's going on?**

By asking for ``images/blog.png`` we're basically asking for ``www.mysite.com/images/blog.png``, pretty straight forward right? Our ``include_css`` and ``include_js`` slots blocks are custom HTML that's loading up CSS/JS files just for this particular page load. This is great because you can split your application up onto smaller CSS/JS files and only load the required assets for your particular page, rather than having to bundle all your CSS into the one file.

Using the router helper
-----------------------

What is a router helper? The router help is a nice PHP class with routing related methods on it that you can use while you're building PHP templates for your application.

What's it useful for? The most common use for this is to perform a technique commonly known as reverse routing. Basically this is the process of taking a route key and turning that into a URL, rather than the standard process of having a URL and that translate into a route to become dispatched.

Why is reverse routing needed? Lets take the Blog_Show route we made earlier in the routing section. The syntax of that URI would be like: ``/blog/show/{title}``, so rather than having numerous HTML links all manually referring to ``/blog/show/my-title`` we always refer to its route key instead, that way if we ever want to change the URI to something like ``/blog/post/{title}`` the templating layer of your application won't care because that change has been centrally maintained in your module's routes file.

Here are some examples of reverse routing using the routes helper

.. code-block:: html+php

    <a href="<?=$view['router']->generate('About_Page');?>">About Page</a>

    <p>User List</p>
    <ul>
    <?php foreach($users as $user): ?>
        <li><a href="<?=$view['router']->generate('User_Profile', array('id' => $user->getID())); ?>"><?=$view->escape($user->getName());?></a></li>
    <?php endforeach; ?>
    </ul>

The output would be something like this

.. code-block:: html+php

    <a href="/about">About Page</a>

    <p>User List</p>
    <ul>
        <li><a href="/user/profile?id=23">PPI User</a></li>
        <li><a href="/user/profile?id=87675">Another PPI User</a></li>
    </ul>

