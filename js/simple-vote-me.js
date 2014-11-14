function simplevotemeaddvote(postId, tipo){
    if(tipo == 1)
        tipo = 'positive';
    else if (tipo == 2)
        tipo = 'neutral';
    else
        tipo = 'negative';
    jQuery.ajax({
    type: 'POST',
    url: gtsimplevotemeajax.ajaxurl,
    data: {
    action: 'simplevoteme_add' + tipo,
    postid: postId
},
 
success:function(data, textStatus, XMLHttpRequest){
 
    var linkid = '#simplevoteme-' + postId;
    jQuery(linkid).html('');
    jQuery(linkid).append(data);
    },
    error: function(MLHttpRequest, textStatus, errorThrown){
        console.log(errorThrown);
        }
    });
}

