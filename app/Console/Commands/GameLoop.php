<?php

namespace App\Console\Commands;

use App\Models\Good;
use App\Models\Location;
use App\Models\Ship;
use App\Models\Trade;
use App\Models\User;
use App\Services\ShipService;
use App\Services\SpaceTradersService;
use App\Services\TradeService;
use App\Services\UserService;
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
     * @var ShipService
     */
    private $shipService;
    /**
     * @var TradeService
     */
    private $tradeService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserService $userService, ShipService $shipService, SpaceTradersService $service, TradeService $tradeService)
    {
        $this->userService = $userService;
        $this->client = $service();
        $this->shipService = $shipService;
        $this->tradeService = $tradeService;
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
        $this->user = $user;
        $ship_total = Ship::all()->count();
        if($user->credits > 100000 and $ship_total < 10)
        {
            // buy ship
            $this->client->ships->purchase(getenv('ST_USERNAME'), 'OE-PM-TR', 'EM-MK-I');
        }
        if($user->credits > 80000 and $ship_total < 10)
        {
            $this->client->ships->purchase(getenv('ST_USERNAME'), 'OE-UC-OB', 'ZA-MK-II');
        }
        $this->userService->refresh();
        $this->shipService->refreshAll();

        //fuel docked ships

        foreach ($this->shipService->findDocked() as $ship){

            $this->shipService->refuel($ship->id);
            $this->shipService->sellCargo($ship->id);
            $route = $this->tradeService->plotRoute($ship->location);
            $this->shipService->buyCargo($ship, $route['good'] , $this->user->credits - 1000);
            $this->shipService->fly($ship->id, $route['destination']);
            //update goods
           // $this->buyGoods($ship);
        }
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
                        $margin = $sale->sellPricePerUnit - $price;
                        if($margin > $best){
                            $location = $sale->location;
                            $best = $margin;
                            $best_good = $sale->type;
                            $qty = intval(floor(min($ship->spaceAvailable / $sale->volumePerUnit, $sale->quantityAvailable)));
                        }
                    }
                }
            }

            if($location != "" and $best > 4 and $qty > 0){
                try{
                    $buy = $this->client->orders->purchase(getenv('ST_USERNAME'), $ship->id, $best_good, $qty);
                    Trade::create(['ship_id' => $ship->id, 'type' => 'PURCHASE',
                        'good' => $best_good, 'location_id' => $ship->location, 'total_credits' => $buy->credits, 'value' => $buy->order->total]);
                    $this->fly($ship, $location);
                }catch(\Exception $e) {
                    print($e->getMessage() . $ship->id . " | " . $best_good . " | " . $ship->spaceAvailable .  " | " . $qty);
                    try{
                        $buy= $this->client->orders->purchase(getenv('ST_USERNAME'), $ship->id, $best_good, 4);
                        Trade::create(['ship_id' => $ship->id, 'type' => 'PURCHASE',
                            'good' => $best_good, 'location_id' => $ship->location, 'total_credits' => $buy->credits, 'value' => $buy->order->total]);
                        $this->fly($ship, $location);
                    }catch(\Exception $e) {
                        print($e->getMessage() . $ship->id . " | " . $best_good . " | " . $ship->spaceAvailable .  " | " . $qty);
                    }
                }


            }else{
                $location = Location::where('id' , "!=" , $ship->location)->where('type' , "!=", "WORMHOLE")->has('goods' , 0)->get()->first();
                if($location){
                    $this->fly($ship, $location);
                }else{
                    $location = Location::where('id' , "!=" , $ship->location)->where('type' , "!=", "WORMHOLE")->get()->random(1)->first();
                    $this->fly($ship, $location);
                }
            }

        return $ship;
    }

    private function sellGoods($ship)
    {
        $cargo = $this->client->ships->get(getenv('ST_USERNAME'), $ship->id)->ship->cargo;
        foreach ($cargo as $good)
        {

            if($good->good != "FUEL"){
                $sale = $this->client->orders->sell(getenv('ST_USERNAME'), $ship->id, $good->good, $good->quantity);
                Trade::create(['ship_id' => $ship->id, 'type' => 'SALE',
                    'good' => $good->good, 'location_id' => $ship->location, 'total_credits' => $sale->credits, 'value' => $sale->order->total ]);
            }
        }
        return $ship;
    }
}
