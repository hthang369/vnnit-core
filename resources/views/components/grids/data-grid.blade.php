<x-row>
    <x-col>
        {!! $grid->renderSearchForm() !!}
    </x-col>
    <x-col>
        @foreach ($grid->getButtons() as $btn)
            {!! $btn !!}
        @endforeach
    </x-col>
</x-row>
<x-table
    :responsive="true"
    bordered
    hover
    :id="$grid->getId()"
    :sectionCode="$sectionCode"
    :items="data_get($data, 'rows')"
    :fields="data_get($data, 'fields')"
    :pagination="data_get($data, 'paginator')">
</x-table>
@push('scripts')
<script>
    (function($) {
        var grid = "{{ '#' . $grid->getId() }}";
        var filterForm = ""; //"'#' . $grid->getFilterFormId() ";
        var searchForm = "{{ '#' . $grid->getSearchFormId() }}";
        _grids.grid.init({
          id: grid,
          filterForm: filterForm,
          dateRangeSelector: '.date-range',
          searchForm: searchForm,
          pjax: {
            pjaxOptions: {
              scrollTo: false,
              timeout: 3000
            },
            // what to do after a PJAX request. Js plugins have to be re-intialized
            afterPjax: function(e) {
              _grids.init();
            },
          },
        });
      })(jQuery);
</script>
@endpush

