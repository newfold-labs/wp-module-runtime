<a href="https://newfold.com/" target="_blank">
    <img src="https://newfold.com/content/experience-fragments/newfold/site-header/master/_jcr_content/root/header/logo.coreimg.svg/1621395071423/newfold-digital.svg" alt="Newfold Logo" title="Newfold Digital" align="right" 
height="42" />
</a>

# wp-module-runtime

Runtime for Newfold WP modules and plugins

## Module Responsibilities

* Container is loaded from the brand plugin in Runtime class and it returns a `NewfoldRuntime` object. 
* This object can be imported in any React components as specified in `Usage` section below. 
* Primary respnsibility of `wp-module-runtime` is to return `NewfoldRuntime` object which provides WordPress site meta data to React components of any newfold-labs modules in below format.


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

* Runtime should provide WordPress site meta data.
* Runtime module should also provide status of site capabilities like: 
    1. If WordPress site has access to AI onboarding
    2. If WordPress site has access to GlobalCTB
    3. If WordPress site has access to HelpCenter
    4. If WordPress site has Ecomdash plugin enabled
    5. If WordPress site has any of Yith Extended plugins enabled 
    6. If WordPress site is an Ecommerce store
    7. If WordPress site is migrated to Jarvis
* Runtime module can be extended by adding custom values under NewfoldRuntime.sdk

## Installation

### 1. Setup GitHub registry

Follow instructions at [GH Packages Setup](https://gist.github.com/aulisius/1a6e4961f17039d82275a6941331b021).

### 2. Install the `@newfold-labs/wp-module-runtime` npm package.

```bash
npm install @newfold-labs/wp-module-runtime
```

## PHP Module

The PHP module can be installed only on the Brand plugin.

### 1. Add the Newfold Satis to your `composer.json`.

```bash
composer config repositories.newfold composer https://newfold-labs.github.io/satis
```

### 2. Require the `newfold-labs/wp-module-runtime` package.

```bash
composer require newfold-labs/wp-module-runtime
```

## Usage

In your React component you can import the runtime module as so,

```js
import { NewfoldRuntime } from "@newfold-labs/wp-module-runtime";

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
declare module "@newfold-labs/wp-module-runtime" {
  export interface DefaultSdk {
    my_field: string;
  }
}
```

Now when you use `NewfoldRuntime.sdk`, your editor should give you the correct type hints.

[More on NewFold WordPress Modules](https://github.com/newfold-labs/wp-module-loader)
