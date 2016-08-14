@section('title')
{{'Unsupported Browser'}}
@stop

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="error-page">
                <div class="code">
                    <a href="http://www.getfirefox.com" target="_blank"><img src="{{URL::asset('assets/images/firefox.png')}}"></a>
                    <a href="http://www.google.com/chrome" target="_blank"><img src="{{URL::asset('assets/images/chrome.png')}}"></a>
                    <a href="http://www.opera.com/" target="_blank"><img src="{{URL::asset('assets/images/opera.png')}}"></a>
                    <a href="https://www.apple.com/safari" target="_blank"><img src="{{URL::asset('assets/images/safari.png')}}"></a>
                </div>
                <p class="sub">Oopss, you're using a very old browser. Please consider to upgrade your browser to be a modern one (Firefox, Chrome, Opera, IE9+, Safari, etc).</p>
            </div>
        </div>
    </div>
@stop