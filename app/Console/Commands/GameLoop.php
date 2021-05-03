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
use Illuminate\Support\Facades\Log;
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
        if(Location::all()->count() == 0) {
            $locations = $this->client->systems->get('OE');
            // $locations = $this->client->systems->get('XV');
            foreach ($locations->locations as $location) {
                Location::updateOrCreate(['id' => $location->symbol], ['name' => $location->name, 'type' => $location->type, 'x' => $location->x, 'y' => $location->y]);
            }
        }
        //buy ships
        $this->user = $this->userService->refresh();
        $ship_total = Ship::all()->count();
        if ($this->user->credits > 150000 and $ship_total < 5) {
            // buy ship
            try{
                $this->client->ships->purchase(getenv('ST_USERNAME'), 'OE-PM-TR', 'EM-MK-I');
                $this->userService->refresh();
                $this->shipService->refreshAll();
            }catch(\Exception $e){
                print($e->getMessage());
            }

        }
        if ($this->user->credits > 100000 and $ship_total < 4) {

            try{
                $this->client->ships->purchase(getenv('ST_USERNAME'), 'OE-UC-OB', 'ZA-MK-II');
                $this->userService->refresh();
                $this->shipService->refreshAll();
            }catch(\Exception $e){
                print($e->getMessage());
            }
        }



        //fuel docked ships
        $this->shipService->refreshAll();

        foreach ($this->shipService->findDocked() as $ship) {

            $this->shipService->sellCargo($ship->id);
            $this->tradeService->updateGoods($ship->location);
            $route = $this->tradeService->plotRoute($ship->location);

            // if a margin then trade else explore
            if($route['margin'] > 0){
                Log::info(strtoupper(substr($ship->id, -4)) . " plotted route departing from " . $ship->location . " to  " . $route['destination'] . " to sell " . $route['good']->symbol . " with a margin of " . $route['margin']  );
                $ship = $this->shipService->refuel($ship->id, $route['fuel'] ); // set ship to refuelled version
                if($this->shipService->buyCargo($ship, $route['good'], $this->user->credits * 0.9 )){
                    $this->tradeService->updateGoods($ship->location);
                    $this->shipService->fly($ship->id, $route['destination']);
                }
            }else{
                $this->shipService->refuel($ship->id);
                try{
                    $destination = Location::where('scouted' , 0)->where('type', '!=' , 'WORMHOLE')->where('id' , "!=" , $ship->location)->get()->random(1)->first();
                    $this->shipService->fly($ship->id,$destination->id);
                    Log::info(strtoupper(substr($ship->id, -4)). " plotted route departing from " . $ship->location . " to  " . $destination->id . " to scout the location"  );
                }catch(\Exception $e){
                    $destination = Location::where('type', '!=' , 'WORMHOLE')->where('type', '!=', 'PLANET')->where('id' , "!=" , $ship->location)->get()->random(3)->first();
                    // need to work a good route out here but I reckon for now just random!
                    $this->shipService->fly($ship->id,$destination->id);
                    Log::info(strtoupper(substr($ship->id, -4)). " plotted route departing from " . $ship->location . " to  " . $destination->id . " as no profitable route can be found"  );

                }

            }

            sleep(5); // Too many requests issue

        }
    }
}
