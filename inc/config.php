<?php

  // LDAP URL; if set, takes precedence over ldapserver and ldapport
  #$conf['ldapurl']     = 'ldaps://ldap.example.com/';

  // Which LDAP Server to use?
  $conf['ldapserver']  = 'ldap';

  // Which LDAP Port Server to use? (389 is standard, 636 for ssl)
  $conf['ldapport']    = 389;

  // Use LDAP protocol version 3? (0|1)
  $conf['ldapv3'] = 1;

  // What is the root dn on this Server?
  $conf['ldaprootdn']  = 'o=cosmocode, c=de';

  // Where are the user Accounts stored?
  $conf['usertree']    = 'ou=people, '.$conf['ldaprootdn'];

  // How to match users? %u is replaced by the given login
  $conf['userfilter']  = '(&(uid=%u)(objectClass=posixAccount))';

  // Show the users as contacts, too?
  $conf['displayusertree'] = 0;

  // Use these values to bind to the ldap directory when not logged in (leave blank for anonymous bind)
  $conf['anonbinddn']  = '';
  $conf['anonbindpw']  = '';

  // Which language to use (see lang directory)
  $conf['lang']        = 'en';

  // Where to store public contacts?
  $conf['publicbook']  = 'ou=contacts, '.$conf['ldaprootdn'];

  // Where to store private contacts (relative to $conf['usertree'])
  $conf['privatebook'] = 'ou=contacts';

  // What fields to look at when searching?
  $conf['searchfields'] = array('uid','mail','name','givenname','o');

  // Should the public address book be viewable by logged in users only? (0|1)
  $conf['userlogreq']  = 0;

  // Should we try to login using the username and password provided by httpd? (0|1)
  $conf['httpd_auth']  = 0;

  // Store the first 4 tags in Thunderbird's custom fields when using ldapab.schema
  $conf['tbtaghack']   = 1;

  // Dateformat for birthdays and anniversary
  // see http://www.php.net/manual/en/function.strftime.php
  $conf['dateformat']  = '%Y/%m/%d';
  #$conf['dateformat']  = '%d.%m.%Y';

  // Google maps key, you can specify multiple when running the app through different vhosts
  $conf['gmaps'] = array(
    'localhost'        => 'ABQIAAAAwcgTzX14Lq48uGhuAgaC-RT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSoniRWQPYZHIWuWPbij8hFqvrEuw',
    'intranet.cosmo'   => 'ABQIAAAAwcgTzX14Lq48uGhuAgaC-RTxyuybgLnXtMVD7dljhze3zUboVhTqk9yc-rQVvv2YwFFJN20RCNbIVA',
    'intranet'         => 'ABQIAAAAwcgTzX14Lq48uGhuAgaC-RTjlGRJ-JcA4ENdYSxSTUELqnaldxSOyZdbUNylw_BZHH1bBLrQNGtjZg',
    'fileserver.cosmo' => 'ABQIAAAAwcgTzX14Lq48uGhuAgaC-RQ2oMD1p7-NjsUZiDyjvzpK3IuhixTXdHGQRp8jtjwAl-P4oPPEB_hGgw',
  );

  // Force recompilation of smarty templates?
  $conf['smartycompile'] = 0;


