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

        $locations = $this->client->systems->get('OE');
        // $locations = $this->client->systems->get('XV');
        foreach($locations->locations as $location) {
            Location::updateOrCreate(['id' => $location->symbol], ['name' => $location->name, 'type' => $location->type, 'x' => $location->x, 'y' => $location->y]);
        }
        //buy ships
        $this->user = $this->userService->refresh();
        $ship_total = Ship::all()->count();
        if ($this->user->credits > 150000 and $ship_total < 2) {
            // buy ship
           // $this->client->ships->purchase(getenv('ST_USERNAME'), 'OE-PM-TR', 'EM-MK-I');
        }
        if ($this->user->credits > 100000 and $ship_total < 4) {
            $this->client->ships->purchase(getenv('ST_USERNAME'), 'OE-UC-OB', 'ZA-MK-II');
        }
        $this->userService->refresh();
        $this->shipService->refreshAll();

        //fuel docked ships

        foreach ($this->shipService->findDocked() as $ship) {

            $this->shipService->refuel($ship->id);
            $this->shipService->sellCargo($ship->id);
            $this->tradeService->updateGoods($ship->location);
            $route = $this->tradeService->plotRoute($ship->location);
            print("ROUTE fuel is " . $route['fuel'] . " and ROUTE margin is " . $route['margin'] ."\r\n");
            // if a margin then trade else explore
            if($route['margin'] > 0){
                $this->user = $this->userService->refresh();
                if($this->shipService->buyCargo($ship, $route['good'], $this->user->credits * 0.9 )){
                    $this->tradeService->updateGoods($ship->location);
                    $this->shipService->fly($ship->id, $route['destination']);
                }

            }else{
                try{
                    $destination = Location::where('scouted' , 0)->where('type', '!=' , 'WORMHOLE')->get()->random(1)->first();
                    $this->shipService->fly($ship->id,$destination->id);
                }catch(\Exception $e){
                    $destination = Location::where('type', '!=' , 'WORMHOLE')->where('type', '!=', 'PLANET')->get()->random(3)->first();
                    // need to work a good route out here but I reckon for now just random!
                    $this->shipService->fly($ship->id,$destination->id);

                }

            }


        }
        $this->userService->refresh();
        $this->shipService->refreshAll();
    }
}
