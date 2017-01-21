"use strict";

function onNewPostSubmit(event) {
  event.preventDefault();

  var title = $(this).find('#input-title').val();
  var body = $(this).find('#input-body').val();

  $.ajax({
    type: 'POST',
    url: '/api/posts',
    data: JSON.stringify({title: title, body: body}),
    dataType: 'json',
    success: reloadPosts,
  })
}

function reloadPosts() {
  $.ajax({
    type: 'GET',
    url: '/api/posts',
    headers: {
      'Accept': 'text/html',
    },
    success: function (data) {
      $(".posts").html(data);
    },
  });
}

$(document).ready(function() {
  $("#form-newpost").submit(onNewPostSubmit);
  reloadPosts();
});
