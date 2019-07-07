@if(app()->environment('production'))
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
@else
    <script src="{{ asset('js/vendor/data-tables.js') }}"></script>
    <script src="{{ asset('js/vendor/data-tables-bs4.js') }}"></script>
@endif

<script>
    let columns = [
        {
            data: 'name',
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                $(nTd).html("<a href=\"" + oData.link + "\">" + oData.name + "</a>");
            }
        },
        {
            data: 'approved_at',
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                if (oData.approved_at) {
                    string = "<i class=\"far fa-check-circle text-success\"></i>";
                } else {
                    string = "<i class=\"far fa-times-circle text-danger\"></i>"
                }
                $(nTd).html(string);
            }
        },
        {
            data: 'wiki_title',
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                string = "";
                if (oData.wiki_title) {
                    string = "<a href=\"https://en.wikipedia.org/wiki/" + oData.wiki_title + "\" target=\"_blank\">" + oData.wiki_title + "</a>";
                }
                $(nTd).html(string);
            }
        },
        {
            data: 'wikidata_id',
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                string = "";
                if (oData.wikidata_id) {
                    string = "<a href=\"https://www.wikidata.org/wiki/" + oData.wikidata_id + "\" target=\"_blank\"><span class=\"badge badge-success\">Wikidata</span></a>";
                }
                $(nTd).html(string);
            }
        },
        {
            data: 'geo_id',
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                string = "";
                if (oData.geo_id) {
                    string = "<a href=\"https://www.geonames.org/" + oData.geo_id + "\" target=\"_blank\"><span class=\"badge badge-success\">GEO</span>"
                }
                $(nTd).html(string);
            }
        },
        {
            data: 'os_id',
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                string = "";
                if (oData.os_id) {
                    string = "<a href=\"http://data.ordnancesurvey.co.uk/doc/" + oData.os_id + "\" target=\"_blank\"><span class=\"badge badge-success\">OS</span>"
                }
                $(nTd).html(string);
            }
        },
        {
            data: 'ons_id',
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                string = "";
                if (oData.ons_id) {
                    string += "<a href=\"http://statistics.data.gov.uk/doc/statistical-geography/" + oData.ons_id + "\" target=\"_blank\"><span class=\"badge badge-success\">ONS</span>";
                }
                if (oData.ipn_id) {
                    string += "<span class=\"badge badge-success\">IPN</span>";
                }
                $(nTd).html(string);
            }
        },
        {
            data: 'osm_id',
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                string = "";
                if (oData.osm_id) {
                    string = "<a href=\"https://www.openstreetmap.org/relation/" + oData.osm_id + "\" target=\"_blank\"><span class=\"badge badge-success\">OSM</span>";
                }
                $(nTd).html(string);
            }
        },
        {
            data: 'lat',
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                if (oData.lat != null && oData.lon != null) {
                    $(nTd).html("<a href=\"https://www.openstreetmap.org/?mlat=" + oData.lat + "&amp;mlon=" + oData.lon + "#map=5/" + oData.lat + "/" + oData.lon + "\" target=\"_blank\" title=\"" + oData.lat + ", " + oData.lon + "\"><i class=\"fas fa-globe-americas\"></i></a>");
                }
            }
        }
    ];
</script>