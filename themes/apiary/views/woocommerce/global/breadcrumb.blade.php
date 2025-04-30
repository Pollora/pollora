<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <nav aria-label="Breadcrumb" class="border-b border-gray-200">
        <ol role="list" class="flex items-center space-x-4 px-0 py-4 list-none">
            @foreach ( $breadcrumb as $key => $crumb )
                @if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 )
                    <li>
                        <div class="flex items-center">
                            <a href="{{ $crumb[1] }}" class="mr-4 text-sm font-medium text-gray-900">
                                {{ $crumb[0] }}
                            </a>
                            <svg viewBox="0 0 6 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                 class="h-5 w-auto text-gray-300">
                                <path d="M4.878 4.34H3.551L.27 16.532h1.327l3.281-12.19z" fill="currentColor"></path>
                            </svg>
                        </div>
                    </li>
                @else
                    <li class="text-sm">
                        <span aria-current="page" class="font-medium text-gray-500 hover:text-gray-600">
                            {{ $crumb[0] }}
                        </span>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>