#!/usr/local/bin/perl
####################################################################
# Script:       | Mailing List Manager                             #
# Version:      | 1.0                                              #
# By:           | i2 Services, Inc. / CGI World                    #
# Contact:      | jason@cgi-world.com                              #
# WWWeb:        | http://www.cgi-world.com                         #
# Copyright:    | CGI World (Jason Berry)                          #
# Released      | August 14th, 1998                                #
####################################################################
# By using this software, you have agreed to the license           #
# agreement packaged with this program.                            #
#                                                                  #
####################################################################
# Done: 
 # (Do not edit below this point, Violation of License Agreement)
 ###################################################################

 $SIG{__DIE__} = $SIG{__WARN__} = \&HTML_Error;	# show error msg on die/warn

						# Find current directory path
 if    ($0=~m#^(.*)\\#){ $cgidir = "$1"; }	# cgidir on win/dos  C:\dir\
 elsif ($0=~m#^(.*)/# ){ $cgidir = "$1"; }	# cgidir on unix     /usr/bin/
 else  {`pwd` =~ /(.*)/; $cgidir = "$1"; }	# else use unix `pwd` for cgidir

 $cgiurl   = $ENV{'SCRIPT_NAME'};		# web path of script
 $filelock = "$cgidir/filelock";		# filelock dir

 $listfile = "$cgidir/mlm_lists.dat.cgi";	# List data file
						# List data fields
 @lfields  = qw(num name desc users subscribe_from subscribe_subject subscribe_email);		

 $userfile  = "$cgidir/mlm_users.dat.cgi";	# User data file
 @ufields  = qw(email lists);			# User data fields

 $cfgfile  = "$cgidir/mlm_config.dat.cgi";	# Config File

 $adminpw  = "protection";
 $sendmail = "/usr/sbin/sendmail";
 $|++;						# Unbuffer output

 require "$cgidir/edis-lib.pl";			# Load EDIS Development Library

 $your_host = "$ENV{'REMOTE_HOST'}";
 $your_addr = "$ENV{'REMOTE_ADDR'}";


# ------------------------------------------------------------------------ 
# Main : Test conditions and give commands
# ------------------------------------------------------------------------ 

  %in = &ReadForm;				# Read CGI Form input
  %ck = &ReadCookie;				# Read Browser Cookies
  %cfg = &LoadHash($cfgfile,$filelock);

  $adminpw  = $cfg{'pw'};
  $sendmail = $cfg{'sendmail'};

#  $adminpw  = "magic";
#  $sendmail = "/usr/sbin/sendmail";

  ### When you use images as submit buttons they add .x .y onto
  ### the end of the image name so we'll substitue the proper var
  foreach $key (keys %in) { 
    if ($key =~ /^(.*)(\.x$|\.y$)/ && !$in{$1}) { $in{$1} = $in{$key}; }
    }

  if ($ARGV[0] eq "admin" || $ck{'admin'}) { $in{'admin'} = 1; }

  if	($in{'select'})		{ &Select; }		# Users Select Mailing Lists
  elsif	($in{'select_save'})	{ &Select_Save; }	# Users Select Mailing Lists
  elsif ($in{'admin'} && &Admin_Login) {		# Admin Menu

    ### ADMIN MENU - EDIT LISTS
    if	  ($in{'list_list'})	{ &List_List;	}	# List Mailing Lists
    elsif ($in{'list_add'})	{ &List_Add;	}	# Create mailing list
    elsif ($in{'list_edit'})	{ &List_Edit;	}	# Edit mailing list
    elsif ($in{'list_erase'})	{ &List_Erase;	}	# Erase mailing list
    elsif ($in{'list_save'})	{ &List_Save;	}	# Save mailing list
    elsif ($in{'list_count'})	{ &List_Count;	}	# Count users on each list

    ### ADMIN MENU - EDIT USERS
    elsif ($in{'user_list'})	{ &User_List;	}	# List users in lists
    elsif ($in{'user_add'})	{ &User_Add;	}	# add user to lists
    elsif ($in{'user_edit'})	{ &User_Edit;	}	# Edit user info
    elsif ($in{'user_erase'})	{ &User_Erase;	}	# Erase user from lists
    elsif ($in{'user_save'})	{ &User_Save;	}	# Save user info

    ### ADMIN MENU - PREFERENCES
    elsif ($in{'config'})	{ &Config;	}	# Change preferences

    ### ADMIN MENU - SEND MAILOUT
    elsif ($in{'mail_create'})	{ &Mail_Create;	}	# Create new email message
    elsif ($in{'mail_preview'})	{ &Mail_Preview;}	# Preview email message
    elsif ($in{'mail_publish'})	{ &Mail_Publish;}	# Send email to subscribers

    else			{ &Admin; }		# Main Admin Menu

    }

  ### DEFAULT ACTION/SCREEN TO DISPLAY
  else				{ &Select; }
	
  exit;

# ------------------------------------------------------------------------ 
# Select : Allow subscribers to select/deselect mailing lists
# ------------------------------------------------------------------------ 

sub Select {

  &Template("$cgidir/_mlm_select_login.html");		# load template cells
  &Template("$cgidir/_mlm_select.html");		# load template cells

  ### If they haven't entered their email yet display login screen
  if (!$in{'email'}) { 
    print "Content-type: text/html\n\n";
    print &Template("$cgidir/_mlm_select_login.html");	# email login
    exit;
    }

  ### If email isn't valid display login screen with error message
  if ($in{'email'} && !&Valid_Email($in{'email'})) { 
    $error = &Cell('error');
    print "Content-type: text/html\n\n";
    print &Template("$cgidir/_mlm_select_login.html");	# email login
    exit;
    }

  ### If email is valid display lists available to subscribe/unsubscribe to

  ### Load user list data file
  open(FILE,"<$userfile");			
  @lines = grep(/^\S/,<FILE>);
  close(FILE);

  foreach (@lines) { 
    ($email,$lists2) = split(/\|/,$_);
    if ($in{'email'} eq $email)	{ $lists = $lists2; last; }	# found
    }

  ### Load mailing list data file
  &FileLock($filelock);				# File Lock
  open(FILE,"<$listfile");
  @lines = grep(/^\d/,<FILE>);
  close(FILE);
  &FileUnlock($filelock);			# File Unlock

  ### Generate list of mailing lists for selecting/deselecting
  foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode
      ${$lfields[$i]} =~ s/"/&quot;/gs;			# 
      ${$lfields[$i]} =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n
      }
    if (substr($lists,($num-1),1)) { $checked = "checked" } else { $checked = ""; }
    $list .= &Cell('row');
    }

  $email = $in{'email'};				# define user email
  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_select.html");		# change options
  exit;

}

# ------------------------------------------------------------------------ 
# Select_Save : Save user selections to the data file
# ------------------------------------------------------------------------ 

sub Select_Save {

  ### Check for valid email format
  $in{'email'} = lc $in{'email'};
  unless (&Valid_Email($in{'email'})) { die("Select_Save : Invalid Email Format!\n"); }

  ### Generate subscribed lists string
  foreach (keys %in) {
    if (/^list(\d+)/) {
      while ((int $1) > length $in{'lists'}) { $in{'lists'} .= "0"; }
      if (!substr($in{'lists'},($1-1),1)) {
        substr($in{'lists'},($1-1),1) = "1";
        &Select_ConfirmMail($in{'email'},$1);	# Send Confirmation Email
        }
      }
    }

  &FileLock($filelock);				# File Lock

  ### Load user list data file
  open(FILE,"<$userfile");			
  @lines = grep(/^\S/,<FILE>);
  close(FILE);

  ### Update list data file
  open(FILE,">$userfile") || die("User Save : Can't write $userfile - $!\n");
  foreach (@lines) { 
    ($email,$lists) = (split(/\|/,$_));
    if ($in{'email'} eq $email)	{			# match
      if (int $in{'lists'}) { print FILE "$in{'email'}|$in{'lists'}|\n"; }
      $found++;
      }
    else { print FILE $_; }				# no match
    }

  unless ($found) { 					# unless email found add new
    print FILE "$in{'email'}|$in{'lists'}|\n";
    }

  close(FILE);
  &FileUnlock($filelock);			# File Unlock
  
  &List_Count;					# Update list user counts

  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_select_saved.html");	# change options
  exit;

}

# ------------------------------------------------------------------------ 
# Select_ConfirmMail : Send Confirm Email to new list subscribers
# ------------------------------------------------------------------------ 

sub Select_ConfirmMail {

  my ($to)	= $_[0];		# email TO: address
  my ($lnum)	= $_[1];		# List confirm message

  ### Load list data file
  &FileLock($filelock);					# File Lock
  open(FILE,"<$listfile");			
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);
  &FileUnlock($filelock);				# File Unlock

  ### Get list name from "to:" list number
  foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode
      ${$lfields[$i]} =~ s/"/&quot;/gs;			# 
      ${$lfields[$i]} =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n
      }
    if ($num == $lnum) { last; }
    }

  ### SEND MAILOUT

  if (-e $sendmail) { open(MAIL,"|$sendmail -t") || die("SendMail: Can't open $sendmail : $!\n"); }
  else 		{ open(MAIL,">>$cgidir/sendmail.txt") || die("can't open sendmail.txt\n"); }
  print MAIL "Subject: $subscribe_subject\n";
  print MAIL "From: $subscribe_from\n";
  print MAIL "To: $to\n\n";
  print MAIL "$subscribe_email\n";
  close(MAIL);

}

# ------------------------------------------------------------------------ 
# Admin_Login : Check for valid admin password
# ------------------------------------------------------------------------ 

sub Admin_Login {

  if (!$cfg{'pw'}) { return 1; }

  &Template("$cgidir/_mlm_admin_login.html");		# Load Templates

  if ($in{'pw'}) {
    if ($in{'pw'} eq $adminpw) { &SetCookie('pw',$in{'pw'}); }
    else {
      $error = &Cell('error');
      print "Content-type: text/html\n\n";
      print &Template("$cgidir/_mlm_admin_login.html");
      exit;
      }
    }
  elsif ($ck{'pw'} ne $adminpw) {
    print "Content-type: text/html\n\n";
    print &Template("$cgidir/_mlm_admin_login.html");
    exit;
    }

  return 1;

}

# ------------------------------------------------------------------------ 
# Admin : Display main admin menu
# ------------------------------------------------------------------------ 

sub Admin {

  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin.html");

}


# ------------------------------------------------------------------------ 
# List List : List all mailing lists
# ------------------------------------------------------------------------ 

sub List_List {

  print "Content-type: text/html\n\n";
  &Template("$cgidir/_mlm_admin_lists_list.html");		# Load Templates

  &FileLock($filelock);				# File Lock

  open(FILE,"<$listfile");
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);

  &FileUnlock($filelock);			# File Unlock

  foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode
      ${$lfields[$i]} =~ s/"/&quot;/gs;			# 
      ${$lfields[$i]} =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n

      }
    $num   = int $num;
    $users = int $users;
    $list .= &Cell('row');
    }

  print &Template("$cgidir/_mlm_admin_lists_list.html");

}

# ------------------------------------------------------------------------ 
# List Add : Add a new mailing list
# ------------------------------------------------------------------------ 

sub List_Add {

  &Template("$cgidir/_mlm_admin_lists_add.html");		# Load Templates

  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_lists_add.html");

}

# ------------------------------------------------------------------------ 
# List Edit : Edit a mailing list
# ------------------------------------------------------------------------ 

sub List_Edit {

  &FileLock($filelock);				# File Lock

  ### Load list data file
  open(FILE,"<$listfile");
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);

  &FileUnlock($filelock);			# File Unlock

  foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode	
      ${$lfields[$i]} =~ s/"/&quot;/gs;			# 
      }
    if ($num == $in{'list_edit'}) { $found++; last; }
    }
  
  unless ($found) { die("List_Edit : Couldn't find record $in{'list_edit'} to edit!\n"); }

  $num   = int $num;
  $users = int $users;

  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_lists_edit.html");

}

# ------------------------------------------------------------------------ 
# List Erase : Erase a mailing list
# ------------------------------------------------------------------------ 

sub List_Erase {


  &FileLock($filelock);				# File Lock

  ### Load list data file
  open(FILE,"<$listfile");
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);

  &FileUnlock($filelock);			# File Unlock


  ### IF ERASE CONFIRMED
  if ($in{'confirm'}) {				
    &FileLock($filelock);			# File Lock

    ### Update data file
    open(FILE,">$listfile");			# Save Data to file
    foreach (@lines) { 
      ($num) = (split(/\|/,$_))[0];
      if ($in{'list_erase'} == $num) { $found++ }
      else                    { print FILE $_; }
      }
    close(FILE);
 
    ### Erase List subscription for all users on list
  
    ### Load user list data file
    open(FILE,"<$userfile");			
    @lines = grep(/^\S/,<FILE>);
    close(FILE);

    ### Update user data file
    open(FILE,">$userfile") || die("User Save : Can't write $userfile - $!\n");
    foreach (@lines) { 
      ($email,$lists) = (split(/\|/,$_));
      if (substr($lists, ($in{'list_erase'}-1), 1)) {
        substr($lists, ($in{'list_erase'}-1), 1) = "0";
        }
      print FILE "$email|$lists|\n";
      }

    close(FILE);

    &FileUnlock($filelock);			# File Unlock
    unless ($found) { die("List_Erase : Couldn't find list $in{'num'} to erase!\n"); }
    &List_List;
    }

  ### DISPLAY ERASE CONFIRM SCREEN
  else {					
    foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode	
      ${$lfields[$i]} =~ s/"/&quot;/gs;			# 
      ${$lfields[$i]} =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n
      }
    if ($num == $in{'list_erase'}) { $found++; last; }
    }
  
    unless ($found) { die("List_Erase : Couldn't find list $in{'num'} to erase!\n"); }
    $num   = int $num;
    $users = int $users;
    print "Content-type: text/html\n\n";
    print &Template("$cgidir/_mlm_admin_lists_erase.html");
    exit;
    }

}

# ------------------------------------------------------------------------ 
# List Save : Save or update mailing list info
# ------------------------------------------------------------------------ 

sub List_Save {

  &FileLock($filelock);				# File Lock

  ### Load list data file
  open(FILE,"<$listfile");
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);

  ### Update list data file
  open(FILE,">$listfile") || die("List Save : Can't write $listfile - $!\n");

  if ($in{'num'}) {				# If num - update record
    foreach (@lines) { 
      ($num) = (split(/\|/,$_))[0];
      if ($in{'num'} != $num) { print FILE $_; }
      else {
        foreach (@lfields) {
          $in{$_} =~ s/(\r\n|\n)/\n/gs;		# Replace \r\n with \n
          $in{$_} = &URL_Encode($in{$_});	
          print FILE "$in{$_}|";
          }
        print FILE "\n";
        }
      }
    }

  else {					# No Num - create record
    $newnum = 1;				# Find an unused record num
    foreach (@lines) { 				
      ($num) = (split(/\|/,$_))[0];		
      if ($newnum == $num) { $newnum++; }	
      if (int $num) { print FILE $_; }		
      }						
    $in{'num'} = $newnum;			
    foreach (@lfields) {				
      $in{$_} =~ s/(\r\n|\n)/\n/gs;		# Replace \r\n with \n
      $in{$_} = &URL_Encode($in{$_});
      print FILE "$in{$_}|";
      }
    print FILE "\n";
    }

  close(FILE);
  &FileUnlock($filelock);			# File Unlock
  
  &List_List;					# Display new list

}

# ------------------------------------------------------------------------ 
# List Count : Count number of users per list
# ------------------------------------------------------------------------ 

sub List_Count {

  ### Load user list and get counts

  &FileLock($filelock);				# File Lock
  open(FILE,"<$userfile");			
  @lines = grep(/^\S/,<FILE>);
  close(FILE);
  &FileUnlock($filelock);			# File Unlock

  foreach (@lines) {
    ($email,$lists) = split(/\|/,$_);
    for (1..(length $lists)) {
      if (substr($lists, ($_ - 1), 1)) {
        $listcount{$_}++;
        }
      }
    }

  ### Save list count numbers

  &FileLock($filelock);				# File Lock
  ## Load list data file
  open(FILE,"<$listfile");
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);

  ## Update list data file
  open(FILE,">$listfile") || die("List Count : Can't write $listfile - $!\n");
  foreach (@lines) { 
    ($num,$name,$desc,$users,$subscribe_from,$subscribe_subject,$subscribe_email) = split(/\|/,$_);
    $users = $listcount{$num}||"0";			# Update User Count
    print FILE "$num|$name|$desc|$users|$subscribe_from|$subscribe_subject|$subscribe_email|\n";
    }
  close(FILE);
  &FileUnlock($filelock);			# File Unlock
  
}

# ------------------------------------------------------------------------ 
# User List : List all mailing list users
# ------------------------------------------------------------------------ 

sub User_List {

  &Template("$cgidir/_mlm_admin_users_list.html");		# Load Templates

  ### Load user list data file
  &FileLock($filelock);					# File Lock
  open(FILE,"<$userfile");			
  @lines = grep(/^\S/,<FILE>);
  $tcount = @lines;
  close(FILE);
  &FileUnlock($filelock);				# File Unlock

  if ($in{'search'}) { 					#
    $search = $in{'search'};

    ### Search through lists of users
    foreach (@lines) { 
      @fdata = split(/\|/,$_);
      for $i (0..$#ufields) {				# for each field name
        ${$ufields[$i]} = $fdata[$i];			# assign field data
        }
      if ($email =~ /$search/) { $list .= &Cell('row'); $count++; }
      }

    }

  $count = int $count;
  $tcount = int $tcount;
  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_users_list.html");

  exit;

}

# ------------------------------------------------------------------------ 
# User Add : Add a new user
# ------------------------------------------------------------------------ 

sub User_Add {

  $search = $in{'search'};
  &Template("$cgidir/_mlm_admin_users_add.html");		# Load Templates

  ### Load mailing list data file
  &FileLock($filelock);				# File Lock
  open(FILE,"<$listfile");
  @lines = grep(/^\d/,<FILE>);
  close(FILE);
  &FileUnlock($filelock);			# File Unlock

  ### Generate list of mailing lists for Message To: options
  foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode
      ${$lfields[$i]} =~ s/"/&quot;/gs;			# 
      ${$lfields[$i]} =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n
      }
    $list .= &Cell('row');
    }

  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_users_add.html");

}

# ------------------------------------------------------------------------ 
# User Edit : Edit a user
# ------------------------------------------------------------------------ 

sub User_Edit {

  $search = $in{'search'};
  &Template("$cgidir/_mlm_admin_users_edit.html");		# Load Templates

  ### Load user list data file
  open(FILE,"<$userfile");			
  @lines = grep(/^\S/,<FILE>);
  close(FILE);

  foreach (@lines) { 
    ($email,$lists) = split(/\|/,$_);
    if ($in{'user_edit'} eq $email)	{ $found++; last; }	# found
    }

  unless ($found) { die("User_Edit : Couldn't find user $in{'user_edit'}\n"); }

  ### Load mailing list data file
  &FileLock($filelock);				# File Lock
  open(FILE,"<$listfile");
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);
  &FileUnlock($filelock);			# File Unlock

  ### Generate list of mailing lists for Message To: options
  foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode
      }
    if (substr($lists,($num-1),1)) { $checked = "checked" } else { $checked = ""; }
    $list .= &Cell('row');
    }

  $email = $in{'user_edit'};
  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_users_edit.html");

}

# ------------------------------------------------------------------------ 
# Users Erase : Erase a user
# ------------------------------------------------------------------------ 

sub User_Erase {

  $search = $in{'search'};
  &Template("$cgidir/_mlm_admin_users_erase.html");		# Load Templates

  ### IF ERASE CONFIRMED
  if ($in{'confirm'}) {				

    ### Load user list data file
    &FileLock($filelock);				# File Lock
    open(FILE,"<$userfile");			
    @lines = grep(/^\S/,<FILE>);
    close(FILE);
    &FileUnlock($filelock);			# File Unlock

    &FileLock($filelock);			# File Lock
    ### Update user data file
    open(FILE,">$userfile") || die("User Save : Can't write $userfile - $!\n");

    foreach (@lines) { 
      ($email) = (split(/\|/,$_))[0];
      if ($in{'user_erase'} eq $email)	{ $found++; }	# found (erase entry)
      else { print FILE $_; }				# continue
      }

    close(FILE);
    &FileUnlock($filelock);			# File Unlock

    unless ($found) { die("User_Erase : Couldn't find user $in{'user_erase'} to erase!\n"); }


    &List_Count;				# Update list user counts
    &User_List;					# List Users
    }

  ### ELSE DISPLAY ERASE CONFIRM SCREEN
	
  $email = $in{'user_erase'};
  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_users_erase.html");
  exit;

}

# ------------------------------------------------------------------------ 
# Users Save : Save a user
# ------------------------------------------------------------------------ 

sub User_Save {

  ### Check for valid email format
  $in{'email'} = lc $in{'email'};
  unless (&Valid_Email($in{'email'})) { die("User_Save : Invalid Email Format!\n"); }

  ### Generate subscribed lists string
  foreach (keys %in) {
    if (/^list(\d+)/) {
      while ((int $1) > length $in{'lists'}) { $in{'lists'} .= "0"; }
      substr($in{'lists'},($1-1),1) = "1";
      }
    }

  &FileLock($filelock);				# File Lock

  ### Load user list data file
  open(FILE,"<$userfile");			
  @lines = grep(/^\S/,<FILE>);
  close(FILE);

  ### Update list data file
  open(FILE,">$userfile") || die("User Save : Can't write $userfile - $!\n");
  foreach (@lines) { 
    ($email) = (split(/\|/,$_))[0];
    if ($in{'email'} eq $email)	{			# match
      print FILE "$in{'email'}|$in{'lists'}|\n";
      $found++;
      }
    else { print FILE $_; }				# no match
    }

  unless ($found) { 					# unless email found add new
    print FILE "$in{'email'}|$in{'lists'}|\n";
    }

  close(FILE);
  &FileUnlock($filelock);			# File Unlock
  
  &List_Count;					# Update list user counts
  &User_List;					# Display new user list
}


# ------------------------------------------------------------------------ 
# Config : Change configuration options
# ------------------------------------------------------------------------ 

sub Config {

  if ($in{'save'}) { 
    $cfg{'sendmail'} = $in{'sendmail'};
    $cfg{'pw'}       = $in{'adminpw'};
    &SaveHash('cfg',$cfgfile,$filelock);		# Save Config file
    }

  $adminpw  = $cfg{'pw'};
  $sendmail = $cfg{'sendmail'};

  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_pref.html");

}

# ------------------------------------------------------------------------ 
# Mail_Create : Create a mailout
# ------------------------------------------------------------------------ 

sub Mail_Create {

  $subject = &URL_Decode($in{'subject'});		# read hidden input field
  $from    = &URL_Decode($in{'from'});			# read hidden input field
  $to      = &URL_Decode($in{'to'});			# read hidden input field
  $message = &URL_Decode($in{'message'});		# read hidden input field

  foreach ($subject,$from,$to,$message) { s/"/&quot;/gs; }

  &Template("$cgidir/_mlm_admin_mailout_create.html");	# Load Template

  ### Load list data file
  &FileLock($filelock);					# File Lock
  open(FILE,"<$listfile");			
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);
  &FileUnlock($filelock);				# File Unlock

  ### Generate list of mailing lists for Message To: options
  foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode
      ${$lfields[$i]} =~ s/"/&quot;/gs;			# 
      ${$lfields[$i]} =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n
      }
    $num   = int $num;
    $users = int $users;
    if ($num == $to) { $selected = "selected" } else { $selected = ""; }
    $list .= &Cell('row');
    }

  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_mailout_create.html");

}

# ------------------------------------------------------------------------ 
# Mail_Preview : Preview mailout
# ------------------------------------------------------------------------ 

sub Mail_Preview {

  ($subject,$from,$to,$message) = ($in{'subject'},$in{'from'},$in{'to'},$in{'message'});

  $message =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n
  $hsubject = &URL_Encode($subject);		# hidden input field
  $hfrom    = &URL_Encode($from);		# hidden input field
  $hto      = &URL_Encode($to);			# hidden input field
  $hmessage = &URL_Encode($message);		# hidden input field
  $message =~ s/\n/<br>\n/gs;			# add <br>'s for preview lines
 
  ### Load list data file
  &FileLock($filelock);					# File Lock
  open(FILE,"<$listfile");			
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);
  &FileUnlock($filelock);				# File Unlock

  ### Get list name from "to:" list number
  foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode
      ${$lfields[$i]} =~ s/"/&quot;/gs;			# 
      ${$lfields[$i]} =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n
      }
    $users = int $users;
    if ($num == $to) { $to = "$name ($users users)"; last; }
    if ($to  eq "A") { $to = "All Users"; last; }
    }

  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_mailout_preview.html");

}

# ------------------------------------------------------------------------ 
# Mail_Publish : Publish mailout
# ------------------------------------------------------------------------ 

sub Mail_Publish {

  ### Load list data file
  &FileLock($filelock);					# File Lock
  open(FILE,"<$listfile");			
  @lines = sort { (split(/\|/,$a))[0] <=> (split(/\|/,$b))[0] } grep(/^\d/,<FILE>);
  close(FILE);
  &FileUnlock($filelock);				# File Unlock

  ### Get list name from "to:" list number
  foreach (@lines) { 
    @fdata = split(/\|/,$_);
    for $i (0..$#lfields) {				# for each field name
      ${$lfields[$i]} = $fdata[$i];			# assign field data
      ${$lfields[$i]} = &URL_Decode(${$lfields[$i]});	# URL Decode
      ${$lfields[$i]} =~ s/"/&quot;/gs;			# 
      ${$lfields[$i]} =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n
      }
    $users = int $users;
    if ($num == $in{'to'}) { last; }
    if ($in{'to'} eq "A")  { $users = "all"; $name = "All Lists"; last; }
    }


  foreach ($subject,$from,$to,$message) { s/"/&quot;/gs; }

  print "Content-type: text/html\n\n";
  print &Template("$cgidir/_mlm_admin_mailout_publish.html");

  ### SEND MAILOUT TO EACH USER

  $subject = &URL_Decode($in{'subject'});	# 
  $from    = &URL_Decode($in{'from'});		# 
  $to      = &URL_Decode($in{'to'});		# 
  $message = &URL_Decode($in{'message'});	#
  $message =~ s/(\r\n|\n)/\n/gs;		# replace \r\n with \n

  ### Load user list data file
  &FileLock($filelock);		
  open(FILE,"<$userfile");			
  @lines = grep(/^\S/,<FILE>);
  close(FILE);
  &FileUnlock($filelock);

  foreach (@lines) {
    ($email,$lists) = split(/\|/,$_);
    if (($in{'to'} eq "A" && int $lists) || substr($lists, ($in{'to'}-1), 1)) {
      print &Cell("sending");
      if (-e $sendmail) { open(MAIL,"|$sendmail -t") || die("SendMail: Can't open $sendmail : $!\n"); }
      else 		{ open(MAIL,">>$cgidir/sendmail.txt") || die("can't open sendmail.txt\n"); }
      print MAIL "Subject: $subject\n";
      print MAIL "From: $from\n";
      print MAIL "To: $email\n\n";
      print MAIL "$message\n";
      close(MAIL);
      }
    }

  print "<br><font face=\"arial\">Mailout to $users Users has been Completed....<br>";


}

# ------------------------------------------------------------------------ 
# Valid Email : Check for valid email field
#
# usage       : if (&Valid_Email('dave@edis.org')) { ... }
#	      : returns 0=invalid 1=valid
# ------------------------------------------------------------------------ 

sub Valid_Email {

  my($email) = $_[0];
  my($user,$host) = split(/@/,$email);              # split into user @ host

  if ($email eq "")                                      { return 0; } # No email address  
  if ($email =~ /[^A-Za-z0-9-_\.\@]/)                    { return 0; } # Invalid characters
  if ($user !~ /^([\w-]+[\w-.])*[\w-]+$/)                { return 0; } # Invalid format
  if ($host !~ /^([\w-]+[\w-.])*[\w-]+\.[A-Za-z]{2,4}$/) { return 0; } # Invalid format

  return 1;

}

# ------------------------------------------------------------------------ 
# Error : Display an Error message and exit
# ------------------------------------------------------------------------ 

sub HTML_Error {

  if (-e $filelock) { &FileUnlock($filelock); }		# File Unlock
  print "@_";
  exit;

}

# ------------------------------------------------------------------------ 
#                            Programming by Dave Edis <dave@cgi-world.com>

