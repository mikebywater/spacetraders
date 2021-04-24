@extends('layouts.master')
@section('content')
<div id="page-container" class="modern-sf">
    <!-- Header -->
    <header id="page-header">
        <div class="h3 text-right pull-right hidden-xs">
            <div class="text-crystal font-w300">{{$user->username}}</div>
            <div class="text-success animated infinite pulse pull-right">[LIVE]</div>
        </div>
        <h1 class="h3 font-w200">
            <span class="text-crystal">//</span> <a class="link-sf font-w300" href="index.html">SPACE_TRADERS</a>
        </h1>
    </header>
    <!-- END Header -->

    <!-- Main Content -->
    <main id="page-main">
        <!-- Columns -->
        <div class="row">
            <!-- Animated Circles Column -->
            <div class="col-lg-6 col-lg-push-3 overflow-hidden push-20">
                <div class="circles push-50">
                    <div class="visibility-hidden" data-toggle="appear" data-class="animated fadeIn">
                        <span class="circle circle-0"></span>
                    </div>
                    <div class="visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="100">
                        <span class="circle circle-1"></span>
                    </div>
                    <div class="visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="200">
                        <span class="circle circle-2"></span>
                    </div>
                    <div class="visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="300">
                        <span class="circle circle-3"></span>
                    </div>
                    <div class="visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="400">
                        <span class="circle circle-4"></span>
                    </div>
                    <div class="visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="500">
                        <span class="circle circle-5"></span>
                    </div>
                    <div class="visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="600">
                        <span class="circle circle-6"></span>
                    </div>
                    <div class="visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="800">
                        <span class="circle circle-over-1 hidden-xs"><span data-toggle="countTo" data-to="{{$user->loan}}" data-speed="1000"></span></span>
                        <span class="circle circle-over-2 hidden-xs"></span>
                        <span class="circle circle-over-3 hidden-xs"></span>
                    </div>
                    <span class="circle circles-main-content visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="100">
                                <span data-toggle="countTo" data-to="{{($user->credits)}}" data-speed="600"></span><br>
                                <span class="text-crystal">Credits</span>
                            </span>
                </div>
                <div class="row">
                    <div class="col-xs-6 visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="100">
                        <button class="btn btn-xl btn-block btn-sf push-10">DATA_DRIVE</button>
                    </div>
                    <div class="col-xs-6 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="100">
                        <button class="btn btn-xl btn-block btn-sf push-10">PO_DATABASE</button>
                    </div>
                    <div class="col-xs-6 visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="500">
                        <button class="btn btn-xl btn-block btn-sf">ACTIVE_ROUTE</button>
                    </div>
                    <div class="col-xs-6 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="500">
                        <button class="btn btn-xl btn-block btn-sf">AUTO_PILOT</button>
                    </div>
                </div>
            </div>
            <!-- END Animated Circles Column -->

            <!-- Left Column -->
            <div class="col-sm-6 col-lg-3 col-lg-pull-6">

                @include('partials.planets')

                @include('partials.moons')
            </div>
            <!-- END Left Column -->

            <!-- Right Column -->
            <div class="col-sm-6 col-lg-3">
                <!-- HQ_COMS -->
                   @include('partials.ships')
                <!-- END HQ_COMS -->

                <!-- POS_TRACKING -->
                <div class="block">
                    <div class="block-header overflow-hidden">
                        <h2 class="block-title visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">POS_TRACKING</h2>
                    </div>
                    <div class="block-content block-content-full overflow-hidden">
                        <div class="font-w600 text-white-op push-5 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="100">X: 95</div>
                        <div class="progress visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="100">
                            <div class="progress-bar progress-bar-sf progress-bar-striped active" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%"></div>
                        </div>
                        <div class="font-w600 text-white-op push-5 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="300">Y: 49</div>
                        <div class="progress visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="300">
                            <div class="progress-bar progress-bar-sf progress-bar-striped active" role="progressbar" aria-valuenow="49" aria-valuemin="0" aria-valuemax="100" style="width: 49%"></div>
                        </div>
                        <div class="font-w600 text-white-op push-5 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="500">Z: 59</div>
                        <div class="progress visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="500">
                            <div class="progress-bar progress-bar-sf progress-bar-striped active" role="progressbar" aria-valuenow="59" aria-valuemin="0" aria-valuemax="100" style="width: 59%"></div>
                        </div>
                        <div class="font-w600 text-white-op push-5 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" data-timeout="700">V: +60</div>
                        <div class="progress visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" data-timeout="700">
                            <div class="progress-bar progress-bar-sf progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
                <!-- END POS_TRACKING -->

                <!-- DATA_STREAM -->
                <div class="block">
                    <div class="block-header overflow-hidden">
                        <h2 class="block-title visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">DATA_STREAM</h2>
                    </div>
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-xs-4 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="100">
                                <div class="font-s12 text-white-op">AT1</div>
                                <div class="font-s18 text-success" data-toggle="countTo" data-to="148" data-speed="4000"></div>
                            </div>
                            <div class="col-xs-4 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="300">
                                <div class="font-s12 text-white-op">SR1</div>
                                <div class="font-s18 text-success" data-toggle="countTo" data-to="30" data-speed="4000"></div>
                            </div>
                            <div class="col-xs-4 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="500">
                                <div class="font-s12 text-white-op">AF1</div>
                                <div class="font-s18 text-success" data-toggle="countTo" data-to="123" data-speed="4000"></div>
                            </div>
                            <div class="col-xs-4 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="700">
                                <div class="font-s12 text-white-op">AT2</div>
                                <div class="font-s18 text-success" data-toggle="countTo" data-to="180" data-speed="4000"></div>
                            </div>
                            <div class="col-xs-4 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="900">
                                <div class="font-s12 text-white-op">SR2</div>
                                <div class="font-s18 text-success" data-toggle="countTo" data-to="680" data-speed="4000"></div>
                            </div>
                            <div class="col-xs-4 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" data-timeout="1100">
                                <div class="font-s12 text-white-op">AF2</div>
                                <div class="font-s18 text-success" data-toggle="countTo" data-to="15" data-speed="4000"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END DATA_STREAM -->
            </div>
            <!-- END Right Column -->
        </div>
        <!-- END Columns -->

    </main>
    <!-- END Main Content -->
</div>
@endsection
