<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar mt-3" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">

                @can('admin.dashboard')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.dashboard')}}" aria-expanded="false">
                        <i data-feather="home" class="feather-icon text-cyan"></i>
                        <span class="hide-menu">@lang('Dashboard')</span>
                    </a>
                </li>
                @endcan


{{--                @can('admin.staff')--}}
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.staff')}}" aria-expanded="false">
                        <i data-feather="users" class="feather-icon text-primary"></i>
                        <span class="hide-menu">@lang('Role Permission')</span>
                    </a>
                </li>
{{--                @endcan--}}

                <li class="nav-small-cap"><span class="hide-menu">@lang('Manage')</span></li>
                @can('admin.ipblock')
                    <li class="sidebar-item {{menuActive(['admin.ipblock*'],3)}}">
                        <a class="sidebar-link" href="{{ route('admin.ipblock') }}" aria-expanded="false">
                            <i class="fas fa-lock text-success"></i>
                            <span class="hide-menu">@lang('IP Block List')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.tags')
                <li class="sidebar-item {{menuActive(['admin.tags*'],3)}}">
                    <a class="sidebar-link" href="{{ route('admin.tags') }}" aria-expanded="false">
                        <i class="fas fa-tags text-warning"></i>
                        <span class="hide-menu">@lang('Manage Tags')</span>
                    </a>
                </li>
                @endcan

                @can('admin.domain')
                        <li class="sidebar-item {{menuActive(['admin.domain*'],3)}}">
                        <a class="sidebar-link" href="{{ route('admin.domain') }}" aria-expanded="false">
                            <i class="fas fa-globe text-info"></i>
                            <span class="hide-menu">@lang('Domain')</span>
                        </a>
                    </li>
                @endcan

                <li class="nav-small-cap"><span class="hide-menu">@lang('Manage Media')</span></li>


                @can('admin.wallpapers')
                    <li class="sidebar-item {{menuActive(['admin.wallpapers*'],3)}}">
                        <a class="sidebar-link" href="{{ route('admin.wallpapers') }}" aria-expanded="false">
                            <i class="fas fa-images text-danger"></i>
                            <span class="hide-menu">@lang('Wallpapers')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.ringtones')
                    <li class="sidebar-item {{menuActive(['admin.ringtones*'],3)}}">
                        <a class="sidebar-link" href="{{ route('admin.ringtones') }}" aria-expanded="false">
                            <i class="fas fa-bell text-warning"></i>
                            <span class="hide-menu">@lang('Ringtones')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.musics')
                    <li class="sidebar-item {{menuActive(['admin.musics*'],3)}}">
                        <a class="sidebar-link" href="{{ route('admin.musics') }}" aria-expanded="false">
                            <i class="fas fa-music text-success"></i>
                            <span class="hide-menu">@lang('Musics')</span>
                        </a>
                    </li>
                @endcan

                <li class="nav-small-cap"><span class="hide-menu">@lang('Controls')</span></li>
                @can('admin.basic-controls')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{route('admin.basic-controls')}}" aria-expanded="false">
                            <i class="fas fa-cogs text-cyan"></i>
                            <span class="hide-menu">@lang('Basic Controls')</span>
                        </a>
                    </li>


                    <li class="sidebar-item {{menuActive(['admin.plugin.config','admin.tawk.control','admin.fb.messenger.control','admin.google.recaptcha.control','admin.google.analytics.control'],3)}}">
                        <a class="sidebar-link" href="{{route('admin.plugin.config')}}" aria-expanded="false">
                            <i class="fa fa-plug text-yellow text-orange" aria-hidden="true"></i>
                            <span class="hide-menu">@lang('Plugin Configuration')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.color-settings')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{route('admin.color-settings')}}" aria-expanded="false">
                            <i class="fas fa-paint-brush text-info"></i>
                            <span class="hide-menu">@lang('Color Settings')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.language.index')
                    <li class="sidebar-item {{menuActive(['admin.language.create','admin.language.edit*','admin.language.keywordEdit*'],3)}}">
                        <a class="sidebar-link" href="{{  route('admin.language.index') }}"
                           aria-expanded="false">
                            <i class="fas fa-language text-orange"></i>
                            <span class="hide-menu">@lang('Manage Language')</span>
                        </a>
                    </li>
                @endcan

                <li class="nav-small-cap"><span class="hide-menu">@lang('Theme Settings')</span></li>

                @can('admin.logo-seo')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{route('admin.logo-seo')}}" aria-expanded="false">
                            <i class="fas fa-image text-purple"></i><span
                                class="hide-menu">@lang('Manage Logo & SEO')</span>
                        </a>
                    </li>
                @endcan
                @can('admin.breadcrumb')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{route('admin.breadcrumb')}}" aria-expanded="false">
                            <i class="fas fa-file-image text-danger"></i><span
                                class="hide-menu">@lang('Manage Breadcrumb')</span>
                        </a>
                    </li>
                @endcan

                <li class="list-divider"></li>
                <li class="nav-small-cap "><small class="hide-menu  text-center">@lang('Version 4.0')</small></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
