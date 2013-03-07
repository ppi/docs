.. index::
   single: Controllers

Controllers
===========

So what is a controller? A controller is just a PHP class, like any other that you've created before, but the intention of it, is to have a bunch of methods on it called actions. The idea is: each route in your system will execute an action method. Examples of action methods would be your homepage or blog post page. The job of a controller is to perform a bunch of code and respond with some HTTP content to be sent back to the browser. The response could be a HTML page, a JSON array, XML document or to redirect somewhere. Controllers in PPI are ideal for making anything from web services, to web applications, to just simple html-driven websites.

Lets quote something we said in the last chapter's introduction section

Defaults
~~~~~~~~

This is the important part, The syntax is ``Module:Controller:action``. So if you specify Application:Blog:show then this will execute the following class path: ``/modules/Application/Controller/Blog->showAction()``. Notice how the method has a suffix of Action, this is so you can have lots of methods on your controller but only the ones ending in ``Action()`` will be executable from a route.

Example controller
~~~~~~~~~~~~~~~~~~

Review the following route that we'll be matching.

.. code-block:: yaml

    Blog_Show:
        pattern: /blog/{id}
        defaults: { _controller: "Application:Blog:show"}

So lets presume the route is ``/blog/show/{id}``, and look at what your controller would look like. Here is an example blog controller, based on some of the routes provided above.

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function showAction() {

            $blogID = $this->getRouteParam('id');

            $bs = $this->getBlogStorage();

            if(!$bs->existsByID($blogID)) {
                $this->setFlash('error', 'Invalid Blog ID');
                return $this->redirectToRoute('Blog_Index');
            }

            // Get the blog post for this ID
            $blogPost = $bs->getByID($blogID);

            // Render our main blog page, passing in our $blogPost article to be rendered
            $this->render('Application:blog:show.html.php', compact('blogPost'));
        }

    }

Generating urls using routes
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Here we are still executing the same route, but making up some urls using route names

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function showAction() {

            $blogID = $this->getRouteParam('id');

            // pattern: /about
            $aboutUrl = $this->generateUrl('About_Page');

            // pattern: /blog/show/{id}
            $blogPostUrl = $this->generateUrl('Blog_Post', array('id' => $blogID);

        }
    }

Redirecting to routes
~~~~~~~~~~~~~~~~~~~~~

An extremely handy way to send your users around your application is redirect them to a specific route.

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function showAction() {

            // Send user to /login, if they are not logged in
            if(!$this->isLoggedIn()) {
                return $this->redirectToRoute('User_Login');
            }

            // go to /user/profile/{username}
            return $this->redirectToRoute('User_Profile', array('username' => 'ppi_user'));

        }
    }

Working with ``POST`` values
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function postAction() {

            $this->getPost()->set('myKey', 'myValue');

            var_dump($this->getPost()->get('myKey')); // string('myValue')

            var_dump($this->getPost()->has('myKey')); // bool(true)

            var_dump($this->getPost()->remove('myKey'));
            var_dump($this->getPost()->has('myKey')); // bool(false)

            // To get all the post values
            $postValues = $this->post();

        }
    }

Working with QueryString parameters
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {


        // The URL is /blog/?action=show&id=453221
        public function queryStringAction() {

            var_dump($this->getQueryString()->get('action')); // string('show')
            var_dump($this->getQueryString()->has('id')); // bool(true)

            // Get all the query string values
            $allValues = $this->queryString();

        }
    }

Working with server variables
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function serverAction() {

            $this->getServer()->set('myKey', 'myValue');

            var_dump($this->getServer()->get('myKey')); // string('myValue')

            var_dump($this->getServer()->has('myKey')); // bool(true)

            var_dump($this->getServer()->remove('myKey'));
            var_dump($this->getServer()->has('myKey')); // bool(false)

            // Get all server values
            $allServerValues =  $this->server();

        }
    }

Working with cookies
~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function cookieAction() {

            $this->getCookie()->set('myKey', 'myValue');

            var_dump($this->getCookie()->get('myKey')); // string('myValue')

            var_dump($this->getCookie()->has('myKey')); // bool(true)

            var_dump($this->getCookie()->remove('myKey'));
            var_dump($this->getCookie()->has('myKey')); // bool(false)

            // Get all the cookies
            $cookies = $this->cookies();

        }
    }

Working with session values
~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function sessionAction() {

            $this->getSession()->set('myKey', 'myValue');

            var_dump($this->getSession()->get('myKey')); // string('myValue')

            var_dump($this->getSession()->has('myKey')); // bool(true)

            var_dump($this->getSession()->remove('myKey'));
            var_dump($this->getSession()->has('myKey')); // bool(false)

            // Get all the session values
            $allSessionValues = $this->session();

        }
    }

Working with the config
~~~~~~~~~~~~~~~~~~~~~~~

Using the ``getConfig()`` method we can obtain the config array. This config array is the result of ALL the configs returned from all the modules, merged with your application's global config.

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function configAction() {

            $config = $this->getConfig();

            switch($config['mailer']) {

                case 'swift':
                    break;

                case 'sendgrid':
                    break;

                case 'mailchimp':
                    break;

            }
        }
    }

Working with the is() method
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The ``is()`` method is a very expressive way of coding and has a variety of options you can send to it. The method always returns a boolean as you are saying "is this the case?"

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function isAction() {

            if($this->is('ajax')) {}

            if($this->is('post') {}
            if($this->is('patch') {}
            if($this->is('put') {}
            if($this->is('delete') {}

            // ssl, https, secure: are all the same thing
            if($this->is('ssl') {}
            if($this->is('https') {}
            if($this->is('secure') {}

        }
    }

Getting the users IP or UserAgent
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Getting the user's IP address or user agent is very trivial.

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function userAction() {

            $userIP = $this->getIP();
            $userAgent = $this->getUserAgent();
        }
    }

Working with flash messages
~~~~~~~~~~~~~~~~~~~~~~~~~~~

A flash message is a notification that the user will see on the next page that is rendered. It's basically a setting stored in the session so when the user hits the next designated page it will display the message, and then disappear from the session. Flash messages in PPI have different types. These types can be ``'error'``, ``'warning'``, ``'success'``, this will determine the color or styling applied to it. For a success message you'll see a positive green message and for an error you'll see a negative red message.

Review the following action, it is used to delete a blog item and you'll see a different flash message depending on the scenario.

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function deleteAction() {

            $blogID = $this->getPost()->get('blogID');

            if(empty($blogID)) {
                $this->setFlash('error', 'Invalid BlogID Specified');
                return $this->redirectToRoute('Blog_Index');
            }

            $bs = $this->getBlogStorage();

            if(!$bs->existsByID($blogID)) {
                $this->setFlash('error', 'This blog ID does not exist');
                return $this->redirectToRoute('Blog_Index');
            }

            $bs->deleteByID($blogID);
            $this->setFlash('success', 'Your blog post has been deleted');
            return $this->redirectToRoute('Blog_Index');
        }
    }

Getting the current environment
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You may want to perform different scenarios based on the site's environment. This is a configuration value defined in your global application config. The ``getEnv()`` method is how it's obtained.

.. code-block:: php

    <?php
    namespace Application\Controller;

    use Application\Controller\Shared as BaseController;

    class Blog extends BaseController {

        public function envAction() {

            $env = $this->getEnv();
            switch($env) {
                case 'development':
                    break;

                case 'staging':
                    break;

                case 'production':
                default:
                    break;

            }
        }
    }
