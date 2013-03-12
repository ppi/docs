.. index::
   single: Services

Services
========

What is a Service?
------------------

Let's put it simple, a Service is any PHP object that performs some sort of "global" task. It's a generic name used in computer science to describe an object that's created for a specific purpose (e.g. an API Handler). Each service is used throughout your application whenever you need the specific functionality it provides. You don't have to do anything special to make a service; simply write a PHP class with some code that accomplishes a specific task.

.. citations::
    As a rule, a PHP object is a service if it is used globally in your application. A single Mailer service is used globally to send email messages whereas the many Message objects that it delivers are not services. Similarly, a Product object is not a service, but an object that persists Product objects to a database is a service.

Why using Services?
~~~~~~~~~~~~~~~~~~~~

The advantage of thinking about "services" is that you begin to think about separating each piece of functionality in your application into a series of services. Since each service does just one job, you can easily access each service and use its functionality wherever you need it. Each service can also be more easily tested and configured since it's separated from the other functionality in your application. This idea is called service-oriented architecture and is not unique to PPI Framework or even PHP. Structuring your application around a set of independent service classes is a well-known and trusted object-oriented best-practice. These skills are key to being a good developer in almost any language.

Working with Services in PPI
----------------------------

Technically you can put your class file on any folder inside your module, this is because we use PSR0 for class autoloading, but for best practises we put all our file under the /Classes/ folder within a module.

Defining the Service in our Module.php file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To let our PPI app to know about the service, we need to declare it in our Module's Module.php file under the function getServiceConfig(), just as follows:

.. code-block:: php

    namespace ModuleName;

    use PPI\Module\Module as BaseModule;
    use PPI\Autoload;

    class Module extends BaseModule
    {

        // ....

        public function getServiceConfig()
        {
            return array('factories' => array(

                'foursquare.handler' => function($sm) {

                    $handler = new \FourSquareModule\Classes\ApiHandler();
                    $cache   = new \Doctrine\Common\Cache\ApcCache();
                    $config  = $sm->get('config');

                    $handler->setSecret($config['foursquare']['secret']);
                    $handler->setKey($config['foursquare']['key']);
                    $handler->setCache($cache);

                    return $handler;
                }

            ));
        }

    }

Using services in our Controllers
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To use the services in our Controllers, we just need to call the function ->getService('service.name'), take the following code as a reference:

.. code-block:: php

    public function getVenuesAction()
    {

        $lat      = $this->getRouteParam('lat');
        $lng      = $this->getRouteParam('lng');

        // Let's instantiate the service and then use it.
        $handler  = $this->getService('foursquare.handler');
        $venues   = $handler->getVenues($lat, $lng);

        return json_encode($venues);
    }

