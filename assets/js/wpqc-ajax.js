jQuery(function($){
  $('#wpqc-form').on('submit', function(e){
    e.preventDefault();
    var data = $(this).serialize();
    data += '&action=wpqc_submit&nonce=' + wpqc.nonce;
    $.post(wpqc.ajax_url, data, function(res){
      alert(res.success ? res.data.message : (res.data.message || 'Error'));
      if (res.success) e.target.reset();
    });
  });
});
