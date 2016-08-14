@section('title')
{{'Page not found'}}
@stop

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="error-page">
                <div class="code">404</div>
                <p class="sub">Oopss, we are sorry but the page you are looking for was not found.</p>
                <p class="align-center">
                    <a href="{{URL::to('/')}}" class="back">
                        <span class="fa-stack fa-lg">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-chevron-left fa-stack-1x fa-inverse"></i>
                        </span>
                        Back to Dashboard
                    </a>
                </p>
            </div>
        </div>
    </div>
@stop