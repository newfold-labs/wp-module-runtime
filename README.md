<a href="https://newfold.com/" target="_blank">
    <img src="https://newfold.com/content/experience-fragments/newfold/site-header/master/_jcr_content/root/header/logo.coreimg.svg/1621395071423/newfold-digital.svg" alt="Newfold Logo" title="Newfold Digital" align="right" 
height="42" />
</a>

# wp-module-runtime

Runtime for Newfold WP modules and plugins

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
