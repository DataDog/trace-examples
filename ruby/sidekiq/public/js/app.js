"use strict";

function onNewJobSubmit(event) {
  event.preventDefault();

  var duration = parseInt($(this).find('#input-duration').val());

  $.ajax({
    type: 'POST',
    url: '/api/jobs',
    data: JSON.stringify({duration: duration}),
    dataType: 'json',
    success: reloadJobs,
  })
}

function reloadJobs() {
  $.ajax({
    type: 'GET',
    url: '/api/jobs',
    headers: {
      'Accept': 'text/html',
    },
    success: function (data) {
      $(".jobs").html(data);
    },
  });
}

$(document).ready(function() {
  $("#form-newjob").submit(onNewJobSubmit);

  reloadJobs();
  window.setInterval(reloadJobs, 5000);
});
