@foreach ($menus = dashboard_menu()->getAll() as $menu)
    @php $menu = apply_filters(BASE_FILTER_DASHBOARD_MENU, $menu); @endphp

    {{-- Verificação se o menu é exclusivo para superusuários --}}
    @if ($menu['id'] === 'cms-plugins-location' && (!auth()->check() || !auth()->user()->isSuperUser()))
        @continue
    @endif

    <li class="nav-item @if ($menu['active']) active @endif" id="{{ $menu['id'] }}">
        <a href="{{ $menu['url'] }}" class="nav-link nav-toggle">
            <i class="{{ $menu['icon'] }}"></i>
            <span class="title">
                {{ !is_array(trans($menu['name'])) ? trans($menu['name']) : null }}
                {!! apply_filters(BASE_FILTER_APPEND_MENU_NAME, null, $menu['id']) !!}
            </span>
            @if (isset($menu['children']) && count($menu['children']))
                <span class="arrow @if ($menu['active']) open @endif"></span>
            @endif
        </a>

        {{-- Verifica se o menu possui submenus e os exibe --}}
        @if (isset($menu['children']) && count($menu['children']))
            <ul class="sub-menu @if (!$menu['active']) hidden-ul @endif">
                @foreach ($menu['children'] as $item)
                    {{-- Verificação específica para o submenu "cms-plugins-project" --}}
                    @if ($item['id'] === 'cms-plugins-re-feature' && (!auth()->check() || !auth()->user()->hasPermission('view_project')))
                        @continue
                    @endif
                    
                    @if ($item['id'] === 'cms-plugins-real-estate-category' && (!auth()->check() || !auth()->user()->hasPermission('view_project')))
                        @continue
                    @endif
                    
                    @if ($item['id'] === 'cms-plugins-facility' && (!auth()->check() || !auth()->user()->hasPermission('view_project')))
                        @continue
                    @endif

                    <li class="nav-item @if ($item['active']) active @endif" id="{{ $item['id'] }}">
                        <a href="{{ $item['url'] }}" class="nav-link">
                            <i class="{{ $item['icon'] }}"></i>
                            {{ trans($item['name']) }}
                            {!! apply_filters(BASE_FILTER_APPEND_MENU_NAME, null, $item['id']) !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach
