<?php
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Monolog\Logger;

class SiteTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testGetSites()
    {
        $log = new Logger(__METHOD__);
        $users = factory('App\User')->create();
        $log->info($users);
        $this->seeInDatabase('users', ['name' => $users->name]);

        // $this->json('GET', "/api/users/" . $users->id)->assertResponseStatus(200);
        // $response = $this->call('GET', '/api/tours/');
    }
}
