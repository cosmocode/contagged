<?

  // Which LDAP Server to use?
  $conf[ldapserver]  = 'ldap';

  // What is the root dn on this Server?
  $conf[ldaprootdn]  = 'o=cosmocode, c=de';

  // Where are the user Accounts stored?
  $conf[usertree]    = 'ou=people, '.$conf[ldaprootdn];

  // How to match users? %u is replaced by the given login
  $conf[userfilter]  = '(&(uid=%u)(objectClass=posixAccount))';

  // Which language to use (see lang directory)
  $conf[lang]        = 'de';

  // Where to store public contacts?
  $conf[publicbook]  = 'ou=contacts, '.$conf[ldaprootdn];

  // Where to store private contacts (relative to $conf[usertree])
  $conf[privatebook] = 'ou=contacts';

  // Should the additional schema ldapab.schema be used? (0|1)
  $conf[extended]    = 1;

  // Dateformat for birthdays when using extended schema
  // see http://www.php.net/manual/en/function.strftime.php
  #$conf[dateformat]  = '%Y/%m/%d';
  $conf[dateformat]  = '%d.%m.%Y';

  // Force recompilation of smarty templates?
  $conf[smartycompile] = 0;
?>
