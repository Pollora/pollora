{{--
 * Large button Blade component
 *
 * @package Theme\Apiary
 --}}
<button {{ $attributes->merge(['class' => 'w-full bg-primary border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors duration-150']) }}>
    {!! $slot !!}
</button>