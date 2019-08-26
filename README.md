# Magento 2 Webhooks Module for fomo.com

![Preview](https://github.com/usefomo/magento-2/blob/master/.github/admin.png?raw=true)

This plugin provides webhooks for Magento 2 events, to be consumed by fomo.com.

> Tested and working on v2.1, v2.2, and v2.3.

## Getting Started

Upload to folder (you may need to create subfolders)
```
app/code/Fomo/Webhook
```

Add `Fomo_Webhook` to your `app/etc/config.php`
```php
<?php
return array (
  'modules' => 
  array (
    //
    // Bunch of other modules
    // 
    'Fomo_Webhook' => 1,
  ),
);
```

Run database migrations
```
php bin/magento setup:upgrade
```

## Supported Webhooks

Available now
- Order created

## Payload

- first_name
- last_name
- email
- city
- province
- country
- subtotal
- grandtotal
- payment_method
- product["id"]
- product["price"]
- product["type"]
- product["qty"]
- product["price"]


## Contributing

Submit a pull request!
