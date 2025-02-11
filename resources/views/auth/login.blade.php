<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KuPinjam</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(-45deg,  #5C27FE,#C165DD,#1de5e2,#2AFEB7);
            background-size: 400% 400%;
            animation: gradient 20s ease infinite;
            font-family: 'Roboto', sans-serif;
            height: 100vh;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .card {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .card h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
        }
        .btn-custom {
            background-color: #333333;
            color: #ffffff;
            font-weight: 500;
            border-radius: 8px;
        }
        .btn-custom:hover {
            background-color: #555555;
        }
        .btn-custom:active {
            background-color: #111111;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <div class="card shadow">
        <form action="{{route('authenticate')}}" method="post">
            <h1>Login KuPinjam</h1>
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control p-3 @error('email') is-invalid @enderror" placeholder="Masukan Email Anda" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control p-3 @error('password') is-invalid @enderror" placeholder="Masukan Password Anda" value="{{ old('password') }}" required>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <button type="submit" class="btn btn-custom w-100 py-2">Masuk</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
