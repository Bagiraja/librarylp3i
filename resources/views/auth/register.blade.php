<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
    <style>
        body{
            background-image: url('{{ asset('images/perpuslp3i.jpg')  }}');
            background-color: #0d6efd;
            background-size: cover;
            background-position: center;
            background-blend-mode: multiply;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
      
        .form-container{
            height: auto;
            background-color:  rgba(248, 249, 250, 0.5);
            width: 420px;
            display: flex;
            border: 1px solid #ced4da;
            justify-content: center;
            flex-direction: column;
            margin: auto;
            padding: 2rem;
            border-radius: .25rem;
        }
    </style>
<body>
  
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="form-container">
            <h2 class="mb-4 text-center ">Register</h2>
            <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-2">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="mb-2">
                <label for="nim" class="form-label">Nim</label>
                <input type="number" class="form-control" id="nim" name="nim" required>
            </div>
            <div class="mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div class="mb-2">
                <label for="password_confirmation" class="form-label">Confirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
            </div>

            <button type="submit" class="btn w-100 mt-3 mb-2 text-white" style="background-color: #00328E;">Submit</button>
            </form>
            <span>Already have an account?<a href="{{ route('login') }}" class="ms-2 fw-bold" style="color: #00328E; text-decoration: none;">Login</a></span>
        </div>

    </div>
</body>
</html>