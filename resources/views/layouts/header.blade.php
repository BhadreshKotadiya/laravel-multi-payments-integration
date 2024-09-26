<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('product.index') }}">
        <img src="{{ asset('assets/pizza.png') }}" alt="pizza" class="navbar-logo">
        Pizza Palooza
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cart.show') }}">
                    <div class="cart-icon-wrapper">
                        <img src="{{ asset('assets/cart.png') }}" alt="cart" height="30px" width="30px">
                        <span id="cart-count" class="badge badge-danger">
                            {{ session('cart') ? count(session('cart')) : '0' }}
                        </span>
                    </div>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
            @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
            <!-- Prime Version Button -->
            <li class="nav-item">
                <a class="btn btn-primary ml-2" href="{{ route('subscriptions.index') }}">Prime Version</a>
            </li>
        </ul>
    </div>
</nav>
