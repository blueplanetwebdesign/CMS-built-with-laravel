@if(Session::has('flash_message'))
    <div class="flash-message alert alert-info {{ session::has('flash_message_important')? 'alert-important' : '' }}">
        @if( session::has('flash_message_important') )
            <button type="button" class="close" data-dismiss="alert">&times</button>
        @endif
        {{ Session::get('flash_message') }}
    </div>
@endif