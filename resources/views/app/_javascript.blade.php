@if(app()->environment('production'))
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsrender/1.0.2/jsrender.min.js"
            integrity="sha256-hd/uw4FMRrVcuSBTkik17X3zMo4cASwOARGQqDrazFo="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.min.js"
            integrity="sha256-E+fw0vUbuPq4p3FWWtX7FzzlcMTe7hvrgZxOk8LPAh4="
            crossorigin="anonymous"></script>
@else
    <script src="{{ asset('js/vendor/jquery.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.js') }}"></script>
    <script src="{{ asset('js/vendor/jsrender.js') }}"></script>
    <script src="{{ asset('js/vendor/pagination.js') }}"></script>
@endif

<script id="search-template" type="text/x-jsrender">
<div class="result">
   <span><a href="@{{:link}}">@{{:name}}</a> <em>(@{{:type}})</em></span>
   @{{if parents.length}}
       <ul>
           @{{for parents}}
               <li>
                   <a href="@{{>link}}">@{{>name}}</a>
               </li>
           @{{/for}}
       </ul>
   @{{/if}}
</div>
</script>
<script src="{!! asset('js/app/app.js') !!}"></script>
<script>
    $("nav input").on("keyup click input", $.debounce(200, function () {
        let search_val = $(this).val().trim();
        if (search_val.length) {
            $.ajax({
                url: "{{ route('api.search.show') }}",
                method: "GET",
                data: {
                    name: search_val
                }
            }).done(function (data) {
                if (data.data.length > 0) {
                    data.data.map(function (el) {
                        el.name = el.name.replace(new RegExp(search_val, "i"), "<b>$&</b>");
                        return el
                    });

                    $("#results").html($.templates("#search-template").render(data.data)).show();
                } else {
                    $("#results").show().html("<div class=\"result\">@lang('places.no_results')</div>");
                }
            }).fail(function () {
                $("#results").hide()
            })
        } else {
            $("#results").hide()
        }
    }));
    $(document).on("click", function () {
        $("#results").hide()
    });
</script>