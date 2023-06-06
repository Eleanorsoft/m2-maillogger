# Magento2 Mail Logger

Module logs all Magento2 emails in `var/mail` folder. Each email is saved in a seperate file with timestamp.

**Default email communication must be enabled in order for this module to work properly. You can disabled real email using settings from the module.**

Copy it to your app/code folder and run:
```
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

