
function tedit_showEditor(dn){
  // if the editor already exists cancel it
  if($('tedit_editor') !== null){
    tedit_cleanUp();
    return;
  }

  var tags = $('tedit_out').innerHTML;
  tags = tags.replace(/>[ \n\r]+<a/g,'>, <a');
  tags = tags.stripTags();
  tags = tags.replace(/[ \n\r]{2,}/g,' ');
  tags = tags.replace(/^ /,'');
  tags = tags.replace(/ $/,'');

  var editor  = '<div id="tedit_editor"><form accept-charset="utf-8">';
      editor += '<textarea id="tedit_edit">'+tags+'</textarea>';
      editor += '<div id="tedit_completion" class="autocomplete"></div>';
      editor += '<img src="pix/accept.png" width="16" height="16" id="tedit_save" class="click" alt="save" /><br />';
      editor += '<img src="pix/cancel.png" width="16" height="16" id="tedit_cancel" class="click" alt="cancel" />';
      editor += '</form></div>';

  Element.hide('tedit_out');
  new Insertion.Top($('tedit_insert'), editor);

  new Ajax.Autocompleter('tedit_edit','tedit_completion', 'ajax.php', {paramName: 'taglookup', tokens: ','});

  Event.observe('tedit_save', 'click', function(){ tedit_saveChanges(dn) }, false);
  Event.observe('tedit_cancel', 'click', tedit_cleanUp, false);
  $('tedit_edit').focus();
}

function tedit_cleanUp(){
  Element.remove('tedit_editor');
  Element.show('tedit_out');
}

function tedit_saveChanges(dn){
  var content = encodeURI($F('tedit_edit'));
  dn = encodeURI(dn);

  $('tedit_editor').innerHTML = "Saving...";

  var success = function(resp){tedit_complete(resp)};
  var failure = function(resp){tedit_failed(resp)};
  var pars    = 'settags='+dn+'&tags='+content;

  var ajax = new Ajax.Request('ajax.php', {method:'post',postBody:pars,onSuccess:success,onFailure:failure});
}

function tedit_complete(resp){
  $('tedit_out').innerHTML = resp.responseText;
  tedit_cleanUp();
}

function tedit_failed(resp){
  tedit_cleanUp();
  alert("Saving failed.");
}



// --------------------------------------------------------------------

/**
 * Create the editor component
 */
function nedit_showEditor(type,dn,name){
  // if the editor already exists cancel it
  if($('nedit_editor') !== null){
    nedit_cleanUp();
    return;
  }

  var editor = '<div id="nedit_editor"><form accept-charset="utf-8">';
  editor += '<textarea id="nedit_edit">';
  if(type=='call'){
    editor += '**Call** ';
  }else if(type=='mail'){
    editor += '**Mail** ';
  }else if(type=='todo'){
    editor += '**Todo** ';
  }else if(type=='note'){
    editor += '**Note** ';
  }

  var dt = new Date();
  editor += '//'+dt.formatDate('j. M y H:i')+' '+name+'//: ';
  editor += '</textarea>';

  editor += '<img src="pix/accept.png" width="16" height="16" id="nedit_save" class="click" alt="save" /><br />';
  editor += '<img src="pix/cancel.png" width="16" height="16" id="nedit_cancel" class="click" alt="cancel" />';
  editor += '</form></div>';

  new Insertion.Top($('nedit_insert'), editor);

  Event.observe('nedit_save', 'click', function(){ nedit_saveChanges(dn) }, false);
  Event.observe('nedit_cancel', 'click', nedit_cleanUp, false);
  $('nedit_edit').focus();
};

function nedit_cleanUp(){
  Element.remove('nedit_editor');
}

function nedit_saveChanges(dn){
  var content = encodeURI($F('nedit_edit'));
  dn = encodeURI(dn);
  
  $('nedit_editor').innerHTML = "Saving...";

  var success = function(resp){nedit_complete(resp)};
  var failure = function(resp){nedit_failed(resp)};
  var pars    = 'addnote='+dn+'&note='+content;

  var ajax = new Ajax.Request('ajax.php', {method:'post',postBody:pars,onSuccess:success,onFailure:failure});
}

function nedit_complete(resp){
  nedit_cleanUp();
  $('nedit_insert').innerHTML = resp.responseText;
}

function nedit_failed(resp){
  nedit_cleanUp();
  alert("Saving failed.");
}



/**
 * initializes all the JS GUI-Stuff
 */
function init(){
  if($('taglookup') !== null)
    new Ajax.Autocompleter('taglookup','tagresult', 'ajax.php', {paramName: 'taglookup', tokens: ','});
  if($('tageditlookup') !== null)
    new Ajax.Autocompleter('tageditlookup','tageditresult', 'ajax.php', {paramName: 'taglookup', tokens: ','});

}

Event.observe(window, 'load', init, false);

