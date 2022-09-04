@php 
$menus = [
            'dashboard' => [
                'txt'       => $siteTitles['admin.dashboard'],
                'href'      => route('admin.dashboard'),
                'active'    => ['admin.dashboard'],
                'icon'      => 'nav-icon fas fa-tachometer-alt'
            ],
            'slider'  => [
                'txt'       => $siteTitles['admin.slider.index'],
                'href'      => route('admin.slider.index'),
                'active'    => ['admin.slider.index', 'admin.slider.form'],
                'icon'      => 'nav-icon far fa-image'
            ],
];
    $routeName  = \Request::route()->getName();
@endphp
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <li class="nav-header">Chức năng</li>
    @foreach ($menus as $k => $menu)
      @php
        $active = in_array($routeName, $menu['active']) ? 'active' : '';
      @endphp
      <li class="nav-item">
        <a href="{{ $menu['href'] }}" class="nav-link {{ $active }}">
          <i class="{{ $menu['icon']}}"></i>
          <p>
            {{ $menu['txt'] }}
          </p>
        </a>
      </li>
    @endforeach
  </ul>
</nav>