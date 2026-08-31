<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.frontend.style')
</head>

<body>
    @include('layouts.frontend.header')

    @yield('content')

    @include('layouts.frontend.footer')
</body>

</html>