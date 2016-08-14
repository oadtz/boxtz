<!DOCTYPE html>
<html lang="en" ng-app="oadtz">
    <head>
        <meta charset="utf-8">
        <title>Sign In</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Thanapat Pirmphol">
        @include('_layouts.styles')
        <link href="{{URL::asset('assets/css/signin.css')}}" rel="stylesheet">
    </head>

    <body ng-controller="SigninController" ng-cloak>  
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-push-4 col-xs-10 col-xs-push-1 col-sm-8 col-sm-push-2">
                    <h1 class="brand brand-big align-center">
                        <img src="{{URL::asset('assets/images/logo.png')}}">
                    </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-md-push-4 col-xs-10 col-xs-push-1 col-sm-8 col-sm-push-2">
                    <section id="middle">

                        <div id="content" class="signin-page">

                            <div class="panel-group" id="signin-page">
                            
                                <div class="panel panel-outline panel-no-padding">
                                    <div id="signin">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Sign In</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-xs-12" ng-show="signinForm.$failed">
                                                <alert type="'danger'">
                                                    <div class="media">
                                                        <i class="fa fa-exclamation-circle pull-left" style="font-size:60px"></i>
                                                        <div class="media-body">
                                                            <h2 class="media-heading">Login Failed!</h2>
                                                            <p class="lead">Please check your login information.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12" ng-show="signinForm.$success">
                                                <alert type="'success'">
                                                    <div class="media">
                                                        <i class="fa fa-check-circle pull-left" style="font-size:60px"></i>
                                                        <div class="media-body">
                                                            <h2 class="media-heading">Success!</h2>
                                                            <p class="lead">You have been signed in. You're being redirected to <a href="{{URL::to('/')}}">Home</a>.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <form name="signinForm" class="form-horizontal form-bordered form-control-borderless display-none" ng-submit="performSignin()" ng-hide="signinForm.$success" style="display: block;">
                                                
                                                <div class="form-group" ng-class="{'has-error': signinForm.errors.login}">
                                                    <div class="col-xs-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                            <input type="text" ng-model="user.login" ng-disabled="signinForm.$waiting" class="form-control input-lg" placeholder="Username or Email">
                                                        </div>
                                                        <span class="help-block" ng-show="signinForm.errors.login">@{{signinForm.errors.login[0]}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group" ng-class="{'has-error': signinForm.errors.password}">
                                                    <div class="col-xs-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                                                            <input type="password" ng-model="user.password" ng-disabled="signinForm.$waiting" class="form-control input-lg" placeholder="Password">
                                                        </div>
                                                        <span class="help-block" ng-show="signinForm.errors.password">@{{signinForm.errors.password[0]}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <p class="align-center">
                                                            <label>
                                                                <input type="checkbox" ng-model="user.remember_flag">
                                                                Keep me signed in
                                                            </label>
                                                        </p>
                                                    </div>
                                                    <div class="col-xs-12 text-center">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-lg btn-primary" type="submit">Sign In</button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <p class="align-center">
                                                            <small>Don't have an account?</small> 
                                                            <a href="{{URL::to('signup')}}"><small>Sign up now for free.</small></a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </form>
                                        </div><!-- /.panel-body -->
                                    </div><!-- /.panel-collapse -->
                                </div><!-- /.panel -->
                            
                            
                            </div><!-- /.panel-group -->
                            


                        </div><!-- /#content -->

                    </section>
                </div><!-- /.col-md-10 -->

            </div><!-- /.row -->
        </div><!-- /.container -->

        @include('_layouts.scripts')
        <script src="{{URL::asset('assets/vendor/js/jquery.vegas.js')}}"></script>
        <script>
        $(function () {
            $.vegas({
                src: '{{URL::asset('assets/images/city.jpg')}}'
            })
            ('overlay', {
                src: '{{URL::asset('assets/images/overlays/10.png')}}'
            });
        });
        </script>
    </body>
</html>