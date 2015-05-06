.. index::
   pair: Monolog; Configuration reference

Monolog Configuration Reference
===============================

Monolog_ is a logging library for PHP 5.3 used by PPI. It is inspired by the Python LogBook library.

.. configuration-block::

    .. code-block:: yaml

        monolog:
            handlers:

                # Examples:
                syslog:
                    type:                stream
                    path:                /var/log/symfony.log
                    level:               ERROR
                    bubble:              false
                    formatter:           my_formatter
                    processors:
                        - some_callable
                main:
                    type:                fingers_crossed
                    action_level:        WARNING
                    buffer_size:         30
                    handler:             custom
                custom:
                    type:                service
                    id:                  my_handler

                # Default options and values for some "my_custom_handler"
                my_custom_handler:
                    type:                 ~ # Required
                    id:                   ~
                    priority:             0
                    level:                DEBUG
                    bubble:               true
                    path:                 "%app.logs_dir%/%app.environment%.log"
                    ident:                false
                    facility:             user
                    max_files:            0
                    action_level:         WARNING
                    activation_strategy:  ~
                    stop_buffering:       true
                    buffer_size:          0
                    handler:              ~
                    members:              []
                    channels:
                        type:     ~
                        elements: ~
                    from_email:           ~
                    to_email:             ~
                    subject:              ~
                    email_prototype:
                        id:                   ~ # Required (when the email_prototype is used)
                        factory-method:       ~
                    channels:
                        type:                 ~
                        elements:             []
                    formatter:            ~

.. _Monolog: https://github.com/Seldaek/monolog