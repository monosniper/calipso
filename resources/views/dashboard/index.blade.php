@extends('layouts.dashboard')

@section('title')
    {{__('main.cabinet')}}
@endsection

@section('content')

    @include('includes.modals.resume')
    @include('includes.modals.make_portfolio')

    <div class="grey_block">
        <div class="profile_info_wrapper">
            <div class="profile_info_content">
                <div class="profile_title no_underline {{$user->username !== $user->fullName ? 'profile_has_username' : ''}}">{{ $user->fullName }}</div>
                @if($user->username !== $user->fullName)
                    <div class="profile_username">{{ '@'.$user->username }}</div>
                @endif
                <div class="profile_details">
                    @if($user->location)
                        <div class="profile_detail">
                            <div class="profile_detail_icon">
                                @include('includes.svg', ['name' => 'location'])
                            </div>
                            <div class="profile_detail_name">
                                {{ $user->location }}
                            </div>
                        </div>
                    @endif
                    <div class="profile_detail">
                        <div class="profile_detail_icon">
                            @include('includes.svg', ['name' => 'online'])
                        </div>
                        <div class="profile_detail_name">
                            {{__('main.now_online')}}
                        </div>
                    </div>
                    @if($user->birthday)
                        <div class="profile_detail">
                            <div class="profile_detail_icon">
                                @include('includes.svg', ['name' => 'birthday'])
                            </div>
                            <div class="profile_detail_name">
                                {{__('main.years', ['years' => 20])}}
                            </div>
                        </div>
                    @endif
                    <div class="profile_detail">
                        <div class="profile_detail_icon">
                            @include('includes.svg', ['name' => 'home'])
                        </div>
                        <div class="profile_detail_name">
                             {{ __('main.on_service', ['time' => $user->getTimeOnService()]) }}
                        </div>
                    </div>
                </div>
                <a href="{{route('dashboard.edit-profile')}}" class="webz_btn d-inline-block">{{__('main.edit_profile')}}</a>
            </div>
            <div class="profile_info_avatar">
                <div class="profile_avatar" >
                    <span class="profile_avatar_edit" id="update_avatar_btn">
                        <i class="fas fa-pencil-alt"></i>
                    </span>
                    <img src="{{$user->getAvatar()}}" alt="{{$user->fullName}}" class="profile_avatar_image">
                    <form id="avatar_form" action="{{route('forms.avatar')}}" method="post" enctype="multipart/form-data" class="avatar_form">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <input type="file" name="avatar" accept="image/png, image/jpeg, image/gif" class="profile_avatar_image" id="avatar" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-end">
        <a href="{{route('chat.index')}}" class="profile_menu_item messages_btn">
            <div class="profile_menu_item_icon">
                @include('includes.svg', ['name' => 'chat'])
            </div>
            <div class="profile_menu_item_title ">{{__("main.messages")}} <small>(1)</small></div>
        </a>
    </div>
    <div class="grey_block">
        <div class="profile_title">{{__($user->isFreelancer ? 'main.resume' : 'main.about_self')}}</div>
        <div class="profile_text">
            <pre>{!! $user->resume !!}</pre>
        </div>
        <button class="webz_btn d-inline-block open_modal" modal-wrapper="#resume">{{__($user->isFreelancer ? 'main.edit_resume' : 'main.edit_about_self')}}</button>
    </div>
    @if($user->isFreelancer)
        <div class="grey_block">
            <div class="section_header">
                <div class="profile_title">{{__('main.portfolio')}}</div>
            </div>

            <div class="portfolios_tags">
                <a class="portfolios_tag {{request()->filled('tag') ?: 'active'}}" href="{{route('dashboard.cabinet')}}">{{__('main.all')}}</a>
                @foreach($portfolios->pluck('tag') as $tag)
                    @if($tag)
                        <a href="{{route('dashboard.cabinet', ['tag' => $tag])}}" class="portfolios_tag {{request('tag') !== $tag ?: 'active'}}">{{$tag}}</a>
                    @endif
                @endforeach
            </div>

            <div class="portfolios">
                @foreach($portfolios as $portfolio)
                    @include('includes.portfolio')
                @endforeach
            </div>

            <button class="webz_btn d-inline-block open_modal" modal-wrapper="#make_portfolio">{{__('main.add_portfolio')}}</button>
        </div>
    @endif
@endsection

@section('js')
    <script>
        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginImageExifOrientation,
            FilePondPluginImageCrop,
            FilePondPluginImageResize,
            FilePondPluginImageTransform,
            FilePondPluginImageEdit,
        );

        const avatar = FilePond.create(document.querySelector('#avatar'));
        avatar.setOptions({
            required: true,
            storeAsFile: true,
            imageCropAspectRatio: '1:1',
            maxFileSize: '100MB',
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,
            stylePanelLayout: 'compact circle',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
            labelIdle: "{{__('main.filepond_label')}}",
        });

        FilePond.registerPlugin(
            FilePondPluginFilePoster
        );
        const preview = FilePond.create(document.querySelector('#preview'));
        preview.setOptions({
            required: true,
            storeAsFile: true,
            acceptedFileTypes: ['image/*'],
            maxFileSize: '100MB',
            labelIdle: "{{__('main.filepond_label')}}"
        });

        $('#update_avatar_btn').click(avatar.browse);

        avatar.on('addfile', (error, file) => {
            if (error) {
                return;
            }

            $('#avatar_form').submit();
        });

        const editor = new Froala('#content', {
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
    </script>
@endsection

@section('css')
    <style>
        #location {
            margin-top: 25px;
        }
    </style>
@endsection
