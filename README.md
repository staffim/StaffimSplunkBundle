# SplunkBundle

## About

[SplunkStorm](http://splunkstorm.com/) handler for [Monolog](https://github.com/Seldaek/monolog) as a Symfony bundle.

The bundle is inspired from [LogglyBundle](https://github.com/beberlei/WhitewashingLogglyBundle)

Note: The bundle did not testing with [Splunk](http://splunk.com)

## Installation

Require the `staffim/splunk-bundle` package in your composer.json and update your dependencies.

    $ composer require staffim/splunk-bundle:*

Add the StaffimSplunkBundle to your application's kernel:

```php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Staffim\SplunkBundle\StaffimSplunkBundle(),
            ...
        );
        ...
    }
```

## Configuration

### Configure Monolog
```yml
monolog:
    handlers:
        main:
            type:         fingers_crossed
            action_level: error
            handler:      splunk
        splunk:
            type: service
            id: staffim_splunk.monolog_handler
```

or buffered handler

```yml
monolog:
    handlers:
        buffered_splunk:
            type: buffer
            level: debug
            handler: splunk
        splunk:
            type: service
            id: staffim_splunk.monolog_handler
```

or even error handler

```yml
services:
    my.monolog.exception_logger:
        public:    false
        class:     Symfony\Bridge\Monolog\Logger
        arguments: ["mole.monolog.exception_logger"]
        calls:
            - [pushHandler, ["@staffim_splunk.monolog_handler"]]
```

### Configure Splunk:

```yml
staffim_splunk:
    # SplunkStorm access token
    token: ###
    # SplunkStorm project ID
    project: ###
    # SplunkStorm API host ((defaults to api.splunkstorm.com))
    host: api.splunkstorm.com
    # Level to be logged (defaults to DEBUG)
    level: DEBUG
    bubble: true
```
