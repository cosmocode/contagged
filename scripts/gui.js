




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

  $.ImageBox.init({
        loaderSRC: 'pix/imagebox/loading.gif',
        closeHTML: '<img src="pix/imagebox/close.jpg" border="0" />'
  });

  // set focus
  if($('#searchfield')) $('#searchfield').focus();
  if($('#firstfield')) $('#firstfield').focus();
});
