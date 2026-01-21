$(document).ready(function () {
  "use strict";
  $("#basic-datatable").DataTable({
    order: [],
    keys: !0,
    pageLength: 5,
    lengthMenu: [
      [5, 10, 20, -1],
      [5, 10, 25, 50, 100],
    ],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
      paginate: {
        previous: "<i class='mdi mdi-chevron-left'>",
        next: "<i class='mdi mdi-chevron-right'>",
      },
    },
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
  });
  var a = $("#datatable-buttons").DataTable({
    order: [],
    lengthChange: !1,
    pageLength: 5,
    lengthMenu: [
      [5, 10, 20, -1],
      [5, 10, 25, 50, 100],
    ],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
      paginate: {
        previous: "<i class='mdi mdi-chevron-left'>",
        next: "<i class='mdi mdi-chevron-right'>",
      },
    },
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
  });
  $("#selection-datatable").DataTable({
    order: [],
    pageLength: 5,
    lengthMenu: [
      [5, 10, 20, -1],
      [5, 10, 25, 50, 100],
    ],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
      paginate: {
        previous: "<i class='mdi mdi-chevron-left'>",
        next: "<i class='mdi mdi-chevron-right'>",
      },
    },
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
  }),
    a
      .buttons()
      .container()
      .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
    $("#alternative-page-datatable").DataTable({
      order: [],
      pagingType: "full_numbers",
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
        paginate: {
          previous: "<i class='mdi mdi-chevron-left'>",
          next: "<i class='mdi mdi-chevron-right'>",
        },
      },
      drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
      },
    }),
    $("#scroll-vertical-datatable").DataTable({
      order: [],
      pageLength: 5,
      lengthMenu: [
        [5, 10, 20, -1],
        [5, 10, 25, 50, 100],
      ],
      scrollY: "350px",
      scrollCollapse: !0,
      paging: !1,
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
        paginate: {
          previous: "<i class='mdi mdi-chevron-left'>",
          next: "<i class='mdi mdi-chevron-right'>",
        },
      },
      drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
      },
    }),
    $("#scroll-horizontal-datatable").DataTable({
      order: [],
      scrollX: !0,
      pageLength: 5,
      lengthMenu: [
        [5, 10, 20, -1],
        [5, 10, 25, 50, 100],
      ],
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
        paginate: {
          previous: "<i class='mdi mdi-chevron-left'>",
          next: "<i class='mdi mdi-chevron-right'>",
        },
      },
      drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
      },
    }),
    $("#complex-header-datatable").DataTable({
      order: [],
      pageLength: 5,
      lengthMenu: [
        [5, 10, 20, -1],
        [5, 10, 25, 50, 100],
      ],
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
        paginate: {
          previous: "<i class='mdi mdi-chevron-left'>",
          next: "<i class='mdi mdi-chevron-right'>",
        },
      },
      drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
      },
      columnDefs: [{ visible: !1, targets: -1 }],
    }),
    $("#row-callback-datatable").DataTable({
      order: [],
      pageLength: 5,
      lengthMenu: [
        [5, 10, 20, -1],
        [5, 10, 25, 50, 100],
      ],
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
        paginate: {
          previous: "<i class='mdi mdi-chevron-left'>",
          next: "<i class='mdi mdi-chevron-right'>",
        },
      },
      drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
      },
      createdRow: function (a, e, t) {
        15e4 < +e[5].replace(/[\$,]/g, "") &&
          $("td", a).eq(5).addClass("text-danger");
      },
    }),
    $("#state-saving-datatable").DataTable({
      order: [],
      pageLength: 5,
      lengthMenu: [[5, 10, 25, 50, 100]],
      stateSave: !0,
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json",
        paginate: {
          previous: "<i class='mdi mdi-chevron-left'>",
          next: "<i class='mdi mdi-chevron-right'>",
        },
      },
      drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
      },
    }),
    $(".dataTables_length select").addClass("form-select form-select-sm"),
    $(".dataTables_length label").addClass("form-label");
});
