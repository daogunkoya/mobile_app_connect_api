<?php
namespace Tests\Unit;

use App\Models\Currency;
use App\Models\User;
use App\Repositories\CurrencyRepository;
use Tests\TestCase;

class CurrencyRepositoryTest extends TestCase
{
    //use RefreshDatabase;

    public function testFetchUserCurrencyIdWithUserId()
    {
        // Create a user and any necessary related data for testing
        $user = User::factory()->create([
            'active_currency_id' => 'USD', // Assuming active_currency_id is a string
        ]);

        // Instantiate the CurrencyRepository
        $currencyRepository = new CurrencyRepository();

        // Call the function and get the result
        $currencyId = $currencyRepository->fetchUserCurrencyId($user->id_user);

        // Assert that the currencyId matches the expected active_currency_id
        $this->assertEquals('USD', $currencyId);
    }

    public function testFetchUserCurrencyIdWithoutUserId()
    {
        // Instantiate the CurrencyRepository
        $currencyRepository = new CurrencyRepository();

        // Call the function without a specific user ID
        $currencyId = $currencyRepository->fetchUserCurrencyId();

        $defaultCurrencyId = Currency::where('default_currency', 1)->value('id_currency');

        // Assert that the currencyId matches the expected default currency ID
        $this->assertEquals($defaultCurrencyId , $currencyId); // Replace with the expected default currency ID
    }
}
