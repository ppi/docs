.. index::
   single: Getting started

Getting Started
===============

DataSource
~~~~~~~~~~

If you want to use DataSource, you will need to have PDO installed. Additionally,
you need to have the PDO driver installed for the database server you want
to use.

Web Server Configuration
-------------------------

We take **security** seriously, as a result all your app code and configuration is kept hidden away outside of ``/public/``
and is inaccessible via the browser. Therefore we need to create a virtual host in order to route all web requests
to the ``/public/`` folder and from there your public assets (css/js/images) are loaded normally. The ``.htaccess`` or web server's rewrite rules kick in which route all non-asset files to ``/public/index.php``.

Apache Virtual Host
~~~~~~~~~~~~~~~~~~~

We are now creating an Apache virtual host for the application to make http://skeletonapp.ppi serve
``index.php`` from the ``skeletonapp/public`` directory.

.. code-block:: apache

    <VirtualHost *:80>
        ServerName    skeletonapp.ppi
        DocumentRoot  "/var/www/skeleton/public"
        SetEnv        PPI_ENV dev
        SetEnv        PPI_DEBUG true
    
        <Directory "/var/www/skeleton/public">
            AllowOverride All
            Allow from all
            DirectoryIndex index.php
            Options Indexes FollowSymLinks
        </Directory>
    </VirtualHost>
    
    
Nginx Virtual Host
~~~~~~~~~~~~~~~~~~~

.. code-block:: nginx 

    server {
        listen 80;
        server_name skeletonapp.ppi;
        root /var/www/skeleton/public;
        index index.php;
        
        location / {
            try_files $uri /index.php$is_args$args;
        }
        
        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            fastcgi_param HTTPS off;
        }
    }


You will need to update the ``/etc/hosts`` or ``c:\windows\system32\drivers\etc\hosts`` file so that your system knows
how to resolve ``skeletonapp.ppi``::

    127.0.0.1               skeletonapp.ppi

Restart your web server. The skeletonapp website can now be accessed using http://skeletonapp.ppi. Welcome!

.. image:: ../../_static/skeletonapp-ppi-localhost.png

----

Now that your environment is properly set up move to the :doc:`/book/application` section to see an overview of the
directory structure and learn the basics on how to configure the framework.
