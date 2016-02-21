
.. index::
single: Services

Commands
========

The PPI commands layer is powered by Symfony2 directly with no change in behaviour. You can also just drop in any Symfony2 command and it will be executable for you.

In PPI console there's a very thin layer we've added on top top of it to allow your commands to talk to the rest of your app and its services in the context of PPI, so be conscious of this when considering the portability of your console commands between technologies that support symfony2 commands.

Making commands for your modules
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Commands get auto-magically registered up by the PPI ``boot()`` process, by placing your files in a ``Command`` directory at the root of where your module's namespace is registered, thus so there's no need to open up your ``app/console`` file and add them in there manually.
If you place a Command class into a ``Command`` directory of your module's source code then it will be registered. All command classes need the ``Command.php`` suffix.

Make your command class

.. code-block:: bash

    mkdir modules/MyModule/src/Command
    touch modules/MyModule/src/Command/ImportUsersCommand.php

.. code-block:: php

    <?php
    namespace MyModule\Command;

    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use PPI\Framework\Console\Command\AbstractCommand;

    class ImportUsersCommand extends AbstractCommand
    {
        protected function configure()
        {
            $this
                ->setName('import:users:csv')
                ->setDescription('Imports Users From a CSV file');
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            // Import Users Code Here
        }
    }

Verifying your commands have been registered
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

In order to verify that your command is successfully being registered, then run app/console and you should see your command appear just like below.

.. code-block:: bash

    $ app/console

    Available commands:
      help                   Displays help for a command
      list                   Lists commands
     import
      import:users:csv       Imports Users From a CSV file



Accessing application parameters
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheDir = $this->getServiceManager()->getParameter('app.cache_dir');
    }

Accessing services
~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userImportService = $this->getServiceManager()->getService('user.import.service');
        $userImportService->doImport();
    }

Accessing the application
~~~~~~~~~~~~~~~~~~~~~~~~~
The app is just a service named ``app`` and you can access it like you would at any other part of the system

.. code-block:: php

    <?php
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getServiceManager()->get('app');
        $env = $app->getEnvironment();
    }

Accessing Configuration
~~~~~~~~~~~~~~~~~~~~~~~

Configuration is actually just a service named ``config`` so you access it like you would from any other part of the system.

.. code-block:: php

    <?php
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getServiceManager()->get('config')
        $userConfig = $config['user'];
    }

