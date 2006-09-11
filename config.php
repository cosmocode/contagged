<?

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

  // Use these values to bind to the ldap directory when not logged in (leave blank for anonymous bind)
  $conf['anonbinddn']  = '';
  $conf['anonbindpw']  = '';

  // Which language to use (see lang directory)
  $conf['lang']        = 'en';

  // Where to store public contacts?
  $conf['publicbook']  = 'ou=contacts, '.$conf['ldaprootdn'];

  // Where to store private contacts (relative to $conf['usertree'])
  $conf['privatebook'] = 'ou=contacts';

  // Should the public address book be viewable by logged in users only? (0|1)
  $conf['userlogreq']  = 0;

  // Should the additional schema ldapab.schema be used? (0|1)
  // Note: openxchange and extended are currently exclusive, do not use both at the same time!
  $conf['extended']    = 1;

  // Should we use some parts of the openxchange.schema? (0|1)
  // Note: openxchange and extended are currently exclusive, do not use both at the same time!
  $conf['openxchange'] = 0;

  // Should we try to login using the username and password provided by httpd? (0|1)
  $conf['httpd_auth']  = 0;

  // Dateformat for birthdays when using extended schema
  // see http://www.php.net/manual/en/function.strftime.php
  #$conf['dateformat']  = '%Y/%m/%d';
  $conf['dateformat']  = '%d.%m.%Y';

  // Force recompilation of smarty templates?
  $conf['smartycompile'] = 0;


