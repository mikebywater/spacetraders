<?php


namespace App\Services;


use App\Models\User;
use RayBlair\SpaceTradersPHP\SpaceTradersPHP;

class UserService
{

    public function __construct()
    {
        $this->client = new SpaceTradersPHP(getenv('ST_TOKEN'), getenv('ST_USERNAME'));
    }

    public function refresh()
    {
        $user = $this->client->users->get(getenv('ST_USERNAME'))->user;
        if(empty($user->loans)){
            $this->client->loans->takeout(getenv('ST_USERNAME'), 'STARTUP');
            $user = $this->client->users->get(getenv('ST_USERNAME'))->user;
        }
        $loan = $user->loans[0]->repaymentAmount;
        $user = User::updateOrCreate(['username' => getenv('ST_USERNAME')] , ['credits' => $user->credits, 'loan' => $loan]);
        return $user;
    }

    public function updateCredits($credits)
    {
        return User::updateOrCreate(['username' => getenv('ST_USERNAME')] , ['credits' => $credits]);
    }
}