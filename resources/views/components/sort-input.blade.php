<input class="sort-input" type="hidden"
       @if(request()?->sort)
           @php($name = array_key_first(request()?->sort))
           @php($value = request()?->sort[$name])
           name="sort[{{ $name }}]" data-name="{{ $name }}" value="{{ $value }}"
    @endif

>
