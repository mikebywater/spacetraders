<?php


namespace App\Services;

use App\Models\Good;
use App\Models\Ship;
use App\Models\Trade;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @property SpaceTradersService client
 * @property  ship
 */
class ShipService
{

    public function __construct(Ship $ship, Trade $trade, SpaceTradersService $client)
    {
        $this->client = $client();
        $this->ship = $ship;
        $this->trade = $trade;
    }

    // Find Ship
    public function find(String $ship_id) :Ship
    {
        return $this->ship->find($ship_id);
    }

    // Return docked ships
    public function findDocked()
    {
       return $this->ship->where('location' , '!=', '')->get();
    }

    // Fly to a location
    public function fly(String $ship_id, String $location_id) : bool
    {
        $ship = $this->find($ship_id);
        try{
            $plan = $this->client->flightPlans->create(getenv('ST_USERNAME'), $ship->id , $location_id);
            print_r($plan);
            return true;
        }catch(\Exception $e){
            print("insufficient fuel");
            return false;
        }
    }

    // Sell all cargo other than fuel
    public function sellCargo(String $ship_id)
    {
        $ship = $this->find($ship_id);
        $cargo = $this->client->ships->get(getenv('ST_USERNAME'), $ship->id)->ship->cargo;
        foreach ($cargo as $good)
        {

            if($good->good != "FUEL"){
                $sale = $this->client->orders->sell(getenv('ST_USERNAME'), $ship->id, $good->good, $good->quantity);
                $this->trade->create(['ship_id' => $ship->id, 'type' => 'SALE',
                    'good' => $good->good, 'location_id' => $ship->location, 'total_credits' => $sale->credits, 'value' => $sale->order->total ]);
            }
        }
        return $ship;
    }

    // Fill fuel up to 40 or specified amount
    public function refuel(String $ship_id, $qty = 50)
    {
        // check if fuel low and buy some if it is always have 20 reserved for fuel
        $ship = $this->find($ship_id);
        if($ship->fuel < $qty) {
            $qty = min($qty - $ship->fuel, $ship->spaceAvailable);
            if($qty > 0){
                try {
                    $buy = $this->client->orders->purchase(getenv('ST_USERNAME'), $ship->id, 'FUEL', $qty);
                }catch (\Exception $e){
                    print($ship->id . " | " . $ship->spaceAvailable .  " | " . $qty);
                }
                $ship->spaceAvailable = $ship->spaceAvailable - $qty;
                $ship->fuel =  $ship->fuel + $qty;
                $ship->save();
                Trade::create(['ship_id' => $ship->id, 'type' => 'PURCHASE',
                    'good' => 'FUEL', 'location_id' => $ship->location, 'total_credits' => $buy->credits, 'value' => $buy->order->total]);
            }

        }
        return $ship;
    }

    public function refreshAll()
    {
        $this->ship->truncate();
        $ships = $this->client->ships->get(getenv('ST_USERNAME'));
        foreach ($ships->ships as $ship){

            $goods = $ship->cargo;
            $ship->fuel = 0;
            foreach ($goods as $good)
            {
                if($good->good = 'FUEL'){
                    $ship->fuel = $good->quantity;
                }
            }

            if(!property_exists($ship, 'location')){
                $ship->location = "";
                $ship->x = 0;
                $ship->y = 0;
            }

            $this->ship->updateOrCreate(['id' => $ship->id], ['class' => $ship->class, 'location' => $ship->location,
                'manufacturer' => $ship->manufacturer, 'maxCargo' => $ship->maxCargo, "type" => $ship->type, "x" => $ship->x, "y" => $ship->y,
                'fuel' => $ship->fuel, 'spaceAvailable' => $ship->spaceAvailable] );
        }
    }

    public function buyCargo(Ship $ship, $good, $budget)
    {

        $space = $ship->spaceAvailable / $good->volumePerUnit;
        $stock = $good->quantityAvailable;
        $afford = $budget / $good->purchasePricePerUnit;
        $qty = intval(floor(min( $space, $stock, $afford)));

        print($ship->spaceAvailable . "|" . $qty * $good->volumePerUnit);

        $good = $good->symbol;

        try{
            $buy = $this->client->orders->purchase(getenv('ST_USERNAME'), $ship->id, $good, $qty);
            Trade::create(['ship_id' => $ship->id, 'type' => 'PURCHASE',
                'good' => $good, 'location_id' => $ship->location, 'total_credits' => $buy->credits, 'value' => $buy->order->total]);
            return true;
        }catch(\Exception $e) {
            print($e->getMessage());
        }
        return false;
    }



}