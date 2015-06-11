function simplevotemeaddvote(postId, tipo, userID){
    jQuery.ajax({
    type: 'POST',
    url: gtsimplevotemeajax.ajaxurl,
    data: {
    action: 'simplevoteme_addvote',
    tipo: tipo,
    postid: postId,
    userid: userID
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

