@extends('layouts.main')

@section('title')
    {{__('order.safe').' '.$order->title}}
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
                    'title' => __('order.safe'),
                ],
            ]
        ])

        <div class="white_block safe-header" style="margin-top: 20px;">
            <div class="safe-header-top">
                <div class="safe-header-top-left">
                    <h2 class="safe-title">{{$order->title}}</h2>
                </div>
                <div class="safe-header-top-right">
                    <div class="safe-price">${{$order->price}}</div>
                    <div class="safe-icon" title="{{__('order.work_in_safe')}}">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                </div>
            </div>
            <div class="safe-header-bottom">
                <div class="safe-stages">
                    <div data-tab-name="{{App\Models\Safe::ACTIVE_STATUS}}" class="safe-stage {{$order->status === App\Models\Safe::ACTIVE_STATUS ? 'active' : ($order->safe->completedStatus(App\Models\Safe::ACTIVE_STATUS) ? 'completed' : 'disabled')}}">{{__('order.status.'.App\Models\Safe::ACTIVE_STATUS)}}</div>
                    <div data-tab-name="{{App\Models\Safe::AGREEMENT_STATUS}}" class="safe-stage {{$order->status === App\Models\Safe::AGREEMENT_STATUS ? 'active' : ($order->safe->completedStatus(App\Models\Safe::AGREEMENT_STATUS) ? 'completed' : 'disabled')}}">{{__('order.status.'.App\Models\Safe::AGREEMENT_STATUS)}}</div>
                    <div data-tab-name="{{App\Models\Safe::RESERVATION_STATUS}}" class="safe-stage {{$order->status === App\Models\Safe::RESERVATION_STATUS ? 'active' : ($order->safe->completedStatus(App\Models\Safe::RESERVATION_STATUS) ? 'completed' : 'disabled')}}">{{__('order.status.'.App\Models\Safe::RESERVATION_STATUS)}}</div>
                    <div data-tab-name="{{App\Models\Safe::WORK_STATUS}}" class="safe-stage {{$order->status === App\Models\Safe::WORK_STATUS ? 'active' : ($order->safe->completedStatus(App\Models\Safe::WORK_STATUS) ? 'completed' : 'disabled')}}">{{__('order.status.'.App\Models\Safe::WORK_STATUS)}}</div>
                    <div data-tab-name="{{App\Models\Safe::REVIEWS_STATUS}}" class="safe-stage {{$order->status === App\Models\Safe::REVIEWS_STATUS ? 'active' : ($order->safe->completedStatus(App\Models\Safe::REVIEWS_STATUS) ? 'completed' : 'disabled')}}">{{__('order.status.'.App\Models\Safe::REVIEWS_STATUS)}}</div>
                </div>
            </div>
        </div>

        <div class="white_block safe-body">
            <div class="safe-tab {{$order->status === App\Models\Safe::ACTIVE_STATUS ? 'active' : ''}}" data-tab-name="{{App\Models\Safe::ACTIVE_STATUS}}">
                @if($order->safe->completedStatus(App\Models\Safe::ACTIVE_STATUS))
                    <h3 class="safe-body-title">{{__('order.chose_offer')}}:</h3>
                    @include('includes.offer', ['offer' => $offer, 'bordered' => true, 'no_choose' => true])
                @endif
            </div>
            <div class="safe-tab {{$order->status === App\Models\Safe::AGREEMENT_STATUS ? 'active' : ''}}" data-tab-name="{{App\Models\Safe::AGREEMENT_STATUS}}">
                @if($order->safe->completedStatus(App\Models\Safe::AGREEMENT_STATUS))
                    <h3 class="safe-body-title">{{__('order.details')}}:</h3>
                    @if($order->status === App\Models\Safe::AGREEMENT_STATUS && $order->user_id === auth()->id())
                        <p class="safe-body-description">{{__('safe.upload_or_write_tz')}}</p>
                        <form action="{{route('forms.safe')}}" method="post" enctype="multipart/form-data" class="form form-big">
                            @csrf

                            <input type="hidden" name="safe_id" value="{{$order->safe->id}}">

                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <input type="file"
                                           id="files"
                                           name="files[]"
                                           multiple
                                           data-allow-reorder="true"
                                           data-max-file-size="100MB"
                                           data-max-files="10">
                                </div>
                            </div>


                            <div class="form-row">
                                <label class="form-label" for="tz">
                                    {{__('safe.tz')}}

                                    @include('includes.help', ['message' => __('safe.tz_help')])
                                </label>
                                <textarea id="tz" minlength="100" maxlength="5000" name="tz" class="form-field">{!! $order->safe->tz ?? request()->old('tz') !!}</textarea>
                            </div>

                            <div class="safe-body-footer">
                                <button type="submit" class="webz_btn bordered white">{{__('main.save')}}</button>
                            </div>

                            <div class="safe-alert">{{__('safe.agreement.before_next')}}</div>
                        </form>
                    @else
                        <div class="files">
                            @forelse($order->safe->media as $file)
                                <a title="{{$file->file_name}}" download href="{{$file->getUrl()}}" class="file">
                                    <i class="file-icon fas {{$order->getMimeTypeIcon($file->mime_type)}}"></i>
                                    <span class="file-name">{{mb_strimwidth($file->file_name, 0, 14).'...'}}</span>
                                    <span class="file-size">{{$file->human_readable_size}}</span>
                                </a>
                            @empty

                            @endforelse
                        </div>

                        <p>{{$order->safe->tz}}</p>

                        @if($order->user_id !== auth()->id())
                            @if($order->safe->tz === '' || !$order->safe->media->count())
                                <div class="safe-alert">{{__('safe.agreement.before_next_freelancer')}}</div>
                            @endif

                            <div class="safe-body-footer">
                                <a href="{{route('freelance.orders.agree', $order->id)}}" {{$order->safe->tz === '' || !$order->safe->media->count() ? 'disabled' : ''}} type="submit" class="webz_btn bordered white">{{__('safe.agree_and_continue')}}</a>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
            <div class="safe-tab {{$order->status === App\Models\Safe::RESERVATION_STATUS ? 'active' : ''}}" data-tab-name="{{App\Models\Safe::RESERVATION_STATUS}}">
                <h3 class="safe-body-title">{{__('order.status.'.App\Models\Safe::RESERVATION_STATUS)}}:</h3>
                <p class="safe-body-description">{{__('safe.reservation' . ($order->user_id !== auth()->id() ? '_freelancer' : ''))}}</p>
                @unless($order->safe->completedStatus(App\Models\Safe::RESERVATION_STATUS))
                    <a href="{{route('freelance.orders.reserve', $order->id)}}" class="webz_btn">{{__('safe.reserve')}}</a>
                @endunless
            </div>
            <div class="safe-tab {{$order->status === App\Models\Safe::WORK_STATUS ? 'active' : ''}}" data-tab-name="{{App\Models\Safe::WORK_STATUS}}">
                <h3 class="safe-body-title">{{__('order.status.'.App\Models\Safe::WORK_STATUS)}}:</h3>

                <p class="safe-body-description">{{__('safe.'.($order->user_id !== auth()->id() ? 'freelancer.' : '').'work')}}</p>

                @if($order->user_id !== auth()->id())
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            @unless($order->safe->completedStatus(App\Models\Safe::WORK_STATUS))
                                <form class="form form-big" action="{{route('forms.safe_result')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="safe_id" value="{{$order->safe->id}}">
                                    <input placeholder="{{__('safe.result_link')}}" value="{{$order->safe->result_link}}" type="url" name="result_link" class="form-field">

                                    <div class="safe-body-footer">
                                        <button class="white webz_btn bordered">{{__('main.save')}}</button>
                                    </div>
                                </form>
                            @else
                                <p>{{__('safe.result_link')}}</p>
                                <a href="{{$order->safe->result_link}}" class="safe-result-link">{{$order->safe->result_link}}</a>
                            @endunless
                        </div>
                    </div>
                @else
                    @if($order->safe->result_link)
                        <p>{{__('safe.result_link')}}</p>
                        <a href="{{$order->safe->result_link}}" class="safe-result-link">{{$order->safe->result_link}}</a>
                        <p class="safe-alert">{{__('safe.be_careful_with_links')}}</p>
                    @endif

                    @unless($order->safe->completedStatus(App\Models\Safe::WORK_STATUS))
                        <div class="safe-body-footer">
                            <a href="{{$order->safe->result_link ? route('freelance.orders.close', $order->id) : '#'}}" class="webz_btn" {{$order->safe->result_link ? '' : 'disabled'}}>{{__('safe.close')}}</a>
                        </div>
                    @endunless
                @endif
            </div>
            <div class="safe-tab {{$order->status === App\Models\Safe::REVIEWS_STATUS ? 'active' : ''}}" data-tab-name="{{App\Models\Safe::REVIEWS_STATUS}}">
                <h3 class="safe-body-title">{{__('order.status.'.App\Models\Safe::REVIEWS_STATUS)}}:</h3>
                <p class="safe-body-description">{{__('safe.'.($order->user_id !== auth()->id() ? 'freelancer.' : '').'reviews')}}</p>
            </div>
        </div>

    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/safe.css') }}"/>
    <style>
        body {
            background: #f5f5f5;
        }
        .filepond--file {
            color: inherit !important;
            border: 1px solid #e7e7e7;
        }
    </style>
@endsection

@section('js')
    <script>
        const editor = new Froala('#tz', {
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
            files: JSON.parse('{!! json_encode($order->safe->getMedia('files')->pluck('original_url')) !!}').map(source => {
                return {source}
            }),
            labelIdle: "{!! __('main.filepond_label') !!}"
        });

        $('.safe-stage').each((i, stage) => {
            $(stage).click(() => {
                if($(stage).hasClass('completed')) {
                    $('.safe-stage.active').removeClass('active').addClass('completed');
                    $(stage).addClass('active').removeClass('completed');

                    $(`.safe-tab.active`).removeClass('active');
                    $(`.safe-tab[data-tab-name=${$(stage).attr('data-tab-name')}]`).addClass('active');
                }
            })
        })
    </script>
@endsection
