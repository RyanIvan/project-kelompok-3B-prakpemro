@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null])

<div>
    <label for="{{ $name }}" class="block text-xs font-semibold text-gray-600 uppercase mb-1">
        {{ $label }} @if ($required)<span class="text-red-500">*</span>@endif
    </label>
    <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" @required($required)
           value="{{ $type === 'password' ? '' : old($name, $value) }}"
           {{ $attributes->merge(['class' => 'w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none']) }}>
    @if ($hint)<p class="text-xs text-gray-500 mt-1">{{ $hint }}</p>@endif
    @error($name)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>
