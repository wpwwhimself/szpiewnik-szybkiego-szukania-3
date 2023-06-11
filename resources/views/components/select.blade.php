@props([
  'type', 'name', 'label',
  'autofocus' => false,
  'required' => false,
  "disabled" => false,
  'options',
  'emptyOption' => false,
  'value' => null,
  'small' => false,
  'dataItems' => null,
])

<div class="inputContainer">
  <label for="{{ $name }}">{{ $label }}</label>
  <select
    name="{{ $name }}"
    id="{{ $name }}"
    {{ $autofocus ? "autofocus" : "" }}
    {{ $disabled ? "disabled" : "" }}
    {{ $required ? "required" : "" }}
    >
    @if ($emptyOption)
    <option value="" {{ $value ? "" : "selected" }}></option>
    @endif
    @foreach ($options as $key => $val)
    <option value="{{ $key }}" {{ $dataItems ? "data-item=".$dataItems[$key] : "" }} {{  $value == $key ? "selected" : "" }}>{{ $val }}</option>
    @endforeach
  </select>
</div>
