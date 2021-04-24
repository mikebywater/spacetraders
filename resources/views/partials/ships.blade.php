<div class="block">
    <div class="block-header overflow-hidden">
            <h2 class="block-title visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">SHIPS</h2>
    </div>
    <div class="block-content overflow-hidden">
        <div class="row items-push">
            @foreach($ships as $ship)
                <div class="col-xs-6 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="300">
                    <div class="font-s24 font-w300 text-white-op">{{substr($ship->id, -4)}} [<span class="text-success">{{$ship->status}}</span>]</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
