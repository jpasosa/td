#!/usr/local/bin/perl
# ---------------------------------------------------------------------------- 
# edis-lib.pl - EDIS CGI Development Library v1.2
# ---------------------------------------------------------------------------- 
# The is a collection of perl routines for rapid CGI development.
#
# Copyright (c) 1998 Dave Edis
# Unpublished work.
# Permission granted to use and modify this library so long as the
# copyright above is maintained, modifications are documented, and
# credit is given for any use of the library.
# ---------------------------------------------------------------------------- 
# v1.0 Created: 01.May.98 - Programming by Dave Edis <dave@cgi-world.com>
# v1.1 Revised: 13.May.98 - Added HTTP Cookie Routines.
# v1.2 Revised: 24.May.98 - Updated LoadHash & SaveHash w/ perl header & file locking
# v1.3 Revised: 28.May.98 - Updated Template routines


# ------------------------------------------------------------------------ 
# ReadForm : Read input from CGI form Perl Routine.  Parse input from a 
#            GET or POST form and return a hash of form names and values.
#
# Usage    : %in = &ReadForm; 
# ------------------------------------------------------------------------ 

sub ReadForm {

  my($max) = $_[1];					# Max Input Size
  my($name,$value,$pair,@pairs,$buffer,%hash);		# localize variables

  # Check input size if max input size is defined
  if ($max && ($ENV{'CONTENT_LENGTH'}||length $ENV{'QUERY_STRING'}) > $max) {
    die("ReadForm : Input exceeds max input limit of $max bytes\n");
    }

  # Read GET or POST form into $buffer
  if    ($ENV{'REQUEST_METHOD'} eq 'POST') { read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'}); }
  elsif ($ENV{'REQUEST_METHOD'} eq 'GET')  { $buffer = $ENV{'QUERY_STRING'}; }

  @pairs = split(/&/, $buffer);				# Split into name/value pairs
  foreach $pair (@pairs) {				# foreach pair
    ($name, $value) = split(/=/, $pair);		# split into $name and $value
    $value =~ tr/+/ /;					# replace "+" with " "
    $value =~ s/%([A-F0-9]{2})/pack("C", hex($1))/egi;	# replace %hex with char
    $hash{$name} = $value;
    }

  return %hash;
  }

# ------------------------------------------------------------------------ 
# Valid Email : Check for valid email field
#
# usage       : if (&Valid_Email('dave@cgi-world.com')) { ... }
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
# Template : Open a template file, translate variables and return contents
#
# usage    : print &Template("$cgidir/filename.html",'html');
# ------------------------------------------------------------------------ 

sub Template {  

  local(*FILE);
  my($hash) = $_[1];

  if    (!$_[0])	{ return "<br>\nTemplate : No file was specified<br>\n"; }
  elsif (!-e "$_[0]")	{ return "<br>\nTemplate : File '$_[0]' does not exist<br>\n"; }
  else {
    open(FILE, "<$_[0]") || return "<br>\nTemplate : Could open $_[0]<br>\n";
    while (<FILE>) { $FILE .= $_; }
    close(FILE);
    for ($FILE) {
      s/<!-- insert : (.*?) -->/\1/gi;				# show hidden inserts
      s/<!-- def : (\w+) -->(?:\r\n|\n)?(.*?)<!-- \/def : \1 -->/
	$CELL{$1}=$2;''/ges;					# read/remove template cells

      if ($hash) { s/\$(\w+)\$/$hash->$1/g; }			# translate $scalars$
      else	 { s/\$(\w+)\$/${$1}/g; }
      }
    }
  return $FILE;
}

# ------------------------------------------------------------------------ 
# Cell  : Return a template cell with translated variables.
#         Note: Before you can read a cell you need to load the template.
#
# usage : print &Cell("cellname");
# ------------------------------------------------------------------------ 

sub Cell {  

  my($CELL);
  my($hash) = $_[1];
  for (0..$#_) { if ($_[$_]) { $CELL .= $CELL{$_[$_]}; }}

  if    (!$_[0]) { return "<br>\nCell : No cell was specified<br>\n"; }
  elsif (!$CELL) { return "<br>\nCell : Cell '$_[0]' is not defined<br>\n"; }
  else		 {
    if ($hash) { $CELL =~ s/\$(\w+)\$/$hash->{$1}/g; }		# translate $scalars$
    else       { $CELL =~ s/\$(\w+)\$/${$1}/g; }
    }		# translate $scalars$
  
  return $CELL;

}


# ------------------------------------------------------------------------ 
# Append : Append some data to the end of a file
#
# usage  : &Append($file,$data);
# ------------------------------------------------------------------------ 

sub Append {

  local (*FILE);        # Localize filehandle
  my($file,$data) = @_;

  open(FILE,">>$file") || die ("Append : Can't append to $file : $!\n");
  print FILE $data;
  close(FILE);
}

# ----------------------------------------------------------------------------
# FileLock : File locking/unlocking Perl routines.
#
# Usage    : &FileLock("$lockdir");
#	   : &FileUnlock("$lockdir");
# ----------------------------------------------------------------------------

sub FileLock   {
  my($i);					# sleep counter
  while (!mkdir($_[0],0777)) {			# if there already is a lock
    sleep 1;					# sleep for 1 sec and try again
    if (++$i>60) { die("File_Lock : Can't create filelock : $!\n"); }		
    }
  }

sub FileUnlock {
  rmdir($_[0]);					# remove file lock dir
  }

# ------------------------------------------------------------------------ 
# Hash  : Perl routines for saving and loading a hash from a datafile
#
# usage : &SaveHash('hash',$filename);
#         %Hash = &LoadHash($filename);
#
#         &SaveHash('hash',$filename,$filelock);	# with file locking
#         %Hash = &LoadHash($filename,$filelock);	# with file locking
# ------------------------------------------------------------------------ 

sub SaveHash {

  local(*FILE);		# localize file handle
  my($hash) = $_[0];	# hash name
  my($file) = $_[1];	# Data file
  my($lockdir) = $_[2];	# File Lock Dir
  my($value);		# temp hash value var

  if ($lockdir) { &FileLock($lockdir); }

  open(FILE,">$file") || die ("SaveHash : Can't open $file : $!\n");

  print FILE qq|#!/usr/local/bin/perl\n|;
  print FILE qq|print "Content-type: text/plain\\n\\n";\n|;
  print FILE qq|print "This is a data file created with edis-lib.pl";\n|;
  print FILE qq|__END__\n|;

  foreach $key (sort keys %{$hash}) {
    $value = &URL_Encode($hash->{$key});
    print FILE "$key $value\n";
    }
  close(FILE);

  if ($lockdir) { &FileUnlock($lockdir); }

}


sub LoadHash {

  my($file) = $_[0];	# Data file
  my(@lines,$name,$value,%hash);

  if ($lockdir) { &FileLock($lockdir); }

  open(FILE,"<$file");				# Load in Data file
  while (<FILE>) { if (/__END__/) { last }}	# Skip Perl header
  @lines = <FILE>;
  close(FILE);

  if ($lockdir) { &FileUnlock($lockdir); }

  foreach $line (@lines) {
    ($name,$value) = split(/ /,$line);
    chomp $value; 				# remove trailing nextline
    $hash{$name} = &URL_Decode($value);
    }

  return %hash;
}

# ------------------------------------------------------------------------ 
# Log    : Make a dated entry in a log file
#
# usage  : &Append($file,$data);
# ------------------------------------------------------------------------ 

sub Log {

  local (*FILE);        # Localize filehandle
  my($file,$data) = @_;
  my $datetime       = localtime(time);

  open(FILE,">>$file") || die ("Log : Can't append to $file : $!\n");
  print FILE "[$datetime] $data\n";
  close(FILE);
}


# ------------------------------------------------------------------------ 
# Tail  : Read last few lines from a text file
#
# usage : $lines = $Tail($file,20);
# ------------------------------------------------------------------------ 

sub Tail {

  local (*FILE);             # Localize filehandle
  my($file)  = $_[0];        # File to read 
  my($lines) = $_[1] || 10;  # Lines to read in
  my($buffer,@lines);

  $buffer = $lines*80;       # How much to read in

  open(FILE,"<$file") || die ("Tail : Can't open $file : $!\n");
 
  ### Read lines from file

  while (@lines < $lines) {       # while lines read < lines requested
    if ($buffer >= -s FILE) {     # if buffer >= file size
      seek(FILE,0,0);             # go to start of file
      @lines = <FILE>;            # read all lines into @lines
      last;                       # and exit this while loop
      }  
    else {                        # else if buffer isn't >= file size
      seek(FILE,-$buffer,2);      # read in buffer size from end of file
      ($_,@lines) = <FILE>;       # break that up into full lines
      $buffer += 80;              # up buffer in case we need another loop
      }
    }
  close(FILE);

  ### Return right number of lines
  
  # unless there is less lines than requested shorten array
  unless (@lines < $lines) { @lines = @lines[($#lines-$lines+1)..$#lines]; }

  return @lines;
}


# ----------------------------------------------------------------------------
# MIME64 : MIME64 encoding/decoding Perl routines.  MIME64 is a common base64
#          encoding scheme documented in RFC1341, section 5.2.
#
# Usage  : $mime64_text = &MIME64_Encode("$plaintext");
#	 : $plaintext   = &MIME64_Decode("$mime64_text");
# ----------------------------------------------------------------------------

sub MIME64_Encode {    
  my($in)  = $_[0];					# text to encode
  my(@b64) = ((A..Z,a..z,0..9),'+','/');		# Base 64 char set to use
  my($out) = unpack("B*",$in);				# Convert to binary
  $out=~ s/(\d{6}|\d+$)/$b64[ord(pack"B*","00$1")]/ge;	# convert 3 bytes to 4
  while (length($out)%4) { $out .= "="; }		# Pad string with '='
  return $out;						# Return encoded text
  }

sub MIME64_Decode {
  my($in)  = $_[0];					# encoded text to decode
  my(%b64);						# Base 64 char set hash
  my($out);						# decoded text variable
  for((A..Z,a..z,0..9),'+','/'){ $b64{$_} = $i++ }	# Base 64 char set to use
  $in = $_[0] || return "MIME64 : Nothing to decode";	# Get input or return
  $in =~ s/[^A-Za-z0-9+\/]//g;				# Remove invalid chars
  $in =~ s/[A-Za-z0-9+\/]/unpack"B*",chr($b64{$&})/ge;	# b64 offset val -> bin
  $in =~ s/\d\d(\d{6})/$1/g;				# Convert 8 bits to 6
  $in =~ s/\d{8}/$out.=pack("B*",$&)/ge;		# Convert bin to text
  return $out;						# Return decoded text
  }


# ----------------------------------------------------------------------------
# URL    : URL encoding/decoding Perl routines.  URL encoding is an common 
#          encoding scheme where non A-Za-z0-9+*.@_- characters are replaced
#          with a character triplet of "%" followed by the two hex digits.
#
# Usage  : $URL_encoded = &URL_Encode("$plaintext");
#	 : $plaintext   = &URL_Decode("$URL_encoded");
# ----------------------------------------------------------------------------

sub URL_Encode {
  my($text)  = $_[0];					# text to URL encode
  $text =~ tr/ /+/;					# replace " " with "+"
  $text =~ s/[^A-Za-z0-9\+\*\.\@\_\-]/			# replace odd chars
             uc sprintf("%%%02x",ord($&))/egx;		#   with %hex value
  return $text;						# return URL encoded text
  }

sub URL_Decode {
  my($text)  = $_[0];					# URL encoded text to decode
  $text =~ tr/+/ /;					# replace "+" with " "
  $text =~ s/%([A-F0-9]{2})/pack("C", hex($1))/egi;	# replace %hex with chars
  return $text;						# return decoded plain text
  }


# ----------------------------------------------------------------------------
# Cookie : Perl routines for setting/reading browser cookies.
#        : Cookies have a max size of 4k and each host can send up to 20.
#
# Usage  : &SetCookie("name","value");
#        : %cookie = &ReadCookie;
# ----------------------------------------------------------------------------

sub SetCookie {

  my($cookie_info);
  my($name,$value,$exp,$path,$domain,$secure) = @_;

  # $name 	- cookie name (ie: username)
  # $value	- cookie value (ie: "joe user")
  # $exp	- exp date, cookie will be deleted at this date. Format: Wdy, DD-Mon-YYYY HH:MM:SS GMT
  # $path	- Cookie is sent only when this path is accessed   (ie: /);
  # $domain	- Cookie is sent only when this domain is accessed (ie: .edis.org)
  # $secure	- Cookie is sent only with secure https connection

  unless (defined $name) { die("SetCookie : Cookie name must be specified\n"); }
  if ($exp && $exp !~ /^[A-Z]{3}, \d\d-[A-Z]{3}-\d{4} \d\d:\d\d:\d\d GMT$/i) { die("SetCookie : Exp Dat format isn't: Wdy, DD-Mon-YYYY HH:MM:SS GMT\n"); }

  if ($name)		{ $name  = &URL_Encode($name); }
  if ($value)		{ $value = &URL_Encode($value); }
  if ($exp)		{ $cookie_info .= "expires=$exp; "; }
  if ($path)		{ $cookie_info .= "path=$path; "; }
  if ($domain)		{ $cookie_info .= "domain=$domain; "; }
  if ($secure)		{ $cookie_info .= "secure; "; }

  print "Set-Cookie: $name=$value; $cookie_info\n";

  }

sub ReadCookie {

  my($cookie,$name,$value,%jar);

  foreach $cookie (split(/; /,$ENV{'HTTP_COOKIE'})) {		# for each cookie sent
    ($name,$value) = split(/=/,$cookie);			# split into name/value
    foreach($name,$value) { $_ = &URL_Decode($_); }		# URL decode strings
    $jar{$name}=$value;						# and put into %jar hash
    }

   return %jar;							# return %jar hash

}


# ----------------------------------------------------------------------------
# ENV      : print out Enviroment variables
#
# Usage    : &ENV;		# print ENV vars
# ----------------------------------------------------------------------------

sub ENV {

  &PrintHash('ENV');
  }


# ----------------------------------------------------------------------------
# PrintHash : print out hash key/value pairs
#
# Usage     : &PrintHash('ENV');
# ----------------------------------------------------------------------------

sub PrintHash {

  my($HASH) = $_[0];
  foreach $key (sort keys %{$HASH}) { print "$key = $HASH->{$key}<br>\n"; }
  }

# ----------------------------------------------------------------------------
# ExecTime : Return time the program has been running.
#
# Usage    : $secs         = &ExecTime;
# ----------------------------------------------------------------------------

sub ExecTime {
  
  my($exectime) = time - $^T;				# exectime in seconds
  my($mins)  = int($exectime/60);
  my($secs)  = sprintf("%02d",$exectime%60);
  return ($secs,$mins,$exectime);

  }

# ------------------------------------------------------------------------ 
#                            Programming by Dave Edis <dave@cgi-world.com>

1; # return true;
