@php 
  $menus = [
            'dashboard' => [
                'txt' => __('site.title.dashboard'),
                'href' => route('dashboard'),
                'icon' => 'fa-calendar-alt'
            ],
            'slider' => [
                'txt' => __('site.title.slider'),
                'href' => route('slider'),
                'icon' => 'fa-calendar-alt'
            ],
            'menu' => [
                'txt' => 'Quản lý Menu',
                'href' => route('dashboard'),
                'icon' => 'fa-calendar-alt'
            ],
            'category' => [
                'txt' => 'Quản lý Danh mục',
                'href' => route('dashboard'),
                'icon' => 'fa-calendar-alt'
            ],
  ]
@endphp
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <li class="nav-header">Chức năng</li>
    @foreach ($menus as $k => $menu)
      @php
        $active = (\Request::route()->getName() == $k) ? 'active' : '';
      @endphp
      <li class="nav-item">
        <a href="{{ $menu['href'] }}" class="nav-link {{ $active }}">
          <i class="nav-icon far {{ $menu['icon']}}"></i>
          <p>
            {{ $menu['txt'] }}
          </p>
        </a>
      </li>
    @endforeach
  </ul>
</nav>