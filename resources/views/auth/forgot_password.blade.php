@extends('layouts.auth')

@section('content')
    <div class="row body">
        <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="margin-top-20">
                @include('alert::alert')
            </div>

            <h2 class="page-header">Forgot Password</h2>

            <form action="/auth/password/email" accept-charset="UTF-8" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <section class="form-group {{ form_error_class('email', $errors) }}">
                    <label>Enter your Email Address</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="email" placeholder="Please insert your Email" value="{{ old('email') }}">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    </div>
                    {!! form_error_message('email', $errors) !!}
                </section>

                <div class="row">
                    <div class="col-md-6">
                        <div class="note">
                            <a class="btn btn-link padding-left-0" href="{{ route('login') }}">
                                I remembered my password!
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-flat btn-submit pull-right">
                            <i class="fa fa-refresh"></i> Send Instructions
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
