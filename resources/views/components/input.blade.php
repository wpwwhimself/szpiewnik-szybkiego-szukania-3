@props([
  "type",
  "name",
  "label",
  "value" => null,
  "disabled" => false,
])

@switch($type)
  @case ("TEXT")
    <div class="inputContainer">
      <label for="{{ $name }}">{{ $label }}</label>
      <textarea name="{{ $name }}" id="{{ $name }}">{{ $value }}</textarea>
    </div>
    @break
  @case ("checkbox")
    <div class="inputContainer">
      <label for="{{ $name }}">{{ $label }}</label>
      <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" {{ $value ? "checked" : "" }} />
    </div>
    @break
  @default
    <div class="inputContainer">
      <label for="{{ $name }}">{{ $label }}</label>
      <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" {{ $disabled ? "disabled" : "" }} />
    </div>
@endswitch
