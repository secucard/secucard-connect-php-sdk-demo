# secucard connect PHP SDK Demo


# Requirements 

PHP 5.5.0 and later.  
Composer.


# Setup

Running "composer install" in this dir will create vendor directory with all needed dependencies.


## Demo 01

Shows basic usage of Smart/Transactions product services.
To run execute simple.php.  
     
This product requires use of device authentication.   
To keep things simple in this sample just a refresh token is provided as credential, obtained from a separate device 
authentication process. See the sample for more details.   


## Demo 02 client payments

Shows basic usage of Payment/Secupaydebits and Payment/Secupayprepays product services.
demo has 2 parts:
1. in main directory there are sample command line scripts
2. in the browser directory there is sample html page where you can use forms to create your objects
the sample url would be : ../secucard-connect-php-sdk-demo/02_client_payments/browser/index.php/settings

This product requires use of client credentials authentication.


