$(document).ready(function(){
    $('#left').click(function () {
       $('#queuemembers option:selected').appendTo('#extensions');
    });
    $('#right').on('click', function () {
       $('#extensions option:selected').appendTo('#queuemembers');
    });
    $('#submit').on('click', function () {
       $('#queuemembers option').prop('selected', true);
    });
    $("#printbutton").click(function () {
       print();
    });

    function exportTableToCSV($table, filename) {
       var $rows = $table.find('tr:has(td)'),

       // Temporary delimiter characters unlikely to be typed by keyboard
       // This is to avoid accidentally splitting the actual contents
       tmpColDelim = String.fromCharCode(11), // vertical tab character
       tmpRowDelim = String.fromCharCode(0), // null character

       // actual delimiter characters for CSV format
       colDelim = '","',
       rowDelim = '"\r\n"',

       // Grab text from table into CSV formatted string
       csv = '"' + $rows.map(function(i, row) {
          var $row = $(row),
          $cols = $row.find('td');
          return $cols.map(function(j, col) {
             var $col = $(col),
             text = $col.text();
             return text.replace(/"/g, '""'); // escape double quotes
          }).get().join(tmpColDelim);

        }).get().join(tmpRowDelim)
        .split(tmpRowDelim).join(rowDelim)
        .split(tmpColDelim).join(colDelim) + '"';

      // Deliberate 'false', see comment below
      if (false && window.navigator.msSaveBlob) {

        var blob = new Blob([decodeURIComponent(csv)], {
          type: 'text/csv;charset=utf8'
        });

        // Crashes in IE 10, IE 11 and Microsoft Edge
        // See MS Edge Issue #10396033
        // Hence, the deliberate 'false'
        // This is here just for completeness
        // Remove the 'false' at your own risk
        window.navigator.msSaveBlob(blob, filename);

      } else if (window.Blob && window.URL) {
        // HTML5 Blob        
        var blob = new Blob([csv], {
          type: 'text/csv;charset=utf-8'
        });
        var csvUrl = URL.createObjectURL(blob);

        $(this)
          .attr({
            'download': filename,
            'href': csvUrl
          });
      } else {
        // Data URI
        var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

        $(this)
          .attr({
            'download': filename,
            'href': csvData,
            'target': '_blank'
          });
      }
    }

    // This must be a hyperlink
    $(".export1").on('click', function(event) {
      // CSV
      var args = [$('#dvData1>table'), 'export.csv'];
      exportTableToCSV.apply(this, args);
      // If CSV, don't do event.preventDefault() or return false
      // We actually need this to be a typical hyperlink
    });
    $(".export2").on('click', function(event) {
      // CSV
      var args = [$('#dvData2>table'), 'export.csv'];

      exportTableToCSV.apply(this, args);

      // If CSV, don't do event.preventDefault() or return false
      // We actually need this to be a typical hyperlink
    });
    $(".export3").on('click', function(event) {
      // CSV
      var args = [$('#dvData3>table'), 'export.csv'];

      exportTableToCSV.apply(this, args);

      // If CSV, don't do event.preventDefault() or return false
      // We actually need this to be a typical hyperlink
    });
    $(".export4").on('click', function(event) {
      // CSV
      var args = [$('#dvData4>table'), 'export.csv'];

      exportTableToCSV.apply(this, args);

      // If CSV, don't do event.preventDefault() or return false
      // We actually need this to be a typical hyperlink
    });

    $(".delete").click(function(){
        return window.confirm("Are you sure?");
    });

    var queueforward = $("#queueforward").val();
    if (queueforward == "no") {
       $("#queuedest").hide();        
       $("#queuedest").prop('disabled', true);
       $("#queuetimeout").hide();        
       $("#queuetimeout").prop('disabled', true);
    }
    if (queueforward == "yes") {
       $("#queuedest").show();        
       $("#queuedest").prop('disabled', false);
       $("#queuetimeout").show();        
       $("#queuetimeout").prop('disabled', false);
    }

    var selection = $("#destination").val();
    if (selection == "") {
       $("#extensionsid").hide();        
       $("#queuesid").hide();        
       $("#ivrid").hide();
       $("#extensionsid").prop('disabled', true);        
       $("#queuesid").prop('disabled', true);
       $("#ivrid").prop('disabled', true);
       $("#voicemailid").hide();
       $("#voicemailid").prop('disabled', true);
       $("#forwardersid").hide();
       $("#forwardersid").prop('disabled', true);
    }
    if (selection == "extensions") {
       $("#extensionsid").show();        
       $("#extensionsid").prop('disabled', false);
       $("#queuesid").hide();        
       $("#ivrid").hide();
       $("#queuesid").prop('disabled', true); 
       $("#ivrid").prop('disabled', true);
       $("#voicemailid").hide();
       $("#voicemailid").prop('disabled', true);
       $("#forwardersid").hide();
       $("#forwardersid").prop('disabled', true);
    }
    if (selection == "queues") {
       $("#queuesid").show();        
       $("#queuesid").prop('disabled', false);
       $("#extensionsid").hide();        
       $("#ivrid").hide();
       $("#extensionsid").prop('disabled', true); 
       $("#ivrid").prop('disabled', true);
       $("#voicemailid").hide();
       $("#voicemailid").prop('disabled', true);
       $("#forwardersid").hide();
       $("#forwardersid").prop('disabled', true);
    }
    if (selection == "forwarders") {
       $("#forwardersid").show();        
       $("#forwardersid").prop('disabled', false);
       $("#queuesid").hide();        
       $("#queuesid").prop('disabled', true);
       $("#extensionsid").hide();        
       $("#extensionsid").prop('disabled', true); 
       $("#ivrid").hide();
       $("#ivrid").prop('disabled', true);
       $("#voicemailid").hide();
       $("#voicemailid").prop('disabled', true);
    }
    if (selection == "ivr") {
       $("#queuesid").hide();        
       $("#extensionsid").hide();        
       $("#ivrid").show();
       $("#ivrid").prop('disabled', false);
       $("#queuesid").prop('disabled', true);
       $("#extensionsid").prop('disabled', true);
       $("#voicemailid").hide();
       $("#voicemailid").prop('disabled', true);
       $("#forwardersid").hide();
       $("#forwardersid").prop('disabled', true);
    }
    if (selection == "timeconditions") {
       $("#extensionsid").hide();        
       $("#queuesid").hide();        
       $("#ivrid").hide();
       $("#extensionsid").prop('disabled', true);        
       $("#queuesid").prop('disabled', true);
       $("#ivrid").prop('disabled', true);
       $("#voicemailid").hide();
       $("#voicemailid").prop('disabled', true);
       $("#forwardersid").hide();
       $("#forwardersid").prop('disabled', true);
    }
    if (selection == "cloudcall") {
       $("#extensionsid").hide();        
       $("#queuesid").hide();        
       $("#ivrid").hide();
       $("#extensionsid").prop('disabled', true);        
       $("#queuesid").prop('disabled', true);
       $("#ivrid").prop('disabled', true);
       $("#voicemailid").hide();
       $("#voicemailid").prop('disabled', true);
       $("#forwardersid").hide();
       $("#forwardersid").prop('disabled', true);
    }
    if (selection == "operator") {
       $("#extensionsid").hide();        
       $("#queuesid").hide();        
       $("#ivrid").hide();
       $("#extensionsid").prop('disabled', true);        
       $("#queuesid").prop('disabled', true);
       $("#ivrid").prop('disabled', true);
       $("#voicemailid").hide();
       $("#voicemailid").prop('disabled', true);
       $("#forwardersid").hide();
       $("#forwardersid").prop('disabled', true);
    }
    if (selection == "voicemail") {
       $("#extensionsid").hide();        
       $("#queuesid").hide();        
       $("#ivrid").hide();
       $("#extensionsid").prop('disabled', true);        
       $("#queuesid").prop('disabled', true);
       $("#ivrid").prop('disabled', true);
       $("#voicemailid").show();
       $("#voicemailid").prop('disabled', false);
       $("#forwardersid").hide();
       $("#forwardersid").prop('disabled', true);
    }
    if (selection == "hangup") {
       $("#extensionsid").hide();        
       $("#queuesid").hide();        
       $("#ivrid").hide();
       $("#extensionsid").prop('disabled', true);        
       $("#queuesid").prop('disabled', true);
       $("#ivrid").prop('disabled', true);
       $("#voicemailid").hide();
       $("#voicemailid").prop('disabled', true);
       $("#forwardersid").hide();
       $("#forwardersid").prop('disabled', true);
    }

    $("#queueforward").change(function(){
      var queueforward = $("#queueforward").val();
      if (queueforward == "no") {
         $("#queuedest").hide();        
         $("#queuedest").prop('disabled', true);
         $("#queuetimeout").hide();        
         $("#queuetimeout").prop('disabled', true);
      }
      if (queueforward == "yes") {
         $("#queuedest").show();        
         $("#queuedest").prop('disabled', false);
         $("#queuetimeout").show();        
         $("#queuetimeout").prop('disabled', false);
      }
    });

    $("#destination").change(function(){
      var selection = $("#destination").val();
      if (selection == "") {
         $("#extensionsid").hide();        
         $("#queuesid").hide();        
         $("#ivrid").hide();
         $("#extensionsid").prop('disabled', true);        
         $("#queuesid").prop('disabled', true);
         $("#ivrid").prop('disabled', true);
         $("#voicemailid").hide();
         $("#voicemailid").prop('disabled', true);
         $("#forwardersid").hide();
         $("#forwardersid").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid").show();        
         $("#extensionsid").prop('disabled', false);
         $("#queuesid").hide();        
         $("#ivrid").hide();
         $("#queuesid").prop('disabled', true); 
         $("#ivrid").prop('disabled', true);
         $("#voicemailid").hide();
         $("#voicemailid").prop('disabled', true);
         $("#forwardersid").hide();
         $("#forwardersid").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid").show();        
         $("#queuesid").prop('disabled', false);
         $("#extensionsid").hide();        
         $("#ivrid").hide();
         $("#extensionsid").prop('disabled', true); 
         $("#ivrid").prop('disabled', true);
         $("#voicemailid").hide();
         $("#voicemailid").prop('disabled', true);
         $("#forwardersid").hide();
         $("#forwardersid").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#forwardersid").show();        
         $("#forwardersid").prop('disabled', false);
         $("#queuesid").hide();        
         $("#queuesid").prop('disabled', true);
         $("#extensionsid").hide();        
         $("#extensionsid").prop('disabled', true); 
         $("#ivrid").hide();
         $("#ivrid").prop('disabled', true);
         $("#voicemailid").hide();
         $("#voicemailid").prop('disabled', true);
      }
      if (selection == "ivr") {
         $("#queuesid").hide();        
         $("#extensionsid").hide();        
         $("#ivrid").show();
         $("#ivrid").prop('disabled', false);
         $("#queuesid").prop('disabled', true);
         $("#extensionsid").prop('disabled', true);
         $("#voicemailid").hide();
         $("#voicemailid").prop('disabled', true);
         $("#forwardersid").hide();
         $("#forwardersid").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid").hide();        
         $("#queuesid").hide();        
         $("#ivrid").hide();
         $("#extensionsid").prop('disabled', true);        
         $("#queuesid").prop('disabled', true);
         $("#ivrid").prop('disabled', true);
         $("#voicemailid").hide();
         $("#voicemailid").prop('disabled', true);
         $("#forwardersid").hide();
         $("#forwardersid").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid").hide();        
         $("#queuesid").hide();        
         $("#ivrid").hide();
         $("#extensionsid").prop('disabled', true);        
         $("#queuesid").prop('disabled', true);
         $("#ivrid").prop('disabled', true);
         $("#voicemailid").hide();
         $("#voicemailid").prop('disabled', true);
         $("#forwardersid").hide();
         $("#forwardersid").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid").hide();        
         $("#queuesid").hide();        
         $("#ivrid").hide();
         $("#extensionsid").prop('disabled', true);        
         $("#queuesid").prop('disabled', true);
         $("#ivrid").prop('disabled', true);
         $("#voicemailid").hide();
         $("#voicemailid").prop('disabled', true);
         $("#forwardersid").hide();
         $("#forwardersid").prop('disabled', true);
      }
      if (selection == "voicemail") {
         $("#extensionsid").hide();        
         $("#queuesid").hide();        
         $("#ivrid").hide();
         $("#extensionsid").prop('disabled', true);        
         $("#queuesid").prop('disabled', true);
         $("#ivrid").prop('disabled', true);
         $("#voicemailid").show();
         $("#voicemailid").prop('disabled', false);
         $("#forwardersid").hide();
         $("#forwardersid").prop('disabled', true);
      }
      if (selection == "hangup") {
         $("#extensionsid").hide();        
         $("#queuesid").hide();        
         $("#ivrid").hide();
         $("#extensionsid").prop('disabled', true);        
         $("#queuesid").prop('disabled', true);
         $("#ivrid").prop('disabled', true);
         $("#voicemailid").hide();
         $("#voicemailid").prop('disabled', true);
         $("#forwardersid").hide();
         $("#forwardersid").prop('disabled', true);
      }
    });

      var selection = $("#destination1").val();
      if (selection == "") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid1").show();        
         $("#extensionsid1").prop('disabled', false);
         $("#queuesid1").hide();        
         $("#queuesid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid1").show();        
         $("#queuesid1").prop('disabled', false);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid1").hide();        
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").show();
         $("#forwardersid1").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid1").hide();        
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();       
         $("#extensionsid1").prop('disabled', true); 
         $("#ivrid1").show();
         $("#ivrid1").prop('disabled', false);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }

      var selection = $("#destination2").val();
      if (selection == "") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid2").show();
         $("#extensionsid2").prop('disabled', false);
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#queuesid2").prop('disabled', true);        
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid2").show();        
         $("#queuesid2").prop('disabled', false);
         $("#extensionsid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid2").hide();
         $("#queuesid2").prop('disabled', true);
         $("#extensionsid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#ivrid2").hide();
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").show();
         $("#forwardersid2").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid2").hide();        
         $("#extensionsid2").hide();        
         $("#ivrid2").show();
         $("#ivrid2").prop('disabled', false);
         $("#queuesid2").prop('disabled', true);
         $("#extensionsid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }

      var selection = $("#destination3").val();
      if (selection == "") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid3").show();        
         $("#extensionsid3").prop('disabled', false);
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#queuesid3").prop('disabled', true);        
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid3").show();        
         $("#queuesid3").prop('disabled', false);
         $("#extensionsid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid3").hide();
         $("#queuesid3").prop('disabled', true);
         $("#extensionsid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#ivrid3").hide();
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").show();
         $("#forwardersid3").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid3").hide();        
         $("#extensionsid3").hide();        
         $("#ivrid3").show();
         $("#ivrid3").prop('disabled', false);
         $("#queuesid3").prop('disabled', true);
         $("#extensionsid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }

      var selection = $("#destination4").val();
      if (selection == "") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid4").show();        
         $("#extensionsid4").prop('disabled', false);
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#queuesid4").prop('disabled', true);        
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid4").show();        
         $("#queuesid4").prop('disabled', false);
         $("#extensionsid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid4").hide();
         $("#queuesid4").prop('disabled', true);
         $("#extensionsid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#ivrid4").hide();
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").show();
         $("#forwardersid4").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid4").hide();        
         $("#extensionsid4").hide();        
         $("#ivrid4").show();
         $("#ivrid4").prop('disabled', false);
         $("#queuesid4").prop('disabled', true);
         $("#extensionsid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }

      var selection = $("#destination5").val();
      if (selection == "") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid5").show();        
         $("#extensionsid5").prop('disabled', false);
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#queuesid5").prop('disabled', true);        
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid5").show();        
         $("#queuesid5").prop('disabled', false);
         $("#extensionsid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid5").hide();
         $("#queuesid5").prop('disabled', true);
         $("#extensionsid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#ivrid5").hide();
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").show();
         $("#forwardersid5").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid5").hide();        
         $("#extensionsid5").hide();        
         $("#ivrid5").show();
         $("#ivrid5").prop('disabled', false);
         $("#queuesid5").prop('disabled', true);
         $("#extensionsid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }

      var selection = $("#destination6").val();
      if (selection == "") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid6").show();        
         $("#extensionsid6").prop('disabled', false);
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#queuesid6").prop('disabled', true);        
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid6").show();        
         $("#queuesid6").prop('disabled', false);
         $("#extensionsid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid6").hide();
         $("#queuesid6").prop('disabled', true);
         $("#extensionsid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#ivrid6").hide();
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").show();
         $("#forwardersid6").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid6").hide();        
         $("#extensionsid6").hide();        
         $("#ivrid6").show();
         $("#ivrid6").prop('disabled', false);
         $("#queuesid6").prop('disabled', true);
         $("#extensionsid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }

      var selection = $("#destination7").val();
      if (selection == "") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid7").show();        
         $("#extensionsid7").prop('disabled', false);
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#queuesid7").prop('disabled', true);        
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid7").show();        
         $("#queuesid7").prop('disabled', false);
         $("#extensionsid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid7").hide();
         $("#queuesid7").prop('disabled', true);
         $("#extensionsid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#ivrid7").hide();
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").show();
         $("#forwardersid7").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid7").hide();        
         $("#extensionsid7").hide();        
         $("#ivrid7").show();
         $("#ivrid7").prop('disabled', false);
         $("#queuesid7").prop('disabled', true);
         $("#extensionsid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }

      var selection = $("#destination8").val();
      if (selection == "") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid8").show();        
         $("#extensionsid8").prop('disabled', false);
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#queuesid8").prop('disabled', true);        
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid8").show();        
         $("#queuesid8").prop('disabled', false);
         $("#extensionsid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid8").hide();
         $("#queuesid8").prop('disabled', true);
         $("#extensionsid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#ivrid8").hide();
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").show();
         $("#forwardersid8").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid8").hide();        
         $("#extensionsid8").hide();        
         $("#ivrid8").show();
         $("#ivrid8").prop('disabled', false);
         $("#queuesid8").prop('disabled', true);
         $("#extensionsid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }

      var selection = $("#destination9").val();
      if (selection == "") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid9").show();        
         $("#extensionsid9").prop('disabled', false);
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#queuesid9").prop('disabled', true);        
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid9").show();        
         $("#queuesid9").prop('disabled', false);
         $("#extensionsid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid9").hide();
         $("#queuesid9").prop('disabled', true);
         $("#extensionsid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#ivrid9").hide();
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").show();
         $("#forwardersid9").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid9").hide();        
         $("#extensionsid9").hide();        
         $("#ivrid9").show();
         $("#ivrid9").prop('disabled', false);
         $("#queuesid9").prop('disabled', true);
         $("#extensionsid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }

      var selection = $("#destination0").val();
      if (selection == "") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid0").show();        
         $("#extensionsid0").prop('disabled', false);
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#queuesid0").prop('disabled', true);        
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid0").show();        
         $("#queuesid0").prop('disabled', false);
         $("#extensionsid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid0").hide();
         $("#queuesid0").prop('disabled', true);
         $("#extensionsid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#ivrid0").hide();
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").show();
         $("#forwardersid0").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid0").hide();        
         $("#extensionsid0").hide();        
         $("#ivrid0").show();
         $("#ivrid0").prop('disabled', false);
         $("#queuesid0").prop('disabled', true);
         $("#extensionsid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }

      var selection = $("#destinationi").val();
      if (selection == "") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsidi").show();        
         $("#extensionsidi").prop('disabled', false);
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#queuesidi").prop('disabled', true);        
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesidi").show();        
         $("#queuesidi").prop('disabled', false);
         $("#extensionsidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesidi").hide();
         $("#queuesidi").prop('disabled', true);
         $("#extensionsidi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#ivridi").hide();
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").show();
         $("#forwardersidi").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesidi").hide();        
         $("#extensionsidi").hide();        
         $("#ivridi").show();
         $("#ivridi").prop('disabled', false);
         $("#queuesidi").prop('disabled', true);
         $("#extensionsidi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }

      var selection = $("#destinationt").val();
      if (selection == "") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsidt").show();        
         $("#extensionsidt").prop('disabled', false);
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#queuesidt").prop('disabled', true);        
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesidt").show();        
         $("#queuesidt").prop('disabled', false);
         $("#extensionsidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesidt").hide();
         $("#queuesidt").prop('disabled', true);
         $("#extensionsidt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#ivridt").hide();
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").show();
         $("#forwardersidt").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesidt").hide();        
         $("#extensionsidt").hide();        
         $("#ivridt").show();
         $("#ivridt").prop('disabled', false);
         $("#queuesidt").prop('disabled', true);
         $("#extensionsidt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }

    $("#destination1").change(function(){
      var selection = $("#destination1").val();
      if (selection == "") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid1").show();        
         $("#extensionsid1").prop('disabled', false);
         $("#queuesid1").hide();        
         $("#queuesid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid1").show();        
         $("#queuesid1").prop('disabled', false);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").show();
         $("#forwardersid1").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid1").hide();        
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();       
         $("#extensionsid1").prop('disabled', true); 
         $("#ivrid1").show();
         $("#ivrid1").prop('disabled', false);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#queuesid1").hide();
         $("#queuesid1").prop('disabled', true);
         $("#extensionsid1").hide();        
         $("#extensionsid1").prop('disabled', true);
         $("#ivrid1").hide();
         $("#ivrid1").prop('disabled', true);
         $("#forwardersid1").hide();
         $("#forwardersid1").prop('disabled', true);
      }
    });

    $("#destination2").change(function(){
      var selection = $("#destination2").val();
      if (selection == "") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid2").show();
         $("#extensionsid2").prop('disabled', false);
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#queuesid2").prop('disabled', true);        
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid2").show();        
         $("#queuesid2").prop('disabled', false);
         $("#extensionsid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid2").hide();
         $("#queuesid2").prop('disabled', true);
         $("#extensionsid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#ivrid2").hide();
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").show();
         $("#forwardersid2").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid2").hide();        
         $("#extensionsid2").hide();        
         $("#ivrid2").show();
         $("#ivrid2").prop('disabled', false);
         $("#queuesid2").prop('disabled', true);
         $("#extensionsid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid2").hide();        
         $("#queuesid2").hide();        
         $("#ivrid2").hide();
         $("#extensionsid2").prop('disabled', true);
         $("#queuesid2").prop('disabled', true);
         $("#ivrid2").prop('disabled', true);
         $("#forwardersid2").hide();
         $("#forwardersid2").prop('disabled', true);
      }
    });

    $("#destination3").change(function(){
      var selection = $("#destination3").val();
      if (selection == "") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid3").show();        
         $("#extensionsid3").prop('disabled', false);
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#queuesid3").prop('disabled', true);        
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid3").show();        
         $("#queuesid3").prop('disabled', false);
         $("#extensionsid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid3").hide();
         $("#queuesid3").prop('disabled', true);
         $("#extensionsid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#ivrid3").hide();
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").show();
         $("#forwardersid3").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid3").hide();        
         $("#extensionsid3").hide();        
         $("#ivrid3").show();
         $("#ivrid3").prop('disabled', false);
         $("#queuesid3").prop('disabled', true);
         $("#extensionsid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid3").hide();        
         $("#queuesid3").hide();        
         $("#ivrid3").hide();
         $("#extensionsid3").prop('disabled', true);
         $("#queuesid3").prop('disabled', true);
         $("#ivrid3").prop('disabled', true);
         $("#forwardersid3").hide();
         $("#forwardersid3").prop('disabled', true);
      }
    });

    $("#destination4").change(function(){
      var selection = $("#destination4").val();
      if (selection == "") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid4").show();        
         $("#extensionsid4").prop('disabled', false);
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#queuesid4").prop('disabled', true);        
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid4").show();        
         $("#queuesid4").prop('disabled', false);
         $("#extensionsid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid4").hide();
         $("#queuesid4").prop('disabled', true);
         $("#extensionsid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#ivrid4").hide();
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").show();
         $("#forwardersid4").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid4").hide();        
         $("#extensionsid4").hide();        
         $("#ivrid4").show();
         $("#ivrid4").prop('disabled', false);
         $("#queuesid4").prop('disabled', true);
         $("#extensionsid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid4").hide();        
         $("#queuesid4").hide();        
         $("#ivrid4").hide();
         $("#extensionsid4").prop('disabled', true);
         $("#queuesid4").prop('disabled', true);
         $("#ivrid4").prop('disabled', true);
         $("#forwardersid4").hide();
         $("#forwardersid4").prop('disabled', true);
      }
    });

    $("#destination5").change(function(){
      var selection = $("#destination5").val();
      if (selection == "") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid5").show();        
         $("#extensionsid5").prop('disabled', false);
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#queuesid5").prop('disabled', true);        
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid5").show();        
         $("#queuesid5").prop('disabled', false);
         $("#extensionsid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid5").hide();
         $("#queuesid5").prop('disabled', true);
         $("#extensionsid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#ivrid5").hide();
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").show();
         $("#forwardersid5").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid5").hide();        
         $("#extensionsid5").hide();        
         $("#ivrid5").show();
         $("#ivrid5").prop('disabled', false);
         $("#queuesid5").prop('disabled', true);
         $("#extensionsid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid5").hide();        
         $("#queuesid5").hide();        
         $("#ivrid5").hide();
         $("#extensionsid5").prop('disabled', true);
         $("#queuesid5").prop('disabled', true);
         $("#ivrid5").prop('disabled', true);
         $("#forwardersid5").hide();
         $("#forwardersid5").prop('disabled', true);
      }
    });

    $("#destination6").change(function(){
      var selection = $("#destination6").val();
      if (selection == "") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid6").show();        
         $("#extensionsid6").prop('disabled', false);
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#queuesid6").prop('disabled', true);        
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid6").show();        
         $("#queuesid6").prop('disabled', false);
         $("#extensionsid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid6").hide();
         $("#queuesid6").prop('disabled', true);
         $("#extensionsid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#ivrid6").hide();
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").show();
         $("#forwardersid6").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid6").hide();        
         $("#extensionsid6").hide();        
         $("#ivrid6").show();
         $("#ivrid6").prop('disabled', false);
         $("#queuesid6").prop('disabled', true);
         $("#extensionsid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid6").hide();        
         $("#queuesid6").hide();        
         $("#ivrid6").hide();
         $("#extensionsid6").prop('disabled', true);
         $("#queuesid6").prop('disabled', true);
         $("#ivrid6").prop('disabled', true);
         $("#forwardersid6").hide();
         $("#forwardersid6").prop('disabled', true);
      }
    });

    $("#destination7").change(function(){
      var selection = $("#destination7").val();
      if (selection == "") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid7").show();        
         $("#extensionsid7").prop('disabled', false);
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#queuesid7").prop('disabled', true);        
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid7").show();        
         $("#queuesid7").prop('disabled', false);
         $("#extensionsid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid7").hide();
         $("#queuesid7").prop('disabled', true);
         $("#extensionsid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#ivrid7").hide();
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").show();
         $("#forwardersid7").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid7").hide();        
         $("#extensionsid7").hide();        
         $("#ivrid7").show();
         $("#ivrid7").prop('disabled', false);
         $("#queuesid7").prop('disabled', true);
         $("#extensionsid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid7").hide();        
         $("#queuesid7").hide();        
         $("#ivrid7").hide();
         $("#extensionsid7").prop('disabled', true);
         $("#queuesid7").prop('disabled', true);
         $("#ivrid7").prop('disabled', true);
         $("#forwardersid7").hide();
         $("#forwardersid7").prop('disabled', true);
      }
    });

    $("#destination8").change(function(){
      var selection = $("#destination8").val();
      if (selection == "") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid8").show();        
         $("#extensionsid8").prop('disabled', false);
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#queuesid8").prop('disabled', true);        
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid8").show();        
         $("#queuesid8").prop('disabled', false);
         $("#extensionsid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid8").hide();
         $("#queuesid8").prop('disabled', true);
         $("#extensionsid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#ivrid8").hide();
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").show();
         $("#forwardersid8").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid8").hide();        
         $("#extensionsid8").hide();        
         $("#ivrid8").show();
         $("#ivrid8").prop('disabled', false);
         $("#queuesid8").prop('disabled', true);
         $("#extensionsid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid8").hide();        
         $("#queuesid8").hide();        
         $("#ivrid8").hide();
         $("#extensionsid8").prop('disabled', true);
         $("#queuesid8").prop('disabled', true);
         $("#ivrid8").prop('disabled', true);
         $("#forwardersid8").hide();
         $("#forwardersid8").prop('disabled', true);
      }
    });

    $("#destination9").change(function(){
      var selection = $("#destination9").val();
      if (selection == "") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid9").show();        
         $("#extensionsid9").prop('disabled', false);
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#queuesid9").prop('disabled', true);        
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid9").show();        
         $("#queuesid9").prop('disabled', false);
         $("#extensionsid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid9").hide();
         $("#queuesid9").prop('disabled', true);
         $("#extensionsid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#ivrid9").hide();
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").show();
         $("#forwardersid9").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid9").hide();        
         $("#extensionsid9").hide();        
         $("#ivrid9").show();
         $("#ivrid9").prop('disabled', false);
         $("#queuesid9").prop('disabled', true);
         $("#extensionsid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid9").hide();        
         $("#queuesid9").hide();        
         $("#ivrid9").hide();
         $("#extensionsid9").prop('disabled', true);
         $("#queuesid9").prop('disabled', true);
         $("#ivrid9").prop('disabled', true);
         $("#forwardersid9").hide();
         $("#forwardersid9").prop('disabled', true);
      }
    });

    $("#destination0").change(function(){
      var selection = $("#destination0").val();
      if (selection == "") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsid0").show();        
         $("#extensionsid0").prop('disabled', false);
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#queuesid0").prop('disabled', true);        
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesid0").show();        
         $("#queuesid0").prop('disabled', false);
         $("#extensionsid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesid0").hide();
         $("#queuesid0").prop('disabled', true);
         $("#extensionsid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#ivrid0").hide();
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").show();
         $("#forwardersid0").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesid0").hide();        
         $("#extensionsid0").hide();        
         $("#ivrid0").show();
         $("#ivrid0").prop('disabled', false);
         $("#queuesid0").prop('disabled', true);
         $("#extensionsid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsid0").hide();        
         $("#queuesid0").hide();        
         $("#ivrid0").hide();
         $("#extensionsid0").prop('disabled', true);
         $("#queuesid0").prop('disabled', true);
         $("#ivrid0").prop('disabled', true);
         $("#forwardersid0").hide();
         $("#forwardersid0").prop('disabled', true);
      }
    });

    $("#destinationi").change(function(){
      var selection = $("#destinationi").val();
      if (selection == "") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsidi").show();        
         $("#extensionsidi").prop('disabled', false);
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#queuesidi").prop('disabled', true);        
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesidi").show();        
         $("#queuesidi").prop('disabled', false);
         $("#extensionsidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesidi").hide();
         $("#queuesidi").prop('disabled', true);
         $("#extensionsidi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#ivridi").hide();
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").show();
         $("#forwardersidi").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesidi").hide();        
         $("#extensionsidi").hide();        
         $("#ivridi").show();
         $("#ivridi").prop('disabled', false);
         $("#queuesidi").prop('disabled', true);
         $("#extensionsidi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsidi").hide();        
         $("#queuesidi").hide();        
         $("#ivridi").hide();
         $("#extensionsidi").prop('disabled', true);
         $("#queuesidi").prop('disabled', true);
         $("#ivridi").prop('disabled', true);
         $("#forwardersidi").hide();
         $("#forwardersidi").prop('disabled', true);
      }
    });

    $("#destinationt").change(function(){
      var selection = $("#destinationt").val();
      if (selection == "") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "extensions") {
         $("#extensionsidt").show();        
         $("#extensionsidt").prop('disabled', false);
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#queuesidt").prop('disabled', true);        
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "queues") {
         $("#queuesidt").show();        
         $("#queuesidt").prop('disabled', false);
         $("#extensionsidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "forwarders") {
         $("#queuesidt").hide();
         $("#queuesidt").prop('disabled', true);
         $("#extensionsidt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#ivridt").hide();
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").show();
         $("#forwardersidt").prop('disabled', false);
      }
      if (selection == "ivr") {
         $("#queuesidt").hide();        
         $("#extensionsidt").hide();        
         $("#ivridt").show();
         $("#ivridt").prop('disabled', false);
         $("#queuesidt").prop('disabled', true);
         $("#extensionsidt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "timeconditions") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "cloudcall") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "operator") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
      if (selection == "delete") {
         $("#extensionsidt").hide();        
         $("#queuesidt").hide();        
         $("#ivridt").hide();
         $("#extensionsidt").prop('disabled', true);
         $("#queuesidt").prop('disabled', true);
         $("#ivridt").prop('disabled', true);
         $("#forwardersidt").hide();
         $("#forwardersidt").prop('disabled', true);
      }
    });

    $("#PbxForm1").hide();
    $("#PbxForm2").hide();
    $("#PbxForm").show();
    $("#menu1").addClass("active");

    $('ul li a').click(function(){
      $('li a').removeClass("active");
      $(this).addClass("active");
    });
    $("#menu1").click(function() { //event called
      $("#PbxForm1").hide();
      $("#PbxForm2").hide();
      $("#PbxForm").show();
    });
    $("#menu2").click(function() { //event called
      $("#PbxForm").hide();
      $("#PbxForm2").hide();
      $("#PbxForm1").show();
    });
    $("#menu3").click(function() { //event called
      $("#PbxForm").hide();
      $("#PbxForm1").hide();
      $("#PbxForm2").show();
    });
    $("#submitButton").click(function() {
        $.post("index.php?mod=add_rt_extensions_details", $("#PbxForm, #PbxForm1, #PbxForm2").serialize(), function(){
           window.location.href = "index.php?mod=rt_extensions";
        });
    });

    $("#submitButton2").click(function() {
        $.post("index.php?mod=modify_rt_extensions_details", $("#PbxForm, #PbxForm1, #PbxForm2").serialize(), function(){
           window.location.href = "index.php?mod=rt_extensions";
        });
    });

    var fileName;
    $('input[type="file"]').change(function(e){
        fileName = e.target.files[0].name;
        //alert('The file "' + fileName +  '" has been selected.');
    });

    $("#queuesubmit").click(function() {
        var name_data = $('#name').val();

        var record_data = '0';
        $.each($("input[name='recordrequest']:checked"), function(){
           record_data = $(this).val();
        });

        var agents_data = '0';
        $.each($("input[name='dynamicagents']:checked"), function(){
           agents_data = $(this).val();
        });

        var file_data = $('#periodic_announce').prop('files')[0];
        var form_data = new FormData();
        form_data.append('name', name_data);
        form_data.append('recordrequest', record_data);
        form_data.append('dynamicagents', agents_data);
        form_data.append('periodic_announce', file_data);
        $.ajax({
	    url: 'modules/queues/queues_exec.php', // point to server-side PHP script
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(php_script_response){
                   alert(php_script_response); // display response from the PHP script, if any
            }
        });

        $.post("index.php?mod=add_queues_details", $("#PbxForm, #PbxForm1").serialize() + '&periodic_announce=' + fileName, function(){
           window.location.href = "index.php?mod=queues&alert="+name_data;
        });
    });

    $("#queuesubmit2").click(function() {
        var name_data = $('#name').val();

        var record_data = '0';
        $.each($("input[name='recordrequest']:checked"), function(){
           record_data = $(this).val();
        });

        var agents_data = '0';
        $.each($("input[name='dynamicagents']:checked"), function(){
           agents_data = $(this).val();
        });

        var file_data = $('#periodic_announce').prop('files')[0];
        var form_data = new FormData();
        form_data.append('name', name_data);
        form_data.append('recordrequest', record_data);
        form_data.append('dynamicagents', agents_data);
        form_data.append('periodic_announce', file_data);
        $.ajax({
	    url: 'modules/queues/queues_exec.php', // point to server-side PHP script
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(php_script_response){
                alert(php_script_response); // display response from the PHP script, if any
            }
        });

        $.post("index.php?mod=modify_queues_details", $("#PbxForm, #PbxForm1").serialize() + '&periodic_announce=' + fileName, function(){
           window.location.href = "index.php?mod=queues&alert="+name_data;
        });
    });
});
 
