# SendFox Integration Package

This package provides a simple integration with the SendFox API for Laravel applications. It allows you to create contacts using the SendFox API.

## Installation

1. Add the package to your Laravel project:
   ```bash
   composer require buchin/sendfox-integration
   ```

2. Publish the configuration file:
   ```bash
   php artisan vendor:publish --tag=config
   ```

3. Add the required environment variables to your `.env` file:
   ```env
   SENDFOX_TOKEN=your_sendfox_api_token
   SENDFOX_URL=https://api.sendfox.com/contacts
   SENDFOX_LIST_ID=your_list_id
   ```

## Usage

### Creating a Contact

You can use the `SendFoxService` to create a contact:

```php
use Buchin\SendFoxIntegration\SendFoxService;

$service = new SendFoxService();

$user = (object) [
    'email' => 'test@example.com',
    'first_name' => 'Test',
    'last_name' => 'User',
];

$response = $service->createContact($user);

if ($response->successful()) {
    echo "Contact created successfully!";
} else {
    echo "Failed to create contact.";
}
```

### Automatically Sending User to SendFox on Creation

You can use Laravel's model events to automatically send a user to SendFox when a user is created. For example, in your `User` model:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Buchin\SendFoxIntegration\SendFoxService;

class User extends Model
{
    protected static function booted()
    {
        static::created(function ($user) {
            $service = new SendFoxService();

            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            $response = $service->createContact((object) [
                'email' => $user->email,
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]);

            if ($response->successful()) {
                Log::info('Contact created successfully in SendFox.');
            } else {
                Log::error('Failed to create contact in SendFox.', $response->json());
            }
        });
    }
}
```

## Testing

The package includes its own tests. To run the tests, navigate to the package directory and run:

```bash
php artisan test packages/SendFoxIntegration/tests
```

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to discuss your ideas.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).