<!-- Planets -->
<div class="block">
    <div class="block-header overflow-hidden">
        <h2 class="block-title visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">Moons / Other</h2>
    </div>
    <div class="block-content">
        @foreach($moons as $moon)
            <div class="row items-push overflow-hidden">
                <div class="col-xs-4 text-center visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="100">
                    <div class="js-pie-chart pie-chart" data-percent="100" data-line-width="20" data-size="45" data-bar-color="rgba({{($moon->x + $moon->y) * 0.5}}, {{$moon->x * 0.5}}, {{$moon->y * 0.5}}, .2)" data-track-color="rgba({{$moon->x * 0.5 +50 + $moon->y * 0.5 + 50}}, {{$moon->x * 0.5 + 50}}, {{$moon->y *0.5}}, 10)">
                        <span class="font-s16 font-w600"></span>
                    </div>
                </div>

                <div class="col-xs-8 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="400">
                    <div class="text-uppercase font-w600 text-white-op">{{$moon->id}}</div>
                    <div class="font-s36 font-w300">{{$moon->name}}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<!-- END Planets -->