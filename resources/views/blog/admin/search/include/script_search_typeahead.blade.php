<script>
    var route = "{{url('/admin/autocomplete')}}";

    $('#search').typeahead({
        source: function (term, process) {
            return $.get(route, {term: term}, function (data) {
                return process(data);
            });
        }
    });
</script>
