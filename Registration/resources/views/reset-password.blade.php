<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        Reset Password
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            {{ \Session::get('error') }}
                        </div>
                        @endif

                        <form action="{{ route('processResetPassword') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{$token}}">
                            <div class="form-group">
                                <label for="new_password">Password</label>
                                <input type="new_password" class="form-control" id="new_password" name="new_password"  placeholder="Enter  New Password">

                                @error('new_password')
                                <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Confirm Password</label>
                                <input type="confirm_password" class="form-control" id="confirm_password" name="confirm_password"  placeholder="Enter  Confirm Password">
                                @error('new_password')
                                <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                            </div>
                          
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary" value="Update Password">Reset Password</button>
                               
                            </div>
                        </form>
                       <div class="text-center small"><a href="{{route('userLogin')}}">Click Here to Login</a></div>
          
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>