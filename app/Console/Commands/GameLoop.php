<?php

namespace App\Console\Commands;

use App\Models\Good;
use App\Models\Location;
use App\Models\Ship;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use RayBlair\SpaceTradersPHP\SpaceTradersPHP;

class GameLoop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'st:loop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fly the planes!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new SpaceTradersPHP(getenv('ST_TOKEN'), getenv('ST_USERNAME'));
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //buy ships
        $user = User::where('username' , getenv('ST_USERNAME'))->first();
        if($user->credits > 50000)
        {
            // buy ship
            $this->client->ships->purchase(getenv('ST_USERNAME'), 'OE-PM-TR', 'JW-MK-I');
        }

        //fuel docked ships

        $docked = Ship::where('location' , '!=', '')->get();
        foreach ($docked as $ship){
            $this->sellGoods($ship);
            $this->buyGoods($ship);
        }
    }

    private function fuel($ship)
    {
        // check if fuel low and buy some if it is always have 20 reserved for fuel
        if($ship->fuel < 40) {
            $qty = 40 - $ship->fuel;
            $this->client->orders->purchase(getenv('ST_USERNAME'), $ship->id, 'FUEL', $qty);
            $ship->spaceAvailable = $ship->spaceAvailable - $qty;
            $ship->fuel = 40;
            $ship->save();
        }
        return $ship;
    }

    private function fly($ship, $destination){
        try{
            $this->client->flightPlans->create(getenv('ST_USERNAME'), $ship->id , $destination->id);
        }catch(\Exception $e){
            print("insufficient fuel");
        }

        return $ship;
    }

    private function buyGoods($ship)
    {
        $location = "";
        $best = -1000;
        $best_good = "";

        //select goods
        $results = $this->client->locations->marketplace($ship->location);
        foreach($results as $result) {
            $goods = $result->marketplace;
            foreach ($goods as $good)
                if($good->symbol == "FUEL"){
                   $ship = $this->fuel($ship);
                }else{
                    $price = $good->purchasePricePerUnit;
                    $sales = Good::where('type' , $good->symbol)->get();
                    foreach($sales as $sale){
                        $margin = $sale->pricePerUnit - $price;
                        if($margin > $best){
                            $location = $sale->location;
                            $best = $margin;
                            $best_good = $sale->type;
                            $qty = min($ship->spaceAvailable / $sale->volumePerUnit, $sale->quantityAvailable);
                        }
                    }
                }
            }

            if($location != "" and $best > 0 ){
                //buy
                $this->client->orders->purchase(getenv('ST_USERNAME'), $ship->id, $best_good, $qty);
                $this->fly($ship, $location);
            }else{
                $location = Location::where('id' , "!=" , $ship->location)->where('type' , "!=", "WORMHOLE")->get()->random(1)->first();
                $this->fly($ship, $location);
            }

        return $ship;
    }

    private function sellGoods($ship)
    {
        $cargo = $this->client->ships->get(getenv('ST_USERNAME'), $ship->id)->ship->cargo;
        foreach ($cargo as $good)
        {

            if($good->good != "FUEL"){
                $this->client->orders->sell(getenv('ST_USERNAME'), $ship->id, $good->good, $good->quantity);
            }
        }
        return $ship;
    }
}
