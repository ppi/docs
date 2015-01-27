Getting Started
===============

Downloading PPI
---------------

You can download the ppi skeletonaapp from the Homepage. If you just want everything in one folder ready to go, you should choose the **"ppi skeletonapp with vendors"** option.

If you are comfortable with using **git** then you can download the **"skeleton app without vendors"** option and run the following commands:

.. code-block:: bash

    curl -s http://getcomposer.org/installer | php
    php composer.phar install
    mkdir app/cache && chmod -R 777 app/cache

Production Apache Configuration
-------------------------------

We take **security** very seriously, so all your app code and configuration is kept hidden away outside of /public/ and is inaccesible via the browser, because of that we need to create a virtual host in order to route all web requests to the /public/ folder and from there your public assets (css/js/images) are loaded normally and the .htaccess rule kicks in to route all non-asset files to /public/index.php.

.. code-block:: bash

    <VirtualHost *:80>
        DocumentRoot /var/www/ppiapplication/public
        ServerName www.myppiwebsite.com
        RewriteEngine On
        ErrorLog /var/log/apache2/error.log
        <Directory "/var/www/ppiapplication/public">
            AllowOverride All
            Options +Indexes +FollowSymLinks
        </Directory>
    </VirtualHost>

System requirements
-------------------

A web server with its rewrite module enabled. (mod_rewrite for apache)

PPI needs **5.3.23** or above as required by zend framework 2.
