<!DOCTYPE html>
<html lang="en">

@include('layouts.head')

<body>
    @include('layouts.header')

    <div class="container mt-4">
        @yield('content')
    </div>

    @include('layouts.foot')
</body>

</html>
