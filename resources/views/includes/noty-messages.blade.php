@if($errors->any())
    @foreach($errors->all() as $err)
        <script>
            new Noty({
                text: "{{$err}}",
                type: 'error',
            }).show();
        </script>
    @endforeach
@endif

@if (session('success'))
    <script>
        new Noty({
            text: "{{session('success')}}",
            type: 'success',
        }).show();
    </script>
@endif

@if (session('error'))
    <script>
        new Noty({
            text: "{{session('error')}}",
            type: 'error',
        }).show();
    </script>
@endif

@if (session('info'))
    <script>
        new Noty({
            text: "{{session('info')}}",
            type: 'info',
        }).show();
    </script>
@endif
