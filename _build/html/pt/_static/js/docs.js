jQuery(document).ready(function($) {
    
    var actionContent = $('#action-content'),
		h1 = $('h1');
	
    var prev = {
        link:  'http://www.ppi.io/docs/' + actionContent.data('prevlink'),
        title: actionContent.data('prevpage')
    };
	
	var next = {
        link:  'http://www.ppi.io/docs/' + actionContent.data('nextlink'),
        title: actionContent.data('nextpage')
    };
    
    var newhtml = '<div class="section-subbar clearfix">';
    if(prev.link !== undefined) {
        newhtml += 
        '<a class="prev-page btn" title="Getting Started" href="' + prev.link + '">' +
                '<img src="../_static/images/previous-page.png" alt="Previous">&nbsp;&nbsp;' + prev.title + 
        '</a>';
    }
	
	newhtml += '<h1>' + h1.html() + '</h1>';
	
	if(next.link !== undefined) {
		newhtml += 
			'<a class="next-page btn" title="Modules" href="' + next.link + '">' +
		        '' + next.title + '&nbsp;&nbsp;<img src="../_static/images/next-page.png" alt="Next">' +
			'</a>';
	}
	
	newhtml += '</div>';
	
    $(newhtml).insertAfter(h1);
    
    h1.hide();
    
});