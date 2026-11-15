@props(['name', 'label', 'options' => [], 'value' => null, 'placeholder' => null, 'required' => false])

<div>
    <label for="{{ $name }}" class="block text-xs font-semibold text-gray-600 uppercase mb-1">
        {{ $label }} @if ($required)<span class="text-red-500">*</span>@endif
    </label>
    <select id="{{ $name }}" name="{{ $name }}" @required($required)
            {{ $attributes->merge(['class' => 'w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none']) }}>
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optValue => $optLabel)
            <option value="{{ $optValue }}" @selected((string) old($name, $value) === (string) $optValue)>{{ $optLabel }}</option>
        @endforeach
    </select>
    @error($name)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>
