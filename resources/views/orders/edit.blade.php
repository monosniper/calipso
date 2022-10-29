@extends('layouts.main')

@section('title')
    {{__('main.edit').' '.$order->title}}
@endsection

@section('css')
    <style>
        body {
            background: #f5f5f5;
        }
        .filepond--file {
            color: inherit !important;
            border: 1px solid #e7e7e7;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap3.min.css" integrity="sha512-MNbWZRRuTPBahfBZBeihNr9vTJJnggW3yw+/wC3Ev1w6Z8ioesQYMS1MtlHgjSOEKBpIlx43GeyLM2QGSIzBDg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    <script>
        const editor = new Froala('#description', {
            charCounterCount: true,
            charCounterMax: 5000,
            heightMax: 500,
            heightMin: 300,
            width: '800',
            pasteAllowedStyleProps: [],
            toolbarButtons: {
                'moreText': {
                    'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
                },
                'moreParagraph': {
                    'buttons': ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote']
                },
                'moreRich': {
                    'buttons': ['insertLink', 'insertImage', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
                },
                'moreMisc': {
                    'buttons': ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll']
                }
            },

            // Change buttons for XS screen.
            toolbarButtonsXS: [['undo', 'redo'], ['bold', 'italic', 'underline']]
        })

        const files = FilePond.create(document.querySelector('#files'));
        files.setOptions({
            storeAsFile: true,
            maxFileSize: '100MB',
            files: JSON.parse('{!! json_encode($order->getMedia('files')->pluck('original_url')) !!}').map(source => {
                return {source}
            }),
            labelIdle: "{!! __('main.filepond_label') !!}"
        });

        $('#category').selectize();
        $('#tags').selectize({create:true});
    </script>
@endsection

@section('content')

    <div class="container main">
        @include('includes.breadcrumbs', ['back_btn' => true, 'items' => [
                        [
                            'link' => route('dashboard.cabinet'),
                            'title' => __('main.profile'),
                        ],
                [
                            'link' => route('dashboard.orders'),
                            'title' => __('main.my_orders'),
                        ],
                        [
                            'link' => route('orders.show', $order->id),
                            'title' => $order->title,
                        ],
                [
                    'link' => '#',
                    'title' => __('main.edit'),
                ],
            ]
        ])

        <div class="white_block" style="margin-top: 20px;">
            <form action="{{route('orders.update', $order->id)}}" method="post" enctype="multipart/form-data" class="form form-big">
                <h2 class="form-title">{{__('main.edit').' '.$order->title}}</h2>

                @method('PUT')
                @csrf

                <div class="row">
                    <div class="col-6">
                        <label class="form-label" for="title">{{__('main.title')}}:</label>
                        <input placeholder="{{__('main.title')}}..." id="title" required minlength="10" maxlength="255" type="text" name="title" value="{{$order->title}}" class="form-field">

                        <div class="form-group">
                            <div class="form-group-item">
                                <label class="form-label" for="price">{{__('main.price')}} ($):</label>
                                <input placeholder="0.00" id="price" required min="1" type="number" name="price" value="{{$order->price}}" class="form-field">
                            </div>

                            <div class="form-group-item">
                                <label class="form-label" for="days">{{__('order.offer.days_count')}}:</label>
                                <input placeholder="3" id="days" required min="1" type="number" name="days" value="{{$order->days}}" class="form-field">
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-lg-7 col-sm-12">
                                <label class="form-label" for="price">{{__('main.category')}}:</label>
                                <select id="category" required name="category_id" class="form-field">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{$category->name === $order->category->name ? 'selected' : ''}}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-5 col-sm-12">
                                <input class="form-checkbox" {{$order->isSafe ? 'checked' : ''}} id="isSafe" type="checkbox" name="isSafe">
                                <label for="isSafe">{{__('order.work_in_safe')}}</label>
                            </div>
                        </div>

                        <label class="form-label" for="tags">{{__('main.tags')}}:</label>

                        <select id="tags" multiple name="tags[]" class="form-field">
                            @foreach($tags as $tag)
                                <option value="{{$tag->name}}" {{$order->tags()->find($tag->id) ? 'selected' : ''}}>{{$tag->name}}</option>
                            @endforeach
                        </select>

                        <div class="form-row">
                            <label class="form-label" for="description">{{__('main.description')}}:</label>
                            <textarea id="description" minlength="100" maxlength="5000" name="description" class="form-field">{!!$order->description!!}</textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <input type="file"
                               id="files"
                               name="files[]"
                               multiple
                               data-allow-reorder="true"
                               data-max-file-size="100MB"
                               data-max-files="10">
                    </div>
                </div>
                <div class="form-footer">
                    <button class="webz_btn">{{__('main.ready')}}</button>
                </div>
            </form>
        </div>

    </div>

@endsection
