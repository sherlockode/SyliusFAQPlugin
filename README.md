# Sherlockode SyliusFaqPlugin

----

[ ![](https://img.shields.io/packagist/l/sherlockode/sylius-faq-plugin)](https://packagist.org/packages/sherlockode/sylius-faq-plugin "License")
[ ![](https://img.shields.io/packagist/v/sherlockode/sylius-faq-plugin)](https://packagist.org/packages/sherlockode/sylius-faq-plugin "Version")
[ ![](https://poser.pugx.org/sherlockode/sylius-faq-plugin/downloads)](https://packagist.org/packages/sherlockode/sylius-faq-plugin "Total Downloads")
[ ![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://www.sherlockode.fr/contactez-nous/?utm_source=github&utm_medium=referral&utm_campaign=plugins_faq)


## Table of Content

***

* [Overview](#overview)
* [Installation](#installation)
    * [Usage](#usage)
* [Demo](#demo-sylius-shop)
* [Additional resources for developers](#additional-resources-for-developers)
* [License](#license)
* [Contact](#contact)

# Overview

----
This plugin allows admin to manage the FAQ section in multiple languages.

----

# Installation

----
Install the plugin with composer:

```bash
$ composer require sherlockode/sylius-faq-plugin
```

Complete the configuration:

```yaml
# config/packages/sherlockode_sylius_faq.yaml

imports:
    - { resource: "@SherlockodeSyliusFAQPlugin/Resources/config/config.yaml" }
```

Import routing:

```yaml
# config/routes/sylius_faq.yaml

sherlockode_sylius_faq_plugin_admin:
    resource: "@SherlockodeSyliusFAQPlugin/Resources/config/admin_routing.yaml"
    prefix: '/%sylius_admin.path_name%/faq'

sherlockode_sylius_faq_plugin:
    resource: "@SherlockodeSyliusFAQPlugin/Resources/config/routing.yaml"
```

Don't forget to generate a migration

Publish the assets to finish the installation

```bash
php bin/console assets:install public
```

----

## Usage

Now you only have to create categories and for each categories, you can have multiple questions / answers.
If certain categories and questions are only displayed for certain languages, leave the other languages blank

----

# Demo Sylius Shop

---

We created a demo app with some useful use-cases of plugins!
Visit [sylius-demo.sherlockode.fr](https://sylius-demo.sherlockode.fr/) to take a look at it. The admin can be accessed under
[sylius-demo.sherlockode.fr/admin/login](https://sylius-demo.sherlockode.fr/admin/login) link.
Plugins that we have used in the demo:

| Plugin name                  | GitHub                                                     | Sylius' Store |
|------------------------------|------------------------------------------------------------|---------------|
| Advance Content Bundle (ACB) | https://github.com/sherlockode/SyliusAdvancedContentPlugin | -             |
| Mondial Relay                | https://github.com/sherlockode/SyliusMondialRelayPlugin    | -             |
| Checkout Plugin              | https://github.com/sherlockode/SyliusCheckoutPlugin        | -             |
| FAQ                          | https://github.com/sherlockode/SyliusFAQPlugin             | -             |

## Additional resources for developers

---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)

## License

---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

## Contact

---
If you want to contact us, the best way is to fill the form on [our website](https://www.sherlockode.fr/contactez-nous/?utm_source=github&utm_medium=referral&utm_campaign=plugins_faq) or send us an e-mail to contact@sherlockode.fr with your question(s). We guarantee that we answer as soon as we can!
