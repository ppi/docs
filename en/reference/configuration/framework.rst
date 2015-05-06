.. index::
   single: Configuration reference; Framework

Framework Configuration ("framework")
===========================================

This reference document is a work in progress. It should be accurate, but
all options are not yet fully covered.

The core of the PPI Framework can be configured under the ``framework`` key in your application configuration.
This includes settings related to sessions, translation, routing and more.

Configuration
-------------

* `session`_
    * `cookie_lifetime`_
    * `cookie_path`_
    * `cookie_domain`_
    * `cookie_secure`_
    * `cookie_httponly`_
    * `gc_divisor`_
    * `gc_probability`_
    * `gc_maxlifetime`_
    * `save_path`_
* `templating`_
    * `assets_base_urls`_
    * `assets_version`_
    * `assets_version_format`_

session
~~~~~~~

cookie_lifetime
...............

**type**: ``integer`` **default**: ``0``

This determines the lifetime of the session - in seconds. By default it will use
``0``, which means the cookie is valid for the length of the browser session.

cookie_path
...........

**type**: ``string`` **default**: ``/``

This determines the path to set in the session cookie. By default it will use ``/``.

cookie_domain
.............

**type**: ``string`` **default**: ``''``

This determines the domain to set in the session cookie. By default it's blank,
meaning the host name of the server which generated the cookie according
to the cookie specification.

cookie_secure
.............

**type**: ``Boolean`` **default**: ``false``

This determines whether cookies should only be sent over secure connections.

cookie_httponly
...............

**type**: ``Boolean`` **default**: ``false``

This determines whether cookies should only accessible through the HTTP protocol.
This means that the cookie won't be accessible by scripting languages, such
as JavaScript. This setting can effectively help to reduce identity theft
through XSS attacks.

gc_probability
..............

.. versionadded:: 2.1
    The ``gc_probability`` option is new in version 2.1

**type**: ``integer`` **default**: ``1``

This defines the probability that the garbage collector (GC) process is started
on every session initialization. The probability is calculated by using
``gc_probability`` / ``gc_divisor``, e.g. 1/100 means there is a 1% chance
that the GC process will start on each request.

gc_divisor
..........

.. versionadded:: 2.1
    The ``gc_divisor`` option is new in version 2.1

**type**: ``integer`` **default**: ``100``

See `gc_probability`_.

gc_maxlifetime
..............

.. versionadded:: 2.1
    The ``gc_maxlifetime`` option is new in version 2.1

**type**: ``integer`` **default**: ``14400``

This determines the number of seconds after which data will be seen as "garbage"
and potentially cleaned up. Garbage collection may occur during session start
and depends on `gc_divisor`_ and `gc_probability`_.

save_path
.........

**type**: ``string`` **default**: ``%app.cache.dir%/sessions``

This determines the argument to be passed to the save handler. If you choose
the default file handler, this is the path where the files are created. You can
also set this value to the ``save_path`` of your ``php.ini`` by setting the
value to ``null``:

.. configuration-block::

    .. code-block:: yaml

        # app/config/app.yml
        framework:
            session:
                save_path: null

    .. code-block:: php

        // app/config/app.php
        return array(
        'framework' => array(
            'session' => array(
                'save_path' => null,
            ),
        ));

.. _configuration-framework-serializer:

templating
~~~~~~~~~~

assets_base_urls
................

**default**: ``{ http: [], ssl: [] }``

This option allows you to define base URLs to be used for assets referenced
from ``http`` and ``ssl`` (``https``) pages. A string value may be provided in
lieu of a single-element array. If multiple base URLs are provided, PPI2
will select one from the collection each time it generates an asset's path.

For your convenience, ``assets_base_urls`` can be set directly with a string or
array of strings, which will be automatically organized into collections of base
URLs for ``http`` and ``https`` requests. If a URL starts with ``https://`` or
is `protocol-relative`_ (i.e. starts with `//`) it will be added to both
collections. URLs starting with ``http://`` will only be added to the
``http`` collection.

.. versionadded:: 2.1
    Unlike most configuration blocks, successive values for ``assets_base_urls``
    will overwrite each other instead of being merged. This behavior was chosen
    because developers will typically define base URL's for each environment.
    Given that most projects tend to inherit configurations
    (e.g. ``config_test.yml`` imports ``config_dev.yml``) and/or share a common
    base configuration (i.e. ``app.yml``), merging could yield a set of base
    URL's for multiple environments.

.. _ref-framework-assets-version:

assets_version
..............

**type**: ``string``

This option is used to *bust* the cache on assets by globally adding a query
parameter to all rendered asset paths (e.g. ``/images/logo.png?v2``). This
applies only to assets rendered via the Twig ``asset`` function (or PHP equivalent)
as well as assets rendered with Assetic.

For example, suppose you have the following:

.. configuration-block::

    .. code-block:: html+jinja

        <img src="{{ asset('images/logo.png') }}" alt="PPI!" />

    .. code-block:: php

        <img src="<?php echo $view['assets']->getUrl('images/logo.png') ?>" alt="PPI!" />

By default, this will render a path to your image such as ``/images/logo.png``.
Now, activate the ``assets_version`` option:

.. configuration-block::

    .. code-block:: yaml

        # app/config/app.yml
        framework:
            # ...
            templating: { engines: ['twig'], assets_version: v2 }

    .. code-block:: php

        // app/config/app.php
        return array(
        'framework' => array(
            ...,
            'templating'      => array(
                'engines'        => array('twig'),
                'assets_version' => 'v2',
            ),
        ));

Now, the same asset will be rendered as ``/images/logo.png?v2`` If you use
this feature, you **must** manually increment the ``assets_version`` value
before each deployment so that the query parameters change.

You can also control how the query string works via the `assets_version_format`_
option.

assets_version_format
.....................

**type**: ``string`` **default**: ``%%s?%%s``

This specifies a :phpfunction:`sprintf` pattern that will be used with the `assets_version`_
option to construct an asset's path. By default, the pattern adds the asset's
version as a query string. For example, if ``assets_version_format`` is set to
``%%s?version=%%s`` and ``assets_version`` is set to ``5``, the asset's path
would be ``/images/logo.png?version=5``.

.. note::

    All percentage signs (``%``) in the format string must be doubled to escape
    the character. Without escaping, values might inadvertently be interpreted
    as a service parameter.

.. tip::

    Some CDN's do not support cache-busting via query strings, so injecting the
    version into the actual file path is necessary. Thankfully, ``assets_version_format``
    is not limited to producing versioned query strings.

    The pattern receives the asset's original path and version as its first and
    second parameters, respectively. Since the asset's path is one parameter, you
    cannot modify it in-place (e.g. ``/images/logo-v5.png``); however, you can
    prefix the asset's path using a pattern of ``version-%%2$s/%%1$s``, which
    would result in the path ``version-5/images/logo.png``.

    URL rewrite rules could then be used to disregard the version prefix before
    serving the asset. Alternatively, you could copy assets to the appropriate
    version path as part of your deployment process and forgo any URL rewriting.
    The latter option is useful if you would like older asset versions to remain
    accessible at their original URL.

Full Default Configuration
--------------------------

.. configuration-block::

    .. code-block:: yaml

        framework:

            # router configuration
            router:
                resource:             ~ # Required
                type:                 ~
                http_port:            80
                https_port:           443

                # set to true to throw an exception when a parameter does not match the requirements
                # set to false to disable exceptions when a parameter does not match the requirements (and return null instead)
                # set to null to disable parameter checks against requirements
                # 'true' is the preferred configuration in development mode, while 'false' or 'null' might be preferred in production
                strict_requirements:  true

            # session configuration
            session:
                storage_id:           session.storage.native
                handler_id:           session.handler.native_file
                name:                 ~
                cookie_lifetime:      ~
                cookie_path:          ~
                cookie_domain:        ~
                cookie_secure:        ~
                cookie_httponly:      ~
                gc_divisor:           ~
                gc_probability:       ~
                gc_maxlifetime:       ~
                save_path:            %app.cache_dir%/sessions

            # templating configuration
            templating:
                assets_version:       ~
                assets_version_format:  %%s?%%s
                assets_base_urls:
                    http:                 []
                    ssl:                  []
                cache:                ~
                engines:              # Required

                    # Example:
                    - twig
                loaders:              []
                packages:

                    # Prototype
                    name:
                        version:              ~
                        version_format:       %%s?%%s
                        base_urls:
                            http:                 []
                            ssl:                  []

            # translator configuration
            translator:
                enabled:              false
                fallback:             en

.. _`protocol-relative`: http://tools.ietf.org/html/rfc3986#section-4.2
