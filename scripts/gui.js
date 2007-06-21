
/**
 * Inplace tag editing
 */
function tagedit() {
    var txt  = document.createElement('textarea');
    txt.id   = 'tagedit_editor';
    txt.name = 'marker';
    txt.className = 'ipe';
    $(txt).load('ajax.php',{loadtags: 'plain', dn: DN});
    $(txt).Autocomplete({
        source: 'ajax.php',
        delay: 300,
        helperClass: 'autocompleter',
        selectClass: 'autocompleterSelect',
        inputWidth: true,
        minchars: 1,
        multiple: true,
        multipleSeperator: ','
    });

    var save       = new Image();
    save.src       = 'pix/accept.png';
    save.id        = 'tagedit_save';
    save.className = 'click';
    $(save).click(function(){
        $('#taglist').load('ajax.php',{settags: $('#tagedit_editor').val(), dn: DN});
        $('#tagedit_save').remove();
        $('#tagedit_cancel').remove();
        $('#tagedit_start').show();
    });

    var canc       = new Image();
    canc.src       = 'pix/cancel.png';
    canc.id        = 'tagedit_cancel';
    canc.className = 'click';
    $(canc).click(function(){
        $('#taglist').load('ajax.php',{loadtags: 'html', dn: DN});
        $('#tagedit_save').remove();
        $('#tagedit_cancel').remove();
        $('#tagedit_start').show();
    });

    $('#tagedit_start').hide();
    $('#taglist').empty().prepend(txt);
    $('#tagedit').append(save);
    $('#tagedit').append(canc);
    $('#tagedit_editor').focus();
}

/**
 * Inplace note adding
 */
function noteedit(type){
    var txt  = document.createElement('textarea');
    txt.id   = 'noteedit_editor';
    txt.className = 'ipe';

    // prepare text
    var text = '';
    if(type=='call'){
        text += '**Call** ';
    }else if(type=='mail'){
        text += '**Mail** ';
    }else if(type=='todo'){
        text += '**Todo** ';
    }else if(type=='note'){
        text += '**Note** ';
    }
    var dt = new Date();
    text += '//'+dt.formatDate('j. M y H:i')+' '+USER+'//: ';
    $(txt).val(text);

    var save       = new Image();
    save.src       = 'pix/accept.png';
    save.id        = 'noteedit_save';
    save.className = 'click';
    $(save).click(function(){
        $('#notes').load('ajax.php',{addnote: $('#noteedit_editor').val(), dn: DN});
        $('#noteedit_editor').remove();
        $('#noteedit_save').remove();
        $('#noteedit_cancel').remove();
        $('#noteedit .ed').show()
    });

    var canc       = new Image();
    canc.src       = 'pix/cancel.png';
    canc.id        = 'noteedit_cancel';
    canc.className = 'click';
    $(canc).click(function(){
        $('#noteedit_editor').remove();
        $('#noteedit_save').remove();
        $('#noteedit_cancel').remove();
        $('#noteedit .ed').show();
    });

    $('#notes').prepend(txt);
    $('#noteedit .ed').hide();
    $('#noteedit').append(save);
    $('#noteedit').append(canc);
    $('#noteedit_editor').focus();
}

/**
 * Initialize everything when DOM is ready
 */
$(document).ready(function() {

    // autocompletion
    $('#taglookup').Autocomplete({
        source: 'ajax.php',
        delay: 300,
        helperClass: 'autocompleter',
        selectClass: 'autocompleterSelect',
        inputWidth: true,
        minchars: 1,
        //multiple: true,
        //multipleSeperator: ','
    });
    $('#tageditlookup').Autocomplete({
        source: 'ajax.php',
        delay: 300,
        helperClass: 'autocompleter',
        selectClass: 'autocompleterSelect',
        inputWidth: true,
        minchars: 1,
        multiple: true,
        multipleSeperator: ','
    });
    // autocompletion
    $('input.ac').Autocomplete({
        source: 'ajax.php',
        delay: 300,
        helperClass: 'autocompleter',
        selectClass: 'autocompleterSelect',
        inputWidth: true,
        minchars: 1,
    });

    // nice images
    $.ImageBox.init({
        loaderSRC: 'pix/imagebox/loading.gif',
        closeHTML: '<img src="pix/imagebox/close.jpg" border="0" />'
    });

    // tag editing
    if($('#tagedit').length){
        var img       = new Image();
        img.src       = 'pix/tag_blue_edit.png';
        img.className = 'click';
        img.id        = 'tagedit_start';
        $('#tagedit').empty().after(img);
        $(img).click(tagedit)
    }

    // note editing
    if($('#noteedit').length){
        var img;

        img           = new Image();
        img.src       = 'pix/note.png';
        img.className = 'click ed';
        $(img).click(function(){noteedit('note');});
        $('#noteedit').append(img);

        img           = new Image();
        img.src       = 'pix/arrow_right.png';
        img.className = 'click ed';
        $(img).click(function(){noteedit('todo');});
        $('#noteedit').append(img);

        img           = new Image();
        img.src       = 'pix/email.png';
        img.className = 'click ed';
        $(img).click(function(){noteedit('mail');});
        $('#noteedit').append(img);

        img           = new Image();
        img.src       = 'pix/phone.png';
        img.className = 'click ed';
        $(img).click(function(){noteedit('call');});
        $('#noteedit').append(img);
    }


    // set focus
    if($('#searchfield').length) $('#searchfield').focus();
    if($('#firstfield').length) $('#firstfield').focus();


    // run google maps loader
    if($('#google_map').length){
        gmap_loader();
        $(document).unload(GUnload);
    }
});
