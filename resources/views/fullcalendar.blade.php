@extends('main')

@section('title', 'Calendar')

@section('content')
<!--Content right-->
<div class="col-sm-9 col-xs-12 content pt-3 pl-0">
    <h5 class="mb-0" ><strong>Full Calendar</strong></h5>
    <span class="text-secondary">Dashboard <i class="fa fa-angle-right"></i> fullcalendar</span>
    
    <div class="row mt-3">
        <div class="col-md-12 col-sm-12">
            <!--Full Calendar-->
            <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm lh-sm">
                <div class="email-msg">
                    
                    <div class="table-responsive" id="calendarFull"></div>

                </div>
            </div>
            <!--/Email messages-->

        </div>
    </div>

    <!--Footer-->
    <div class="row mt-5 mb-4 footer">
        <div class="col-sm-8">
            <span>&copy; All rights reserved 2019 designed by <a class="text-info" href="#">A-Fusion</a></span><br>
            <span>&copy; 2024 Modified by <a class="text-info" href="#">Kamal Sa'danah</a></span>
        </div>
        <div class="col-sm-4 text-right">
            <a href="#" class="ml-2">Contact Us</a>
            <a href="#" class="ml-2">Support</a>
        </div>
    </div>
    <!--Footer-->

</div>
    
@endsection