#!/usr/bin/perl -w

use strict;


=head1 ldif to contagged

=head1 usage

ldif2contagged.pl < exported.addressbook.from.thunderbird.mozilla.ldif

cd split

for i in *;do ldapadd -w mypass -x -D "cn=Manager,dc=example,dc=com" -f $i -H ldap://ldap.server.example.com ;done

for i in *;do echo $i;ldapmodify -w mypass -x -D "cn=Manager,dc=example,dc=com" -f $i -H ldap://ldap.server.example.com ;done

=head1 modification made to contagged 


contagged/inc/lang/en.php :
-$lang['country']          = 'Land';
+$lang['country']          = 'Country';



contagged/inc/fields.php 
# added departement field, country
# street remapped to street

+    'department'   => 'ou',                           // aka. unit

-    'street'       => 'postalAddress',
+    'street'       => 'street',
+    'country'      => 'c',                           



=head1 modification made to ldap schemas

core.schema:
# to get longer country name (ie usa, canada instead of ca, us)

 attributetype ( 2.5.4.6 NAME ( 'c' 'countryName' )
        DESC 'RFC2256: ISO-3166 country 2-letter code'
-       SUP name SINGLE-VALUE )
+    EQUALITY caseIgnoreMatch
+    SUBSTR caseIgnoreSubstringsMatch
+    SYNTAX 1.3.6.1.4.1.1466.115.121.1.15{128} )


# to get phones numbers with extensions, free writing

 attributetype ( 2.5.4.20 NAME 'telephoneNumber'
-       DESC 'RFC2256: Telephone Number'
-       EQUALITY telephoneNumberMatch
-       SUBSTR telephoneNumberSubstringsMatch
-       SYNTAX 1.3.6.1.4.1.1466.115.121.1.50{32} )
+   DESC 'RFC2256: Telephone Number'
+   EQUALITY caseIgnoreMatch
+   SUBSTR caseIgnoreSubstringsMatch
+   SYNTAX 1.3.6.1.4.1.1466.115.121.1.15 )


# add country to persons

 objectclass ( 2.5.6.7 NAME 'organizationalPerson'
        DESC 'RFC2256: an organizational person'
        SUP person STRUCTURAL
        MAY ( title $ x121Address $ registeredAddress $ destinationIndicator $
                preferredDeliveryMethod $ telexNumber $ teletexTerminalIdentifier $
               telephoneNumber $ internationaliSDNNumber $ 
                facsimileTelephoneNumber $ street $ postOfficeBox $ postalCode $
-               postalAddress $ physicalDeliveryOfficeName $ ou $ st $ l ) )
+               postalAddress $ physicalDeliveryOfficeName $ ou $ st $ l $ c ) )



cosine.schema :
# to get phones numbers with extensions, free writing

 attributetype ( 0.9.2342.19200300.100.1.20
-       DESC 'RFC1274: home telephone number'
-       NAME ( 'homePhone' 'homeTelephoneNumber' )
-       EQUALITY telephoneNumberMatch
-       SUBSTR telephoneNumberSubstringsMatch
-       SYNTAX 1.3.6.1.4.1.1466.115.121.1.50 )
+    NAME ( 'homePhone' 'homeTelephoneNumber' )
+    DESC 'RFC1274: home telephone number'
+   EQUALITY caseIgnoreMatch
+   SUBSTR caseIgnoreSubstringsMatch
+   SYNTAX 1.3.6.1.4.1.1466.115.121.1.15 )




=head1 copyright

Copyright (c) 2004-2006 8D Technologies, Xavier Renaut
xavier blob ldif2contagged at pecos blob 8d blod com
http://labs.8d.com

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU Lesser General Public
    License as published by the Free Software Foundation; either
    version 2.1 of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public
    License along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307  USA


=cut

my $uid="1186058859";

my $count=0;

my @data;

my $dir="split";
mkdir "split";

my $sn="";
my	$cn_surname="";
my	$mail_surname="";
my	$sn_found=0;

while (<>)
{
    
    next if /^objectclass: (top|person|mozillaAbPersonAlpha)/;
    next if /^modifytimestamp/;
    next if /^$/;
    if (/dn:\s*(.*)$/)
    {

	# flush old data
	if (@data){
	
	    unless ( $sn_found)
	    {
		if ($cn_surname ne ""){push @data,"sn: $cn_surname\n" }
		else {push @data,"sn: $mail_surname\n" }
		
	    }
	    print "\b\b\b\b$count";
	    my $file="$dir/$uid";
	    open (C,">$file") || die "cant open $file : $!";
	    print C @data  ;
	    
	    close C;
	}
	@data=();
	$sn="";
	$cn_surname="";
	$sn_found=0;
	$uid++;$count++;
	#my $dn=$1;


    }
    # s/^(\w+)::/$1:/; # used for the accents

    if (/sn: (\S+)/){ $sn=$1;$sn_found=1;}
    if (/cn:.*\b(\w+?)\b.*?\s*$/i) { $cn_surname=$1;}
    if (/mail: (\S+)@/i) { $mail_surname=$1;}


    #s/^(company:\s*\w{30})\w+\s*$/$1\n/;
    #s/^(\w+:\s*\w{30})[\w=]+\s*$/$1\n/; #there is no garbage, comment it
    s/dn:\s*(.*)$/dn: uid=$uid,ou=centraladdressbook,dc=8d,dc=com\nuid: $uid/;
    s/^company:/o:/;
    s/^department:/ou:/;
    s/^mozillaSecondEmail/mail/;
    s/^mozillaCustom1/description/;


    s/^mozillaWorkUrl/labeledURI/;
    if (s/^mozillaWorkStreet2::? /, /)
    {
	my $street2=$1;
	#foreach (@data) {if (s/street .*/
	my @data2;
	map {
	    if (/^street:/){chomp}
	    push @data2,$_;
	} @data;
	@data=@data2;

    }
    push @data,$_;
    #print
}
print " entries created in $dir/\n";

