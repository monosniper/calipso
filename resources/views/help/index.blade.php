@extends('layouts.main')

@section('title')
    {{__('main.help')}}
@endsection

@section('content')

    <div class="container main">
        @include('includes.breadcrumbs', ['items' => [
                                [
                                    'link' => route('home'),
                                    'title' => __('main.main'),
                                ],
                                [
                                    'link' => '#',
                                    'title' => __('main.help'),
                                ],
                    ]
                ])

        <h1 class="help-title">{{__('main.help')}}</h1>

        <div class="help-content">
            <div class="faq sm-cont" id="faq">
                <h3 class="help-subtitle">{{__('help.faq')}}</h3>
                <div class="faq-search">
                    <input type="text" id="faq-search-input" placeholder="{{__('help.search')}}">
                </div>
                <div class="faq-items">
                    @forelse($questions as $question)
                        <div class="faq-item" onclick="toggleAccordion(this)">
                            <div class="faq-item-header">
                                <h4>{{$question->title}}</h4><span class="icon"></span>
                            </div>
                            <div class="faq-item-content">
                                <div class="faq-item-content-wrapper">
                                    <p>{{$question->answer}}</p>
                                </div>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>
            </div>

            <div class="sm-cont" id="price">
                <h3 class="help-subtitle">{{__('help.price_table')}}</h3>

                <div class="table">
                    <div class="table-row">
                        <div class="cell" data-title="Name">
                            Luke Peters
                        </div>
                        <div class="cell" data-title="Age">
                            25
                        </div>
                    </div>

                    <div class="table-row">
                        <div class="cell" data-title="Name">
                            Joseph Smith
                        </div>
                        <div class="cell" data-title="Age">
                            27
                        </div>
                    </div>

                    <div class="table-row">
                        <div class="cell" data-title="Name">
                            Maxwell Johnson
                        </div>
                        <div class="cell" data-title="Age">
                            26
                        </div>
                    </div>

                    <div class="table-row">
                        <div class="cell" data-title="{{__('help.name')}}">
                            Harry Harrison
                        </div>
                        <div class="cell" data-title="{{__('help.price')}}">
                            25
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/help.css') }}"/>
@endsection

@section('js')
    <script>
        let elementOld = null;
        const openClass = "faq-item--open";

        function toggleAccordion(element) {
            let content = element.querySelector(".faq-item-content");

            if(elementOld != null){
                elementOld.classList.remove(openClass);
                let contentOld = elementOld.querySelector(".faq-item-content");
                contentOld.style.maxHeight = "0px";
            }

            if(elementOld !== element){
                element.classList.add(openClass);
                content.style.maxHeight = content.scrollHeight + 20 + "px";
                elementOld = element;
            } else{
                elementOld = null;
            }
        }

        const faqSearch = new RLSearch({
            input_selector: '#faq-search-input',
            items_container_selector: '.faq-items',
            item_selector: '.faq-item',
            item_title_selector: '.faq-item-header',
            item_title_el_selector: 'h4',
            items_limit: 5,
        });
    </script>
@endsection
