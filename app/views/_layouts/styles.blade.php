
		<meta name="socket" content="{{Config::get('pusher.app_key')}}">
        <link rel="shortcut icon" href="{{URL::asset('favicon.ico')}}" type="image/x-icon">
		<link rel="icon" href="{{URL::asset('favicon.ico')}}" type="image/x-icon">
		<base href="{{URL::to('/')}}">
@if(App::environment() == 'dev')
		<!-- Stylesheets -->
		<!--link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'-->
		<link href="{{URL::asset('assets/vendor/css/bootstrap.min.css')}}" rel="stylesheet">
		<link href="{{URL::asset('assets/vendor/css/font-awesome.min.css')}}" rel="stylesheet">
		<link href="{{URL::asset('assets/vendor/css/ng-grid.min.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/vendor/css/loading-bar.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/vendor/css/select2.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/vendor/css/jquery.growl.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/vendor/css/select2-bootstrap.css')}}" rel="stylesheet">
		<link href="{{URL::asset('assets/vendor/css/metroboard.css')}}" rel="stylesheet">

@else
        <link href="{{URL::asset('assets/css/styles.css')}}" rel="stylesheet">
@endif
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<style>
		[ng\:cloak], [ng-cloak], .ng-cloak {
  			display: none !important;
		}
		</style>