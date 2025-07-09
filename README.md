# php-satim
PHP Library to Interact with Satim.dz CIB Payment Gateway

## Installation

You can install the library using Composer. Run the following command in your terminal:

```bash
composer require aljeerz/satim
```

## Usage

#### Initializing the Satim Client
```php
require 'vendor/autoload.php';
use Aljeerz\PhpSatim\SatimClient;

// Initialize the Satim client with your credentials

$testMode = true; // Set to false for production environment

// Create a new instance of the SatimClient
$client = new SatimClient('satim_username', 'satim_password', 'satim_terminal_id', $testMode);

```
#### Registering a New Order
```php
use Aljeerz\PhpSatim\Support\Enums\SatimCurrency;
use Aljeerz\PhpSatim\Support\Enums\SatimLanguage;
use Aljeerz\PhpSatim\Exceptions\SatimException;



// Create a new order using a builder pattern
$orderBuilder = $client->initNewOrder()
    ->withAmount(1000) // Amount in Currency * 100
    ->withOrderNumber('APP01ORD00001') // Unique order ID
    ->withCurrency(SatimCurrency::DZD) // Currency
    ->withLanguage(SatimLanguage::FR) // Language
    ->withReturnUrl('https://example.com/payments/cib') // URL to redirect after payment
    ->withFailUrl('https://example.com/payments/cib/cancel') // URL to redirect if payment fails
    ->withDescription('Satim Payment Test') // Description of the order
    ->withUserDefinedFields([...]);

try {
    $orderRegistrationResponse = $client->registerOrder($orderBuilder);
} catch (SatimException $e){
    // Handle exception
}
```
#### Confirming an Order
```php
use Aljeerz\PhpSatim\Exceptions\SatimException;

// Confirm the order
try {
    $orderConfirmationResponse = $client->confirmOrder("APP01ORD00001");
} catch (SatimException $e){
    // Handle exception
}
```
#### Checking Order Status ( Not in official satim integration documentation )
```php
// Check the status of the order
try {
    $orderStatusResponse = $client->getOrderStatus("APP01ORD00001");
} catch (SatimException $e){
    // Handle exception
}
```
#### Refunding an Order
```php
// Refund the order
try {
    $refundResponse = $client->refundOrder("APP01ORD00001", 1000); // Amount in Currency * 100
} catch (SatimException $e){
    // Handle exception
}
```