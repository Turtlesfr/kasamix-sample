var companyPage;
companyPage = {
    loadPromotor: function (link) {
        var table = $(link.tableId).DataTable({
            destroy: true,
            "dom": '<"row"<"col-sm-12"rt>>' + '<"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "pageLength": 25,
            ajax: link.list,
            "columnDefs": [
                {
                    targets: 0,
                    "render": function (data, type, row) {
                        return '<a href="' + link.edit.replace(999999999, row.id) + '">' + row.name + '</a>';
                    }
                },
                {
                    targets: 1,
                    data: "zip"
                },
                {
                    targets: 2,
                    "render": function (data, type, row) {
                        if (!row.projects) {
                            return 'No data'
                        } else {
                            return row.projects;
                        }
                    }
                },
                {
                    targets: 3,
                    "render": function (data, type, row) {
                        if (!row.accom) {
                            return 'No data'
                        } else {
                            return row.accom;
                        }
                    }
                },
                {
                    targets: 4,
                    "render": function (data, type, row) {
                        if (!row.clients) {
                            return 'No data'
                        } else {
                            return row.clients;
                        }
                    }
                },
                {
                    targets: 5,
                    "render": function (data, type, row) {
                        if (!row.accom_w_client) {
                            return 'No data'
                        } else {
                            return row.accom_w_client;
                        }
                    }
                }
            ]
        });
    },
    loadProviders: function (link) {
        $(link.tableId).DataTable({
            destroy: true,
            "dom": '<"row"<"col-sm-12"rt>>' + '<"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "pageLength": 25,
            ajax: link.list,
            "columnDefs": [
                {
                    targets: 0,
                    "render": function (data, type, row) {
                        return '<a href="' + link.edit.replace(999999999, row.id) + '">' + row.name + '</a>';
                    }
                },
                {
                    targets: 1,
                    data: "zip"
                }
            ]
        });
    },
    loadFitters: function (link) {
        $(link.tableId).DataTable({
            destroy: true,
            "dom": '<"row"<"col-sm-12"rt>>' + '<"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "pageLength": 25,
            ajax: link.list,
            "columnDefs": [
                {
                    targets: 0,
                    "render": function (data, type, row) {
                        return '<a href="' + link.edit.replace(999999999, row.id) + '">' + row.name + '</a>';
                    }
                },
                {
                    targets: 1,
                    data: "zip"
                },
                {
                    targets: 2,
                    "render": function (data, type, row) {
                        if (!row.accom) {
                            return 'No data'
                        } else {
                            return row.accom;
                        }
                    }
                }
            ]
        });
    },
    loadOther: function (link) {
        $(link.tableId).DataTable({
            destroy: true,
            "dom": '<"row"<"col-sm-12"rt>>' + '<"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "pageLength": 25,
            ajax: link.list,
            "columnDefs": [
                {
                    targets: 0,
                    "render": function (data, type, row) {
                        return '<a href="' + link.edit.replace(999999999, row.id) + '">' + row.name + '</a>';
                    }
                },
                {
                    targets: 1,
                    data: "zip"
                }
            ]
        });
    },
    list: '',
    edit: ''
};
