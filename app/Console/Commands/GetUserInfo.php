<?php

namespace App\Console\Commands;

use App\Models\Good;
use App\Models\Location;
use App\Models\Ship;
use App\Models\User;
use Illuminate\Console\Command;
use RayBlair\SpaceTradersPHP\SpaceTradersPHP;

class GetUserInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'st:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get user information';

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
        $user = $this->client->users->get(getenv('ST_USERNAME'))->user;
        $loan = $user->loans[0]->repaymentAmount;
        User::updateOrCreate(['username' => getenv('ST_USERNAME')] , ['credits' => $user->credits, 'loan' => $loan]);

        $locations = $this->client->systems->get('OE');
        foreach($locations->locations as $location) {
                Location::updateOrCreate(['id' => $location->symbol], ['name' => $location->name, 'type' => $location->type, 'x' => $location->x, 'y' => $location->y]);
            }
        $ships = $this->client->ships->get(getenv('ST_USERNAME'));

        // refresh your ships
        Ship::truncate();
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
            }else{
                $this->updateGoods($ship->location);
            }
            Ship::updateOrCreate(['id' => $ship->id], ['class' => $ship->class, 'location' => $ship->location,
                'manufacturer' => $ship->manufacturer, 'maxCargo' => $ship->maxCargo, "type" => $ship->type, "x" => $ship->x, "y" => $ship->y,
                'fuel' => $ship->fuel] );
        }

    }
    private function updateGoods($location){
        $results = $this->client->locations->marketplace($location);
            foreach($results as $result){
                $goods = $result->marketplace;
                foreach ($goods as $good){
                    Good::updateOrCreate(['id' => $location . '-' . $good->symbol], ["quantityAvailable" => $good->quantityAvailable,
                        "pricePerUnit" => $good->pricePerUnit, "volumePerUnit" => $good->volumePerUnit, "location" => $location, "type" => $good->symbol]);
                }
            }
    }
}
