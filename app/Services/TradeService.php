<?php


namespace App\Services;


use App\Models\Good;
use App\Models\Location;

class TradeService
{

    public function __construct(SpaceTradersService $client)
    {
        $this->client = $client();
    }


    public function updateGoods($location){
        $results = $this->client->locations->marketplace($location);
        foreach($results as $result){
            $goods = $result->marketplace;
            foreach ($goods as $good){
                Good::updateOrCreate(['id' => $location . '-' . $good->symbol], ["quantityAvailable" => $good->quantityAvailable,
                    "pricePerUnit" => $good->pricePerUnit, "purchasePricePerUnit" => $good->purchasePricePerUnit,
                    "sellPricePerUnit" => $good->sellPricePerUnit  ,"volumePerUnit" => $good->volumePerUnit,
                    "location" => $location, "type" => $good->symbol]);
            }
        }
        Location::updateOrCreate(['id' => $location] , ['scouted' => true]);
    }


    // return location, good to trade and fuel needed
    public function plotRoute(String $location)
    {
        // get x y of location

        // get all goods at location
        // cycle through goods
        // make sure good is not more than 40 fuel away
        // check margin
        // loop to find best margin
        // return route array



        //select goods
        $results = $this->client->locations->marketplace($location);

        $destination = "";
        $best = -1000;
        $best_good = "";
        $qty = 0;

        foreach($results as $result) {
            $goods = $result->marketplace;
            foreach ($goods as $good) {
                if($good->symbol != 'FUEL'){
                    $price = $good->purchasePricePerUnit;
                    $sales = Good::where('type', $good->symbol)->get();
                    foreach ($sales as $sale) {
                        $margin = $sale->sellPricePerUnit - $price;
                        $fuel = $this->calculateFuel($location, $sale->location);
                        if ($margin > $best && $fuel < 50) {
                            $destination = $sale->location;
                            $best = $margin;
                            $best_good = $good;
                            $best_fuel = $fuel;
                            print("fuel is " . $fuel . " and margin is " . $margin ."\r\n");
                        }
                    }
                }

            }
            return ['destination' => $destination, 'good' => $best_good , 'margin' => $best, 'fuel' => $best_fuel ];
        }

    }

    public function calculateFuel($location, $destination)
    {
        $loc = Location::find($location);
        $dest = Location::find($destination);
        $a = abs($loc->x - $dest->x);
        $b = abs($loc->y - $dest->y);

        $fuel = intval(ceil(sqrt(($a * $a) + ($b * $b))) / 5) + 2;
        return $fuel ;
    }
}