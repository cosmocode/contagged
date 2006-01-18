
/**
 * initializes all the JS GUI-Stuff
 */
function init(){
  new Ajax.Autocompleter('taglookup','tagresult', 'ajax.php', {paramName: 'taglookup', tokens: ','});
  new Ajax.Autocompleter('tageditlookup','tageditresult', 'ajax.php', {paramName: 'taglookup', tokens: ','});
}



Event.observe(window, 'load', init, false);

