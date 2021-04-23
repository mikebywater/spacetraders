<!-- Planets -->
<div class="block">
    <div class="block-header overflow-hidden">
        <h2 class="block-title visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">Planets</h2>
    </div>
    <div class="block-content">
        @foreach($planets as $planet)
            <div class="row items-push overflow-hidden">
                <div class="col-xs-4 text-center visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="100">
                    <div class="js-pie-chart pie-chart" data-percent="100" data-line-width="20" data-size="45" data-bar-color="rgba({{$planet->x + $planet->y}}, {{$planet->x}}, {{$planet->y}}, .2)" data-track-color="rgba({{$planet->x +50 + $planet->y + 50}}, {{$planet->x + 50}}, {{$planet->y}}, 10)">
                        <span class="font-s16 font-w600"></span>
                    </div>
                </div>

                <div class="col-xs-8 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="400">
                    <div class="text-uppercase font-w600 text-white-op">{{$planet->id}}</div>
                    <div class="font-s36 font-w300">{{$planet->name}}</div>
                </div>
            </div>
        @endforeach
        <div class="row items-push overflow-hidden">
            <div class="col-xs-4 text-center visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="200">
                <div class="js-pie-chart pie-chart" data-percent="100" data-line-width="22" data-size="50" data-bar-color="rgba(255, 255, 255, .2)" data-track-color="#3673cc">
                    <span class="font-s16 font-w600"></span>
                </div>
            </div>
            <div class="col-xs-8 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="500">
                <div class="text-uppercase font-w600 text-white-op">Earth</div>
                <div class="font-s36 font-w300">1.62 <span class="font-s16 font-w400 text-crystal">L.Y.</span></div>
            </div>
        </div>
        <div class="row items-push overflow-hidden">
            <div class="col-xs-4 text-center visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="300">
                <div class="js-pie-chart pie-chart" data-percent="100" data-line-width="30" data-size="70" data-bar-color="rgba(255, 255, 255, .2)" data-track-color="#f7c880">
                    <span class="font-s16 font-w600"></span>
                </div>
            </div>
            <div class="col-xs-8 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="600">
                <div class="text-uppercase font-w600 text-white-op">Saturn</div>
                <div class="font-s36 font-w300">2.22 <span class="font-s16 font-w400 text-crystal">L.Y.</span></div>
            </div>
        </div>
    </div>
</div>
<!-- END Planets -->