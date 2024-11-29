<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>

<style>
    body{
        background-image: url('{{ asset('images/DSC_0224.JPG') }}');
        background-size: cover;
        background-repeat: no-repeat;  
        background-color:  #6EACDA;
        background-position: center;
        background-blend-mode: multiply;
        display: flex;
        height: 100vh;
        align-items: center;
        justify-content: center;

    }

    .form-container {
        max-width: 500px;
        margin: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: auto;
        background-color:  rgba(248, 249, 250, 0.5);
        border: 1px solid #ced4da;
        border-radius: .25rem;
        padding: 2rem; 
    }
</style>
<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="form-container">
            <h2 class="text-center mb-4">Login</h2>
            <form action="{{ route('login') }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="login" class="mb-1">NIM</label>
                <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" aria-describedby="nimemialHelp" required autofocus>
                <div id="nimemailHelp" class="form-text mt-2" style="color: #00328E;">We'll never share your nim with anyone else.</div>
                @error('login')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
    

            <div class="mb-4">
                <label for="exampleInputPassword" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="exampleInputPassword">
            </div>

            <button type="submit" class="btn btn-primary mb-4 w-100" style="background-color: #00328E;">Submit</button>
            </form>
            <span>Don't have account?<a href="{{ route('register') }}" class="ms-2 fw-bold" style="color: #00328E; text-decoration: none;">Register</a></span>
        </div>

    </div>
</body>
</html>