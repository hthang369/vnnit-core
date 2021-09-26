<x-table
    :responsive="true"
    bordered
    hover
    id="gridData"
    :sectionCode="$sectionCode"
    :items="data_get($data, 'rows')"
    :fields="data_get($data, 'fields')">
</x-table>

