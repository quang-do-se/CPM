<li class="nav-item dropdown">
    <a id="{{$navItem['name']}}" class="nav-link dropdown-toggle" href="#" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        {{ $navItem['name'] }} <span class="caret"></span>
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        @foreach ($navItem['dropdownItems'] as $dropdownItem)
            <a class="dropdown-item" href="{{$dropdownItem['url']}}">{{ $dropdownItem['name'] }}</a>
        @endforeach
    </div>
</li>
