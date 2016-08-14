@if(App::environment() == 'dev')
        <!-- Javascript -->
        <script src="{{URL::asset('assets/vendor/js/pusher.min.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/jquery.min.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/select2.min.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/jquery.growl.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/angular.min.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/angular-animate.min.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/angular-resource.min.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/angular-select2.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/ui-utils.min.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/loading-bar.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/js/ui-bootstrap-tpls.min.js')}}"></script>
        <script src="{{URL::asset('assets/js/app.services.js')}}"></script>
        <script src="{{URL::asset('assets/js/app.js')}}"></script>
        <script src="{{URL::asset('assets/js/app.controllers.js')}}"></script>
@else
        <script src="{{URL::asset('assets/js/scripts.min.js')}}"></script>
@endif