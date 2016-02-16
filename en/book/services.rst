
.. index::
   single: Services

Services
========

Each of your features (modules) wants to be self-containers, isolated and in control of its own destiny. To keep such separation is a good thing (Separation of Responsibility prinicpal). Once you've got that nailed then you want to begin exposing information out of our feature. A popular architectural pattern is Service Oriented Architecture (SOA).

Services in PPI have names that you define. This can be something simple like ``cache`` or more complicated like ``cache.driver`` or it's even somewhat popular to use the Fully Qualified Class Name (FQCN) as the name of the service like this: ``MyService::class``. With that in mind it's just a string and it's up to you what convention you use just make sure it's consistent.

Defining the Service in our Module
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Services are defined by our module in its Module.php class. This is ofcource optional but if you want to begin doing services from your module then the method is ``getServiceConfig``. This will be called on all modules that have it upon ``boot()`` of PPI. Be sure not to do anything expensive here or make network connections as that'll slow down your boot process which should be almost instantaneous and non-blocking.

.. code-block:: php

    <?php
    class Module extends AbstractModule
    {

        public function getServiceConfig()
        {
            return ['factories' => [
                'user.search' => UserSearchFactory::class,
                'user.create' => UserCreateFactory::class,
                'user.import' => function ($sm) {
                   return new UserImportService($sm->get('Doctrine\ORM\EntityManager'));
                }
            ]];
        }

    }

Above you'll see two types of ways to create a service. One is a Factory class and one is an inline factory closure. It's recommended to use a Factory class but each to their own.

Here's a factory class

.. code-block:: php

    <?php
    namespace MyModule\Factory;

    use Zend\ServiceManager\ServiceLocatorInterface;
    use Zend\ServiceManager\FactoryInterface;
    use MyModule\Service\UserSearchService;

    class UserSearchFactory implements FactoryInterface
    {
        public function createService(ServiceLocatorInterface $sm)
        {
            $config = $sm->get('config');
            if(!isset($config['usersearch']['search_key']) {
                throw new \RuntimeException('Missing user search configuration');
            }

            return new UserSearchService(
                $config['usersearch']['search_key'],
                $sm->get('Doctrine\ORM\EntityManager')
            );
        }

    }

Using services in our Controllers
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To use the services in our Controllers, we just need to call ``$this->getService('service.name')``

.. code-block:: php

    <?php
    public function searchUsersAction(Request $request, $lat, $long)
    {
        $userSearchService = $this->getService('user.search');
        $users = $userSearchService->getUsersFromLatLong($lat, $long);

        return $this->render('MyModule:search:searchUsers.twig', compact('users'));
    }

