<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-state-100">
    <div style="background: linear-gradient(to bottom, transparent, mistyrose); min-height: 100%; width: 100%; height: auto; position: fixed; top: 0; left: 0;"></div>
    <div class="w-full md:w-9/12 mx-auto mt-10" style="position: relative;">
        <div class="mb-2">
            <h1 class="text-slate-900 text-3xl">Auto booking</h1>
        </div>
        <div class="mb-2 flex justify-center">
            <h3 class="text-2xl">The task of obtaining data from the database on car reservations by company employees</h3>
        </div>
        <div class="mb-2">
            <a href="{{ url('/filter')}}" class="btn btn-lg btn-info">
                Go to task
            </a>
        </div>
    </div>

    @livewireScripts
</body>
</html>