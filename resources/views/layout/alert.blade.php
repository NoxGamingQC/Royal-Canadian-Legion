@if(isset($errors))
    @if(count($errors) > 0)
        @foreach($errors as $error)
            <div class="alert alert-danger" role="alert">
                {{$error}}
            </div>
        @endforeach
    @endif
@endif
@if(isset($success))
    @if(count($successes) > 0)
        @foreach($success as $success_alert)
            <div class="alert alert-success" role="alert">
                {{$success_alert}}
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