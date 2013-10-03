<?php
/**
 * Configures fieldname - LDAP attribute associations
 *
 * If you use other attributes you may change the assignments here.
 * Note the arrays need to remain flippable, eg. both sides have to
 * be unique
 *
 * Fields starting with a * may contain multiple values (needs to be
 * handled by the template as well)
 */

global $conf;

/**
 * The object classes to store with contacts
 */
$OCLASSES = $conf['oclasses'];

/**
 * The standard fields suported by OpenLDAP's default schemas
 */
$FIELDS = array(
    'dn'           => 'dn',                          // don't touch!
    'name'         => 'sn',
    'displayname'  => 'cn',
    'givenname'    => 'givenName',
    'title'        => 'title',
    'organization' => 'o',                           // aka. company
    'office'       => 'physicalDeliveryOfficeName',
    'street'       => 'postalAddress',
    'zip'          => 'postalCode',
    'location'     => 'l',                           // aka. city
    'phone'        => 'telephoneNumber',
    'fax'          => 'facsimileTelephoneNumber',
    'mobile'       => 'mobile',                      // aka. cell phone
    'pager'        => 'pager',
    'homephone'    => 'homePhone',
    'homestreet'   => 'homePostalAddress',
    'photo'        => 'jpegPhoto',
    '_url'         => 'labeledURI',
    'note'         => 'description',
    'manager'      => 'manager',                     // aka. key account
    '_mail'        => 'mail',
);

/**
 * If the provided "extended" schema is used the following fields
 * and object classes are added
 */
if (array_search('contactPerson', $conf['oclasses']) !== false) {
    $FIELDS['anniversary']  = 'anniversary';
    $FIELDS['_marker']      = 'marker';                  // aka. tags
    $FIELDS['country']      = 'c';
}

/**
 * If the open exchange schema is used the following fields
 * and object classes are added
 */
if (array_search('OXUserObject', $conf['oclasses']) !== false) {
    $FIELDS['country']          = 'userCountry';
    $FIELDS['birthday']         = 'birthDay';
    $FIELDS['ipphone']          = 'IPPhone';
    $FIELDS['_marker']          = 'OXUserCategories';
    $FIELDS['instantmessenger'] = 'OXUserInstantMessenger';
    $FIELDS['timezone']         = 'OXTimeZone';
    $FIELDS['position']         = 'OXUserPosition';
    $FIELDS['certificate']      = 'relClientCert';
    $FIELDS['domain']           = 'domain';
}

/**
 * If the Evolution schema is used the following fields
 * and object classes are added
 */
if (array_search('evolutionPerson', $conf['oclasses']) !== false) {
    $FIELDS['anniversary'] = 'anniversary';
    $FIELDS['department']  = 'ou';
    $FIELDS['state']       = 'st';
    $FIELDS['phone']       = 'primaryPhone';
    $FIELDS['switchboard'] = 'companyPhone';
    $FIELDS['note']        = 'note';
    $FIELDS['manager']     = 'seeAlso';
    $FIELDS['birthday']    = 'birthDate';
    $FIELDS['spouse']      = 'spouseName';
    $FIELDS['_marker']     = 'categories'; // aka. tags
}

// add custom fields from config
$FIELDS = array_merge($FIELDS, $conf['customFields']);

/**
 * Flip the array
 */
$RFIELDS = array_flip($FIELDS);

