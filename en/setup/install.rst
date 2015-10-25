Installing the environment
==========================

Automatic Vagrant Installation
------------------------------

The recommended install procedure is to use the pre-built vagrant image that ships with the skeleton app in the ``ansible`` directory.

Installing vagrant and ansible
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Before you can run vagrant you'll need to install a few system dependencies.

Install vagrant https://docs.vagrantup.com/v2/installation/

Install ansible: http://docs.ansible.com/ansible/intro_installation.html#latest-releases-via-pip

Running vagrant
~~~~~~~~~~~~~~~

Running the vagrant image - it's that easy!

.. code-block:: bash

    vagrant up

Manual Web Server Configuration
-------------------------------

Security is crucial to consider. As a result all your app code and configuration is kept hidden away outside of ``/public/``
and is inaccessible via the browser. Therefore we need to create a virtual host in order to route all web requests
to the ``/public/`` folder and from there your public assets (css/js/images) are loaded normally. The ``.htaccess`` or web server's rewrite rules kick in which route all non-asset files to ``/public/index.php``.

Accessing the application
~~~~~~~~~~~~~~~~~~~~~~~~~

If you wish to use the skeletonapp as a hostname, run this command and browse to `http://skeletonapp.ppi`

.. code-block:: bash

    sudo sh -c 'echo "192.168.33.99 skeletonapp.ppi" >> /etc/hosts’


Otherwise you can browse straight to the ip address of: `http://192.168.33.99`

Apache Configuration
~~~~~~~~~~~~~~~~~~~~

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

            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^ index.php [L]

        </Directory>
    </VirtualHost>


Nginx Virtual Host
~~~~~~~~~~~~~~~~~~

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


Restart your web server. The skeletonapp website can now be accessed using http://skeletonapp.ppi


