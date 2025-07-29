<div class="mb-4">
    {{ $slot }}
    <div class="input-group">
        <input @isset($id) id="{{$id}}" @endisset type="password" name="{{$name}}"
               class="form-control form-control-alt form-control-lg"
               placeholder="{{$placeholder ?? ''}}" required/>
        <span class="input-group-text">
        <i class="{{$name}}-icon fa fa-fw fa-eye"
           onclick="$('input[name={{$name}}]').attr('type', 'text');$('.{{$name}}-icon').toggle()"></i>
        <i class="{{$name}}-icon fa fa-fw fa-eye-slash" style="display: none"
           onclick="$('input[name={{$name}}]').attr('type', 'password');$('.{{$name}}-icon').toggle()"></i>
        </span>

        <x-invalid-feedback :name="$name"/>
    </div>
</div>
