<?php


namespace App\Services;


use RayBlair\SpaceTradersPHP\SpaceTradersPHP;

class SpaceTradersService
{

    public function __invoke() : SpaceTradersPHP
    {
        return new SpaceTradersPHP(getenv('ST_TOKEN'), getenv('ST_USERNAME'));
    }
}