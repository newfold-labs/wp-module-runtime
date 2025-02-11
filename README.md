<a href="https://newfold.com/" target="_blank">
    <img src="https://newfold.com/content/experience-fragments/newfold/site-header/master/_jcr_content/root/header/logo.coreimg.svg/1621395071423/newfold-digital.svg" alt="Newfold Logo" title="Newfold Digital" align="right" 
height="42" />
</a>

# WordPress Runtime Module

Runtime for Newfold WP modules and plugins

## Module Responsibilities

* `prepareRuntime` method in `Runtime` class accepts `container` object as parameter and generates a PHP object which contains:
      
        siteUrl, 
        siteTitle, 
        adminUrl, 
        homeUrl, 
        capabilties, 
        Woocommerce plugin active/inactive status, 
        YithBooking plugins active/inactive status(yith-woocommerce-booking-extended / yith-woocommerce-booking-premium / yith-woocommerce-booking), 
        JetpackBoost plugin active/inactive status, 
        WordPress version, 
        currentTheme.
      
* Above PHP object is then converted into a JSON-encoded string and assigned `NewfoldRuntime` JS variable. 
* `NewfoldRuntime` JSON object can be imported in any React components in `newfold-labs` modules as specified in `Usage` section below. 
* Structure of `NewfoldRuntime` JSON object consumed by React components is as below.
* Please note, `redundant values` are kept for backward compatibility and will be removed soon, as part `tech-debt` initiative.

```
  {
    "site": {
        "url": "Your wordpress site url",
        "title": "Your wordpress site name"
    },
    "adminUrl": "Your wordpress site wp-admin url",
    "base_url": "Your wordpress site index.php url",
    "homeUrl": "Your wordpress site url",
    "capabilities": {
        "canAccessAI": boolean,
        "canAccessGlobalCTB": boolean,
        "canAccessHelpCenter": boolean,
        "hasEcomdash": boolean,
        "hasYithExtended": boolean,
        "isEcommerce": boolean,
        "isJarvis": boolean
    },
    "sdk": {
        "wpversion": "Current WordPress version",
        "plugin": {
            "url": "Brand plugin build url",
            "version": "Brand plugin version",
            "assets": "Brand plugin assets url",
            "brand": "Current WordPress hosting brand name"
        },
        "ecommerce": {
            "brand_settings": {
                "brand": "Hosting brand value",
                "name": "Hosting brand name",
                "url": "Hosting brand page url",
                "hireExpertsInfo": "Marketplace service purchase url",
                "support": "Hosting brand support contact url",
                "adminPage": "Plugin Dashboard Homepage url",
                "setup": {
                    "payment": [
                        Payment options supported for eg: "Paypal", "Razorpay", "Stripe"
                    ],
                    "shipping": [
                        Shipping options supporter for eg: "Shippo"
                    ]
                },
                "defaultContact": {
                    "woocommerce_default_country": "Default country code",
                    "woocommerce_currency": "Default currency code"
                },
                "wondercartBuyNow": "Marketplace link to purchase WonderCart"
            },
            "nonces": {
                "gateway_toggle": ""gateway_toggle_code"
            },
            "install_token": "NFD_INSTALLER_token"
        }
    },
    "siteUrl": "Your wordpress site url",
    "siteTitle": "Your wordpress site name",
    "restUrl": "Your wordpress site /wp-json/",
    "restNonce": "restNonce_value",
    "isWoocommerceActive": boolean,
    "isYithBookingActive": boolean,
    "isJetpackBoostActive": boolean,
    "wpVersion": "Current WordPress version",
    "currentTheme": "Current Theme Name",
    "context": {
        "platform": "default",
        "brand": {
            "name": "Hosting brand name"
        }
    },
    "plugin": {
            "url": "Brand plugin build url",
            "version": "Brand plugin version",
            "assets": "Brand plugin assets url",
            "brand": "Current WordPress hosting brand name"
    },
    "comingSoon": {
      enable: {
        success: boolean
      }  
      isEnabled: boolean,
      disabled: {
        success: boolean
      }
      lastChanged: timeStamp when coming soon setting was updated last time
      toggleAdminBarSiteStatus: null
    }
}
```

## Critical Paths

* Runtime should provides, 
    1. WordPress site meta data
    2. Site capabilties
    3. WooCommerce & YITH plugin active/inactive status
    4. Brand plugin information 
    5. Ecommerce details like, Payment & Shipping third party softwares supported and Support contact information
    6. WordPress version 
    7. Current WordPress Theme.
* Runtime module should support addition of custom values as per need.

## Installation

Previously, this module's package was hosted at github and these instructions were required [GH Packages Setup](https://gist.github.com/aulisius/1a6e4961f17039d82275a6941331b021). Now the package is hosted at npmjs and this referenced setup is no longer relevant. Find the npmjs package at https://www.npmjs.com/package/@newfold/wp-module-runtime and install just as any other public package. Note the `newfold` org namespace for npmjs and the `newfold-labs` org namespace for github and satis.

### 1. Install the `@newfold/wp-module-runtime` npm package.

```bash
npm install @newfold/wp-module-runtime
```

## PHP Module

The PHP module can be installed only on the Brand plugin.

### 1. Add the Newfold Satis to your `composer.json`.

```bash
composer config repositories.newfold composer https://newfold-labs.github.io/satis
```

### 2. Require the `newfold/wp-module-runtime` package.

```bash
composer require newfold/wp-module-runtime
```

## Usage

In your React component you can import the runtime module as so,

```js
import { NewfoldRuntime } from "@newfold/wp-module-runtime";

function Component(props) {
  if (NewfoldRuntime.hasCapability("hasYithExtended")) {
    //
  }
}
```

## Advanced Usage

### Adding custom values

While the default runtime has useful values, you can extend the runtime and add newer values under `NewfoldRuntime.sdk`. This is done via use of WP filter.

__An example usage__

```php
add_filter( 'newfold-runtime', array( $this, 'add_to_runtime' ) );

public function add_to_runtime( $sdk ) {
  return array_merge( $sdk, array( 'my_field' => 'custom value' ) );
}
```

Now when you use `NewfoldRuntime.sdk.my_field`, you'll see the value as `'custom value'`;

### Type definition for `NewfoldRuntime.sdk`

As the runtime can be extended via the `newfold-runtime` filter, you can also configure the type definitions to make sure you safely access the extended values. Since `sdk` is an TS interface, it can be easily extended in the following manner.

```ts
declare module "@newfold/wp-module-runtime" {
  export interface DefaultSdk {
    my_field: string;
  }
}
```

Now when you use `NewfoldRuntime.sdk`, your editor should give you the correct type hints.

[More on Newfold WordPress Modules](https://github.com/newfold-labs/wp-module-loader)
