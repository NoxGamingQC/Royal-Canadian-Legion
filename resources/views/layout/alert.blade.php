@if(isset($errors))
    @if(count($errors->all()) > 0)
        @foreach($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{$error}}
            </div>
        @endforeach
    @endif
@endif
@if(isset($successes))
    @if(count($successes) > 0)
        @foreach($successes as $success)
            <div class="alert alert-success" role="alert">
                {{$success}}
            </div>
        @endforeach
    @endif
@endif
<!--

<div class="alert alert-warning" role="alert">
  A simple warning alert—check it out!
</div>
<div class="alert alert-info" role="alert">
  A simple info alert—check it out!
</div>
-->