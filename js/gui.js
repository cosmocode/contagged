

/**
 * Note Editor Class
 */

/*
NoteEditor.prototype = {

  initialize: function(ident,type,controlId){
    this.type  = type;
    this.ident = ident;
    this.ctl   = $(controlId);
    // add click handler to control
    Event.observe(controlId, 'click', this.edit, false);
  },
*/


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
  editor += '</textarea><br />';

  editor += '<input id="nedit_save" type="button" value="SAVE" />';
  editor += '<input id="nedit_cancel" type="button" value="CANCEL" />';
  editor += '</form></div>';

  new Insertion.Top($('nedit_insert'), editor);

  Event.observe('nedit_save', 'click', function(){ nedit_saveChanges(dn) }, false);
  Event.observe('nedit_cancel', 'click', nedit_cleanUp, false);

//  $('nedit_edit').scrollIntoView();
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

