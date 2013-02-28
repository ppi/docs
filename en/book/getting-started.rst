Getting Started
===============

Downloading PPI
---------------

You can download the ppi skeletonaapp from the Homepage. If you just want everything in one folder ready to go, you should choose the **"ppi skeletonapp with vendors"** option.

If you are comfortable with using **git** then you can download the **"skeleton app without vendors"** option and run the following commands:

.. code-block:: bash

    curl -s http://getcomposer.org/installer | php
    php composer.phar install
    mkdir -p app/cache && chmod -R 777 app/cache

System requirements
-------------------

A web server with its rewrite module enabled. (mod_rewrite for apache)

PPI needs **5.3.3** or above. The recommended version by symfony is **5.3.10** or above.
