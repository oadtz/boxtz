<!DOCTYPE html>
<html lang="en" ng-app="oadtz">
    <head>
        <meta charset="utf-8">
        <title>Sign Up</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Thanapat Pirmphol">
        @include('_layouts.styles')
        <link href="{{URL::asset('assets/css/signin.css')}}" rel="stylesheet">
    </head>

    <body ng-controller="SignupController" ng-cloak>        
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
                                    <div id="signup">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Sign Up</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-xs-12" ng-show="signupForm.$success">
                                                <alert type="'success'">
                                                    <div class="media">
                                                        <i class="fa fa-check-circle pull-left" style="font-size:60px"></i>
                                                        <div class="media-body">
                                                            <h2 class="media-heading">Success!</h2>
                                                            <p class="lead">You have been signed up. Now, go to <a href="{{URL::to('signin')}}">Sign In</a>.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <form name="signupForm" class="form-horizontal form-bordered form-control-borderless display-none" ng-submit="performSignup()" ng-hide="signupForm.$success" style="display: block;">
                                                <div class="form-group" ng-class="{'has-error': signupForm.errors.username}">
                                                    <div class="col-xs-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                            <input type="text" ng-model="user.username" ng-disabled="signupForm.$waiting" class="form-control input-lg" placeholder="Username">
                                                        </div>
                                                        <span class="help-block" ng-show="signupForm.errors.username">@{{signupForm.errors.username[0]}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group" ng-class="{'has-error': signupForm.errors.email}">
                                                    <div class="col-xs-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                            <input type="text" ng-model="user.email" ng-disabled="signupForm.$waiting" class="form-control input-lg" placeholder="Email">
                                                        </div>
                                                        <span class="help-block" ng-show="signupForm.errors.email">@{{signupForm.errors.email[0]}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group" ng-class="{'has-error': signupForm.errors.password}">
                                                    <div class="col-xs-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                                                            <input type="password" ng-model="user.password" ng-disabled="signupForm.$waiting" class="form-control input-lg" placeholder="Password">
                                                        </div>
                                                        <span class="help-block" ng-show="signupForm.errors.password">@{{signupForm.errors.password[0]}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group" ng-class="{'has-error': signupForm.errors.password2}">
                                                    <div class="col-xs-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                                                            <input type="password" ng-model="user.password2" ng-disabled="signupForm.$waiting" class="form-control input-lg" placeholder="Verify Password">
                                                        </div>
                                                        <span class="help-block" ng-show="signupForm.errors.password2">@{{signupForm.errors.password2[0]}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <!--div class="col-xs-12">
                                                        <p class="align-center">
                                                            <label>
                                                                <input type="checkbox" ng-model="user.agreement_accepted">
                                                                I accepted <a href="#tnc-modal" data-toggle="modal">Terms &amp; Conditions</a>
                                                            </label>
                                                        </p>
                                                    </div-->
                                                    <div class="col-xs-12 text-center">
                                                        <span class="input-group-btn">
                                                            <button ng-disabled="signupForm.$waiting" class="btn btn-lg btn-primary" type="submit">Sign Up</button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <p class="align-center">
                                                            <small>Already have an account?</small> 
                                                            <a href="{{URL::to('signin')}}"><small>Go Sign In</small></a>
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
                src: '{{URL::asset('assets/images/night.jpg')}}'
            })
            ('overlay', {
                src: '{{URL::asset('assets/images/overlays/10.png')}}'
            });
        });
        </script>

    </body>
</html>