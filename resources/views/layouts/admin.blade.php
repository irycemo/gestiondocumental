<x-app-layout>

    <div class="relative max-h-screen md:flex">

        {{-- Sidebar --}}
        <div id="sidebar" class="z-50 bg-white w-64 absolute inset-y-0 left-0 transform -translate-x-full transition duration-200 ease-in-out md:relative md:translate-x-0">

            {{-- Header --}}
            <div class="w-100 flex-none bg-white border-b-2 border-b-grey-200 flex flex-row p-5 pr-0 justify-between items-center h-20 ">

                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="mx-auto focus:outline-rojo focus:outline-offset-2">

                    <img class="h-16" src="{{ asset('storage/img/logo2.png') }}" alt="Logo">

                </a>

                {{-- Side Menu hide button --}}
                <button  type="button" title="Cerrar Menú" id="sidebar-menu-button" class="md:hidden mr-2 inline-flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">

                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>

            </div>

            {{-- Nav --}}
            <nav class="p-4 text-rojo overflow-y-auto h-menu">

                {{-- <p class="uppercase text-md text-rojo mb-4 tracking-wider">Administración</p> --}}

                @can('Lista de roles')

                    <x-nav-dropdown>

                        <x-slot name="head">

                            <a href="{{ route('roles') }}" class="capitalize font-medium text-md flex items-center w-full py-2 px-4 focus:outline-rojo focus:outline-offset-2">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>

                                Roles

                            </a>

                        </x-slot>

                        <x-slot name="body">

                            <a href="{{ route('permisos') }}" class="hover:bg-gray-100 hover:text-red-600 transition ease-in-out duration-500 rounded-full capitalize font-medium text-md flex items-center w-full py-2 px-4 focus:outline-rojo focus:outline-offset-2">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>

                                Permisos

                            </a>

                        </x-slot>

                    </x-nav-dropdown>

                @endcan

                @can('Lista de usuarios')

                    <a href="{{ route('usuarios') }}" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>

                        Usuarios
                    </a>

                @endcan

                @can('Lista de oficinas')

                    <a href="{{ route('oficinas') }}" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>

                        Oficinas
                    </a>

                @endcan

                @can('Lista de dependencias')

                    <a href="{{ route('dependencias') }}" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>

                        Dependencias
                    </a>

                @endcan

                @can('Lista de entradas')

                    <a href="{{ route('entradas') }}" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                        </svg>

                        Entradas
                    </a>

                @endcan

                @can('Lista de seguimientos')

                    <a href="{{ route('seguimientos') }}" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                        </svg>

                        Seguimientos
                    </a>

                @endcan

                @can('Lista de conclusiones')

                    <a href="{{ route('conclusiones') }}" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>

                        Conclusiones
                    </a>

                @endcan

                <a href="{{ route('manual') }}" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>

                    Manual de Usuario
                </a>

                @if (auth()->user()->hasRole('Administrador'))

                    <a href="{{ url('log-viewer') }}" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        Logs
                    </a>

                @endif

            </nav>

        </div>

        {{-- Content --}}
        <div class="flex-1 flex-col flex max-h-screen overflow-x-auto min-h-screen">

            {{-- Header --}}
            <div class="w-100  bg-white border-b-2 border-b-grey-200 flex-none flex flex-row p-5 justify-between items-center h-20">

                <!-- Mobile menu button-->
                <div class="flex items-center">

                    <button  type="button" title="Abrir Menú" id="mobile-menu-button" class="md:hidden inline-flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">

                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>

                    </button>

                </div>

                {{-- Logo --}}
                <p x-show.transition.in.duration.1000ms.out.duration.200msw="!open_side_menu"  class="font-semibold text-2xl text-rojo">{{ env('APP_NAME') }}</p>

                <!-- Profile dropdown -->
                <div class="ml-3 relative z-50" x-data="{ open_drop_down:false }">

                    <div>

                        <button x-on:click="open_drop_down=true" type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu" aria-expanded="false" aria-haspopup="true">

                            <span class="sr-only">Abrir menú de usuario</span>

                            <img class="h-10 w-10 rounded-full" src="{{Auth::user()->profile_photo_url}}" alt="{{ Auth::user()->name }}" id="nav-profile">

                        </button>

                    </div>

                    <div x-show="open_drop_down" x-on:click.away="open_drop_down=false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Mi Perfil</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none" role="menuitem">Cerrar Sesión</button>

                        </form>

                    </div>

                </div>

            </div>

            <div class="bg-gray-100 p-3 flex-1 overflow-y-auto  md:border-l-2 border-l-grey-200">

                @yield('content')

            </div>

        </div>

    </div>

    <script>

        const btn_close = document.getElementById('sidebar-menu-button');
        const btn_open = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');

        btn_open.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        btn_close.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        /* Change nav profile image */
        window.addEventListener('nav-profile-img', event => {

            document.getElementById('nav-profile').setAttribute('src', event.detail.img);

        });

    </script>

</x-app-layout>
