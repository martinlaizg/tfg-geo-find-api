<?php
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class SiteTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testGetSites()
    {
        $users = factory('App\User')->create();
        $this->json('GET', "/api/users/" . $users->id)->assertResponseStatus(200);
        $response = $this->call('GET', '/api/tours/');
    }
}
