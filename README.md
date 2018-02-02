# Summary
Use apache `ab` command to evaluate php framework performance

# Usage

- install php framework

    ```
    composer global require "laravel/installer"
    
    composer create-project --prefer-dist laravel/laravel Laravel5.5
    
    composer global require "laravel/lumen-installer"
        
    composer create-project --prefer-dist laravel/lumen Lumen5.5
    
    // see https://github.com/phalcon/phalcon-devtools
    
    phalcon project Phalcon3.2.4
        
    phalcon project PhalconMicro3.2.4 --type=micro
    
    // ... more php framework
    
    ```

- set `http://php.test.com` to the project root directory

- set `php framework` url to the framework root directory

- modify config.php