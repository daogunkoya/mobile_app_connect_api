<?php

// database/factories/SenderFactory.php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;
class DomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Domain::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'store_id' => '2bda0c37-4eac-44e5-a014-6c029d76dc62',
            //'domain_name' => $this->faker->domainName,
            //'domain_host' => $this->faker->domainWord,
            'domain_host' => 'localhost',
            'domain_name' => 'https://localhost',
            'domain_slug' =>  "test",
            'domain_default' =>  1,
            'domain_verified' => 1,
            'domain_status' => 1,
            'moderation_status' => 1,
            'domain_local' => 1,
            'aws_ssl_arn' =>  "test",
            'aws_resource_record' =>  "test",
            'domain_ssl_verified' => 1,
            'domain_cname_verified' => 1,
            // Add other fields as needed
        ];
    }
}
