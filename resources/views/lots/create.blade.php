@extends('layouts.main')

@section('title')
    {{__('main.make_lot')}}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
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

        const archive = FilePond.create(document.querySelector('#archive'));
        archive.setOptions({
            required: true,
            storeAsFile: true,
            maxFiles: 1,
            maxFileSize: '100MB',
            labelIdle: "{!! __('main.filepond_label') !!}"
        });

        const images = FilePond.create(document.querySelector('#images'));
        images.setOptions({
            required: true,
            storeAsFile: true,
            maxFileSize: '100MB',
            acceptedFileTypes: ['image/*'],
            labelIdle: "{!! __('main.filepond_label') !!}"
        });

        const propsFieldsLimit = 15;
        const propsFieldTemplate = $('.form-separated-field.template');
        const propsFieldsContainer = $('#props');

        const addPropField = () => {
            const propFieldsCount = $('.form-separated-field').length;

            if(propFieldsCount <= propsFieldsLimit) {
                const propsField = propsFieldTemplate.clone();

                const keyAttr = `properties[${propFieldsCount}][key]`;
                const valueAttr = `properties[${propFieldsCount}][value]`;

                $(propsField).removeClass('template');

                $(propsField).find('input.form-separated-field-key').attr('name', keyAttr);
                $(propsField).find('input.form-separated-field-value').attr('name', valueAttr);

                $(propsFieldsContainer).append(propsField);
            }
        }

        $('#add_prop').click(addPropField);

        $('#category').selectize();
        $('#tags').selectize({create:true});
    </script>
@endsection

@section('content')

    <div class="container main">
        @include('includes.breadcrumbs', ['back_btn' => true, 'items' => [
                [
                    'link' => route('home'),
                    'title' => __('main.main'),
                ],
                [
                    'link' => route('lots.index'),
                    'title' => __('main.shop'),
                ],
                [
                    'link' => '#',
                    'title' => __('main.make_lot'),
                ],
            ]
        ])

        <div class="white_block" style="margin-top: 20px;">
            <form action="{{route('lots.store')}}" method="post" enctype="multipart/form-data" class="form form-big">
                <h2 class="form-title">{{__('main.make_lot')}}</h2>

                @csrf

                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <label class="form-label" for="title">{{__('main.title')}}:</label>
                        <input placeholder="{{__('main.title')}}..." id="title" required minlength="10" maxlength="255" type="text" name="title" value="{{request()->old('title')}}" class="form-field">

                        <div class="form-group">
                            <div class="form-group-item">
                                <label class="form-label" for="price">{{__('main.price')}} ($):</label>
                                <input placeholder="0.00" id="price" required min="1" type="number" name="price" value="{{request()->old('price')}}" class="form-field">
                            </div>

                            <div class="form-group-item">
                                <label class="form-label" for="price">{{__('main.category')}}:</label>
                                <select id="category" required name="category_id" class="form-field">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <label class="form-label" for="tags">{{__('main.tags')}}:</label>
                        <select id="tags" multiple name="tags[]" class="form-field">
                            @foreach($tags as $tag)
                                <option value="{{$tag->id}}">{{$tag->name}}</option>
                            @endforeach
                        </select>

                        <div class="form-row">
                            <label class="form-label" for="description">{{__('main.description')}}:</label>
                            <textarea id="description" minlength="100" maxlength="5000" name="description" class="form-field">{!!request()->old('description')!!}</textarea>
                        </div>

                        <label class="form-label form-label-header">
                            <span>{{__('main.properties')}}:</span>
                            <button type="button" class="webz_btn white bordered" id="add_prop"><i class="fas fa-plus"></i></button>
                        </label>
                        <div class="form-separated-field template">
                            <input placeholder="{{__('main.key')}}" type="text" class="form-field form-separated-field-key">
                            <input placeholder="{{__('main.value')}}" type="text" class="form-field form-separated-field-value">
                        </div>
                        <div id="props"></div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <label class="form-label" for="archive">{{__('main.archive')}}:</label>
                        <input type="file"
                               id="archive"
                               name="archive"
                               data-allow-reorder="true"
                               data-max-files="1">
                        <label class="form-label" for="description">{{__('main.images')}}:</label>
                        <input type="file"
                               id="images"
                               name="images[]"
                               multiple
                               data-allow-reorder="true"
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
