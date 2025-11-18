# KeyStack PHP Manager SDK

A lightweight, developer-friendly PHP SDK for the KeyStack Manager API. It wraps the Manager API client with a simple, cohesive interface and takes care of configuration, authentication, and retry on expired tokens.

- Repository: keystackapp/keystack-php-manager-sdk
- License: MIT
- Status: Early preview

## Features

- Simple ManagerSDK facade with high-level methods
  - Licenses: create, get, list, update, delete, view activations, delete activation
  - Manifest: add record, delete record
- Built-in auth bootstrapping via keystack-php-auth
- Automatic token refresh on 401 responses
- Pluggable token storage (session by default)

## Requirements

- PHP >= 8.2 (Symfony HttpClient 7.x requirement)
- Composer
- A KeyStack API key

## Installation

Install via Composer:

```bash
composer require keystackapp/keystack-php-manager-sdk
```

This SDK pulls in:
- keystackapp/keystack-php-auth (for login and token handling)
- keystackapp/keystack-php-manager-client (Manager API client library)

## Getting started

You can provide your API key either via environment variable or as a constructor argument.

- Environment variable: `KEYSTACK_API_KEY="<YOUR_API_KEY>"`
- Constructor parameter: pass as the second argument to the SDK

By default, the SDK stores tokens in the PHP session (SessionAdapter). You can bring your own token storage by implementing TokenStorageAdapterInterface from keystack-php-auth.

### Minimal example

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use KeyStackApp\ManagerSDK;

// Option A: use KEYSTACK_API_KEY env var
$sdk = new ManagerSDK();
// Option B: pass API key directly (you can also pass a custom TokenStorageAdapter as first argument)
// $sdk = new ManagerSDK(null, 'ks_live_...');

// List all licenses
$licenses = $sdk->getAllLicenses();
foreach ($licenses->getLicenses() ?? [] as $license) {
    echo $license->getInternalId() . "\n";
}
```

## Usage

Below are practical examples for the most common operations. All methods may throw \Exception on failure.

### Licenses

Create a license:
```php
use KeyStackApp\Authentication\Model\LicenseCreateInput;

$input = new LicenseCreateInput([
    'email' => 'user@example.com',
    // add other supported fields here based on your KeyStack setup
]);

$record = $sdk->createLicense($input);
echo $record->getInternalId();
```

Get a license by internal ID:
```php
$license = $sdk->getLicense('lic_123');
echo $license->getEmail();
```

Update a license:
```php
use KeyStackApp\Authentication\Model\LicenseUpdateInput;

$update = new LicenseUpdateInput([
    // e.g. 'metadata' => ['plan' => 'pro']
]);

$updated = $sdk->updateLicense('lic_123', $update);
```

Delete a license:
```php
$sdk->deleteLicense('lic_123');
```

List all licenses:
```php
$list = $sdk->getAllLicenses();
foreach ($list->getLicenses() ?? [] as $lic) {
    // ...
}
```

List activations for a license:
```php
$activations = $sdk->getActivations('lic_123');
foreach ($activations->getItems() ?? [] as $act) {
    // ...
}
```

Delete a specific activation:
```php
$sdk->deleteActivation('lic_123', 'act_456');
```

### Manifest

Add a manifest record:
```php
use KeyStack\Manager\Model\ManifestAddSchema;

$manifest = new ManifestAddSchema([
    'key' => 'my-feature-flag',
    'value' => 'enabled',
]);

$response = $sdk->addManifest($manifest);
```

Delete a manifest record by key:
```php
$sdk->deleteManifestRecord('my-feature-flag');
```

## Authentication, configuration, and token storage

Internally, ManagerSDK extends a ConfiguredApi class that:
- Builds the API base URL from your API key’s project info
- Logs in via keystack-php-auth to obtain a bearer token if none is available
- Sets the token on the underlying ManagerApi client
- Retries the call once on HTTP 401 after re-login

Default token storage is the SessionAdapter (stores the token in PHP sessions). If you need a different storage (filesystem, cache, DB), implement `KeyStackApp\Adapter\TokenStorageAdapterInterface` and pass it as the first argument to `new ManagerSDK($adapter, $apiKey)`.

## Error handling

- Methods throw \Exception on errors from the underlying HTTP or API client.
- On 401 Unauthorized responses, the SDK attempts a re-login and retries once.
- Ensure your API key is valid and belongs to the intended project/environment.

## Troubleshooting

- 401 errors repeatedly: double-check the API key, and that your clock is accurate.
- CLI usage with sessions: provide a custom TokenStorageAdapter instead of the default session-based one.

## License

MIT © KeyStack
