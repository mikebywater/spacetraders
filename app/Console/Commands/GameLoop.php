<?php

namespace App\Console\Commands;

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
    protected $description = 'Command description';

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
            $this->fuel($ship);
        }
    }

    private function fuel($ship)
    {
        // check if fuel low and buy some if it iis always have 20 reserved for fuel
        if($ship->fuel < 20) {
            $qty = 20 - $ship->fuel;
            $this->client->orders->purchase(getenv('ST_USERNAME'), $ship->id, 'FUEL', $qty);
            $ship->fuel = 20;
            $ship->save();
        }
        return $ship;
    }
}
