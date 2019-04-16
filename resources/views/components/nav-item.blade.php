@foreach ($navItems as $navItem)
    @if (isset($navItem['url']))
        <li class="nav-item">
            <a class="nav-link" href="{{ $navItem['url'] }}">{{ $navItem['name'] }}</a>
        </li>
    @else
        @component('components.nav-item-dropdown', ['navItem' => $navItem])
        @endcomponent
    @endif
@endforeach
