{!! Form::open(['url' => $action]) !!}
<div class="input-group mb-3">
    {!! Form::btText($name, '', compact('placeholder')) !!}
    <div class="input-group-append">
        {!! Form::btSubmit('<i class="fa fa-search"></i>') !!}
    </div>
</div>
{!! Form::close() !!}
