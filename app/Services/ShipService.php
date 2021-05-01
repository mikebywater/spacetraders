<?php


namespace App\Services;


use App\Models\Ship;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @property SpaceTradersService client
 * @property  ship
 */
class ShipService
{

    public function __construct(Ship $ship, SpaceTradersService $client)
    {
        $this->client = $client();
        $this->ship = $ship;
    }


    // Fly to a location
    public function fly(String $ship_id, String $location_id) : Boolean
    {
        $this->ship = $this->ship->find($ship_id);
        try{
            $this->client->flightPlans->create(getenv('ST_USERNAME'), $this->ship->id , $location_id);
            return true;
        }catch(\Exception $e){
            print("insufficient fuel");
            return false;
        }
    }

    // Sell all cargo other than fuel
    public function sellCargo()
    {

    }

    // Fill fuel up to 40 or specified amount
    public function refuel()
    {

    }

}