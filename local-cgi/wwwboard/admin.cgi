#!/usr/local/bin/perl
##############################################################################
# WWWBoard Admin                Version 2.0 ALPHA 2                          #
# Copyright 1996 Matt Wright    mattw@worldwidemart.com                      #
# Created 10/21/95              Last Modified 11/25/95                       #
# Scripts Archive at:           http://www.worldwidemart.com/scripts/        #
##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright 1996 Matthew M. Wright  All Rights Reserved.                     #
#                                                                            #
# WWWBoard may be used and modified free of charge by anyone so long as      #
# this copyright notice and the comments above remain intact.  By using this #
# code you agree to indemnify Matthew M. Wright from any liability that      #  
# might arise from it's use.                                                 #  
#                                                                            #
# Selling the code for this program without prior written consent is         #
# expressly forbidden.  In other words, please ask first before you try and  #
# make money off of my program.                                              #
#                                                                            #
# Obtain permission before redistributing this software over the Internet or #
# in any other medium.  In all cases copyright and header must remain intact.#
###########################################################################
# Define Variables

INSERT HERE
$mesgdir = "messages";
$datafile = "data.txt";
$mesgfile = "index.html";
$faqfile = "faq.html";
$passwd_file = "passwd.txt";
$ext = "html";
$title = "WWWBoard 2.02A";
$use_time = 1;          # 1 = YES; 0 = NO

# Done
###########################################################################

if ($ENV{'QUERY_STRING'} ne '') {
   $command = "$ENV{'QUERY_STRING'}";
}
else {
   &parse_form;
}

###########################################################################
# Remove                                                                  #
#       This option is useful to see how the threads appear in the        #
#   wwwboard.html document.  It can give you a better idea of whether or  #
#   not you want to remove the whole thread or just part of it.           #
###########################################################################

if ($command eq 'remove') {
print <<EOT; 
Content-type: text/html


<html>
<head>
<title>
Remove Messages From The $title
</title>
</head>
<body bgcolor=white link=blue vlink=navy>
<center>
<table width=600>
<tr><td width=100%>
<font face=arial size=5 color=maroon>
<center>
<U><B>Remove Messages</B></U>
</center>
</font>
</center>
<br>
<br>
<font face=arial size=2>
<p align="justify">
Select below to remove those postings you wish to remove. Checking the Input Box on the left will remove the whole thread
while checking the Input Box on the right to remove just that posting.  These messages have been left unsorted, so that you can 
see the order in which they appear in the $mesgpage page.  This will give you an idea of what the threads look like and is often 
more helpful than the sorted method.<p>
<br>
<hr size=2 color=navy width=600>
<center>
<font face=arial size=1> [ <a href="$cgi_url?remove">Remove</a> | <a href="$cgi_url?remove_by_date">Remove by Date</a> | 
<a href="$cgi_url?remove_by_author">Remove by Author</a> | <a href="$cgi_url?remove_by_num">Remove by Number</a> | 
<a href="$baseurl/$mesgpage">$title</a> ] 
</font>
</center>
<hr size=2 color=navy width=600>
<br>
<br>
<form method=POST action="$cgi_url">
<input type=hidden name="action" value="remove">
<center>
<table border=1 width=600 bgcolor=#E6E6E6 cellpadding=3 cellspacing=0 bordercolor=maroon>
<tr><th colspan=6>
<font face=arial size=2>Username: <input type=text name="username" value=""> &nbsp; 
&nbsp; Password: <input type=password name="password"><br>
<br>
</th></tr>
<tr><th>
<font face=arial size=2 color=green>
<B>Post</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B>Entire Thread</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B>Single Message</B>
</font>
</th><th>
<font face=arial size=2 color=green>
Subject
</font>
</th><th> 
<font face=arial size=2 color=green>
<B>Author</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B>Date</B>
</font>
</th></tr>
EOT



   open(MSGS,"$basedir/$mesgfile");
   @lines = <MSGS>;
   close(MSGS);

   foreach $line (@lines) {

       if ($line =~ /<!--top: (.*)--><a href="messages\/\1\.$ext"><IMG SRC="(.*)\.gif" border=0><\/a> &nbsp; <a href="messages\/\1\.$ext">(.*)<\/a> - <b>(.*)<\/b> &nbsp;\s+(.*)/) {
         push(@ENTRIES,$1);
	 $NULL{$1} = $2;
         $SUBJECT{$1} = $3;
         $AUTHOR{$1} = $4;
         $DATE{$1} = $5;
      }
   }

   @SORTED_ENTRIES = (sort { $a <=> $b } @ENTRIES);
   $max = pop(@SORTED_ENTRIES);
   $min = shift(@SORTED_ENTRIES);

print <<EOT;
<input type=hidden name="min" value="$min">
<input type=hidden name="max" value="$max">
<input type=hidden name="type" value="remove">
EOT

   foreach (@ENTRIES) {
print <<EOT;
<tr><th>
<font face=arial size=2>
<b>$_</b>
</font>
</th><td>
<input type=radio name="$_" value="all"> 
</td><td>
<input type=radio name="$_" value="single"> 
</td><td>
<font face=arial size=2>
<a href="$baseurl/$mesgdir/$_\.$ext">$SUBJECT{$_}</font></a>
</td><td>
<font face=arial size=2>$AUTHOR{$_} </font>
</td><td>
<font face=arial size=2>$DATE{$_}<br></font>
</td></tr>
EOT
	}

   print <<EOT;
</table>
<br>
<br>
<input type=submit value="Remove Messages"> &nbsp<input type="reset" value="Reset Selections">
</form>
</td></tr>
</table> 
</body>
</html>  
EOT
}


###########################################################################
# Remove By Number                                                        #
#       This method is useful to see in what order the messages were      #
#   added to the wwwboard.html document.                                  #
###########################################################################


elsif ($command eq 'remove_by_num') {
print <<EOT; 
Content-type: text/html


<html>
<head>
<title>
Remove Messages From The $title
</title>
</head>
<body bgcolor=white link=blue vlink=navy>
<center>
<table width=600>
<tr><td width=100%>
<font face=arial size=5 color=maroon>
<center>
<U><B>Remove Messages by Number</B></U>
</center>
</font>
</center>
<br>
<br>
<font face=arial size=2>
<p align="justify">
Select below to remove those postings you wish to remove. Checking the Input Box on the left will remove the whole thread
while checking the Input Box on the right to remove just that posting.  These messages have been left unsorted, so that you can 
see the order in which they appear in the $mesgpage page.  This will give you an idea of what the threads look like and is often 
more helpful than the sorted method.<p>
<br>
<hr size=2 color=navy width=600>
<center>
<font face=arial size=1> [ <a href="$cgi_url?remove">Remove</a> | <a href="$cgi_url?remove_by_date">Remove by Date</a> | 
<a href="$cgi_url?remove_by_author">Remove by Author</a> | <a href="$cgi_url?remove_by_num">Remove by Number</a> | 
<a href="$baseurl/$mesgpage">$title</a> ] 
</font>
</center>
<hr size=2 color=navy width=600>
<br>
<br>
<form method=POST action="$cgi_url">
<input type=hidden name="action" value="remove">
<center>
<table border=1 width=600 bgcolor=#E6E6E6 cellpadding=3 cellspacing=0 bordercolor=maroon>
<tr><th colspan=6>
<font face=arial size=2>Username: <input type=text name="username" value=""> &nbsp; 
&nbsp; Password: <input type=password name="password"><br>
<br>
</th></tr>
<tr><th>
<font face=arial size=2 color=green>
<B>Post</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B>Entire Thread</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B>Single Message</B>
</font>
</th><th>
<font face=arial size=2 color=green>
Subject
</font>
</th><th> 
<font face=arial size=2 color=green>
<B>Author</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B>Date</B>
</font>
</th></tr>
EOT



   open(MSGS,"$basedir/$mesgfile");
   @lines = <MSGS>;
   close(MSGS);

   foreach $line (@lines) {

       if ($line =~ /<!--top: (.*)--><a href="messages\/\1\.$ext"><IMG SRC="(.*)\.gif" border=0><\/a> &nbsp; <a href="messages\/\1\.$ext">(.*)<\/a> - <b>(.*)<\/b> &nbsp;\s+(.*)/) {
         push(@ENTRIES,$1);
         $NULL{$1} = $2;
         $SUBJECT{$1} = $3;
         $AUTHOR{$1} = $4;
         $DATE{$1} = $5;
      }
   }

   @SORTED_ENTRIES = (sort { $a <=> $b } @ENTRIES);
   $max = pop(@SORTED_ENTRIES);
   $min = shift(@SORTED_ENTRIES);

print <<EOT;
<input type=hidden name="min" value="$min">
<input type=hidden name="max" value="$max">
<input type=hidden name="type" value="remove">
EOT

   foreach (@ENTRIES) {
print <<EOT;
<tr><th>
<font face=arial size=2>
<b>$_</b>
</font>
</th><td>
<input type=radio name="$_" value="all"> 
</td><td>
<input type=radio name="$_" value="single"> 
</td><td>
<font face=arial size=2>
<a href="$baseurl/$mesgdir/$_\.$ext">$SUBJECT{$_}</font></a>
</td><td>
<font face=arial size=2>$AUTHOR{$_} </font>
</td><td>
<font face=arial size=2>$DATE{$_}<br></font>
</td></tr>
EOT
	}

   print <<EOT;
</table>
<br>
<br>
<input type=submit value="Remove Messages"> &nbsp<input type="reset" value="Reset Selections">
</form>
</td></tr>
</table> 
</body>
</html>  
EOT
}


###########################################################################
# Remove By Date                                                          #
#       Using this method allows you to delete all messages posted before #
#   a certain date.                                                       #
###########################################################################

elsif ($command eq 'remove_by_date') {
print <<EOT; 
Content-type: text/html


<html>
<head>
<title>
Remove Messages From The $title
</title>
</head>
<body bgcolor=white link=blue vlink=navy>
<center>
<table width=600>
<tr><td width=100%>
<font face=arial size=5 color=maroon>
<center>
<U><B>Remove Messages by Posting Date</B></U>
</center>
</font>
</center>
<br>
<br>
<font face=arial size=2>
<p align="justify">
Select below to remove those postings you wish to remove. Checking the Input Box on the left will remove the whole thread
while checking the Input Box on the right to remove just that posting.  These messages have been left unsorted, so that you can 
see the order in which they appear in the $mesgpage page.  This will give you an idea of what the threads look like and is often 
more helpful than the sorted method.<p>
<br>
<hr size=2 color=navy width=600>
<center>
<font face=arial size=1> [ <a href="$cgi_url?remove">Remove</a> | <a href="$cgi_url?remove_by_date">Remove by Date</a> | 
<a href="$cgi_url?remove_by_author">Remove by Author</a> | <a href="$cgi_url?remove_by_num">Remove by Number</a> | 
<a href="$baseurl/$mesgpage">$title</a> ] 
</font>
</center>
<hr size=2 color=navy width=600>
<br>
<br>
<form method=POST action="$cgi_url">
<input type=hidden name="action" value="remove">
<center>
<table border=1 width=600 bgcolor=#E6E6E6 cellpadding=3 cellspacing=0 bordercolor=maroon>
<tr><th colspan=4>
<font face=arial size=2>Username: <input type=text name="username" value=""> &nbsp; 
&nbsp; Password: <input type=password name="password"><br>
<br>
</th></tr>
<tr><th>
<font face=arial size=2 color=green>
<B>X</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B>Date</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B># of Message(s)</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<b>Message Number(s)</B>
</font>
</th></tr>
EOT



   open(MSGS,"$basedir/$mesgfile");
   @lines = <MSGS>;
   close(MSGS);

   foreach $line (@lines) {

       if ($line =~ /<!--top: (.*)--><a href="messages\/\1\.$ext"><IMG SRC="(.*)\.gif" border=0><\/a> &nbsp; <a href="messages\/\1\.$ext">.*<\/a> - <b>.*<\/b> &nbsp; Posted\s+(.*)/) {
         $date = $3;
         if ($use_time == 1) {
            ($time,$day) = split(/\s+/,$date);
         }
         else {
            $day = $date;
         }
         $DATE{$1} = $day;
      }
   }

   undef(@used_values);
   foreach $value (sort (values %DATE)) {
      $match = '0';
      $value_number = 0;
      foreach $used_value (@used_values) {
         if ($value eq $used_value) {
            $match = '1';
            last;
         }
      }
      if ($match == '0') {
         undef(@values); undef(@short_values);
         foreach $key (keys %DATE) {
            if ($value eq $DATE{$key}) {
               $key_url = "<a href=\"$baseurl/$mesgdir/$key\.$ext\">$key</a>";
               push(@values,$key_url);
	       push(@short_values,$key);
               $value_number++;
            }
         }
         $form_value = $value;
         $form_value =~ s/\//_/g;
         print <<EOT;
<tr><td>
<input type=checkbox name="$form_value" value="@short_values">
</td><th>
<font face=arial size=2>
$value
</font>
</th><td>
<font face=arial size=2>
$value_number
</font>
</td><td>
<font face=arial size=2>
@values
</font>
<br>
</td></tr>
EOT

         push(@used_values,$value);
         push(@used_form_values,$form_value);
      }
   }

   print <<EOT;
</table>
<br>
<br>
<input type=submit value="Remove Messages"> &nbsp<input type="reset" value="Reset Selections">
</form>
</td></tr>
</table> 
</body>
</html>  
EOT
}


###########################################################################
# Remove By Author                                                        #
#       This option makes a list of all known authors and then groups     #
#    together there postings and allows you to remove them all at once.   #
###########################################################################

elsif ($command eq 'remove_by_author') {
print <<EOT;
Content-type: text/html


<html>
<head>
<title>
Remove Messages From The $title
</title>
</head>
<body bgcolor=white link=blue vlink=navy>
<center>
<table width=600>
<tr><td width=100%>
<center>
<font face=arial size=5 color=maroon>
<U><B>Remove Messages By Author</B></U>
</font>
</center>
<br>
<br>
<font face=arial size=2>
<p align="justify">
Checking the checkbox beside the name of an author will remove all postings which that author has created.<br>
<br>
<hr size=2 color=navy width=600><center><font face=arial size=1> [ <a href="$cgi_url\?remove">Remove</a> | 
<a href="$cgi_url\?remove_by_date">Remove by Date</a> | <a href="$cgi_url\?remove_by_author">Remove by Author</a> | 
<a href="$cgi_url\?remove_by_num">Remove by Number</a> |
<a href="$baseurl/$mesgpage">$title</a> ] </font></center><hr size=2 color=navy width=600><p><p>
<form method=POST action="$cgi_url"><input type=hidden name="action" value="remove_by_date_or_author">
<input type=hidden name="type" value="remove_by_author">
<center>
<table border=1 width=600 bgcolor=#E6E6E6 cellpadding=3 cellspacing=0 bordercolor=maroon>
<tr><th colspan=4>
<font face=arial size=2>Username: <input type=text name="username" value=""> &nbsp; 
&nbsp; Password: <input type=password name="password"><br>
<br>
</th></tr>
<th><font face=arial size=2 color=green>
<B>X</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B>Author</B>
</font>
</th><th>
<font face=arial size=2 color=green>
<B># of Messages</B>
</font></th>
<th><font face=arial size=2 color=green><B>Message #'s</B></font><br></th>
</tr>
EOT

   open(MSGS,"$basedir/$mesgfile");
   @lines = <MSGS>;
   close(MSGS);

   foreach $line (@lines) {
   
       if ($line =~ /<!--top: (.*)--><a href="messages\/\1\.$ext"><IMG SRC="(.*)\.gif" border=0><\/a> &nbsp; <a href="messages\/\1\.$ext">(.*)<\/a> - <b>(.*)<\/b> &nbsp;\s+(.*)/) {
         $NULL{$1} = $2;
         $AUTHOR{$1} = $4;  	 
      }
   }

   undef(@used_values);
   foreach $value (sort (values %AUTHOR)) {
      $match = '0';
      $value_number = 0;
      foreach $used_value (@used_values) {
         if ($value eq $used_value) {
            $match = '1';
            last;
         }
      }
      if ($match == '0') {
         undef(@values); undef(@short_values);
         foreach $key (keys %AUTHOR) {
            if ($value eq $AUTHOR{$key}) {
               $key_url = "<a href=\"$baseurl/$mesgdir/$key\.$ext\">$key</a>";
               push(@values,$key_url);
               push(@short_values,$key);
               $value_number++;
            }
         }
         $form_value = $value;
         $form_value =~ s/ /_/g;
         print <<EOT;
<tr><td>
<input type=checkbox name="$form_value" value="@short_values"> 
</td><th align=left>
<font face=arial size=2>$value</font>
</th><td>
<font face=arial size=2>$value_number</font>
</td><td>
<font face=arial size=2>@values</font>
<br>
</td></tr>
EOT
         push(@used_values,$value);
         push(@used_form_values,$form_value);
      }
   }
   print <<EOT;
</table>
<br>
<br>
<input type=hidden name="used_values" value="@used_form_values">
<input type=submit value="Remove Messages"> &nbsp;<input type=reset value="Reset Selections">
</form>
</center>
</tr></td>
</table>
</body>
</html>
EOT

}

###########################################################################
# Change Password                                                         #
#       By calling this section of the script, the admin can change his or#
#   her password.							  #
###########################################################################

elsif ($command eq 'change_passwd') {

   print "Content-type: text/html\n\n<html><head><title>Change WWWBoard Admin Password</title></head>\n";
   print "<body><center><h1>Change WWWBoard Admin Password</h1></center>\n";
   print "Fill out the form below completely to change your password and user name.\n";
   print "If new username is left blank, your old one will be assumed.<p><hr size=7 width=75%><p>\n";
   print "<form method=POST action=\"$cgi_url\">\n";
   print "<input type=hidden name=\"action\" value=\"change_passwd\">\n";
   print "<center><table border=0>\n";
   print "<tr>\n";
   print "<th align=left>Username: </th><td><input type=text name=\"username\"><br></td>\n";
   print "</tr><tr>\n";
   print "<th align=left>Password: </th><td><input type=password name=\"password\"><br></td>\n";
   print "</tr><tr> </tr><tr>\n";
   print "<th align=left>New Username: </th><td><input type=text name=\"new_username\"><br></td>\n";
   print "</tr><tr>\n";
   print "<th align=left>New Password: </th><td><input type=password name=\"passwd_1\"><br></td>\n";
   print "</tr><tr>\n";
   print "<th align=left>Re-type New Password: </th><td><input type=password name=\"passwd_2\"><br></td>\n";
   print "</tr><tr>\n";
   print "<td align=center><input type=submit value=\"Change Password\"> </td><td align=center><input type=reset></td>\n";
   print "</tr></table></center>\n";
   print "</form></body></html>\n";

}

###########################################################################
# Remove Action                                                           #
#       This portion is used by the options remove and remove_by_num.     #
###########################################################################

elsif ($FORM{'action'} eq 'remove') {

   &check_passwd;

   for ($i = $FORM{'min'}; $i <= $FORM{'max'}; $i++) {
      if ($FORM{$i} eq 'all') {
         push(@ALL,$i);
      }
      elsif ($FORM{$i} eq 'single') {
         push(@SINGLE,$i);
      }
   }

   open(MSGS,"$basedir/$mesgfile");
   @lines = <MSGS>;
   close(MSGS);

   foreach $single (@SINGLE) {
      foreach ($j = 0;$j <= @lines;$j++) {
         if ($lines[$j] =~ /<!--top: $single-->/) {
            splice(@lines, $j, 3);
            $j -= 3;
         }
         elsif ($lines[$j] =~ /<!--end: $single-->/) {
            splice(@lines, $j, 1);
            $j--;
         }
      }
      $filename = "$basedir/$mesgdir/$single\.$ext";
      if (-e $filename) {
         unlink("$filename") || push(@NOT_REMOVED,$single);
      }
      else {
         push(@NO_FILE,$single);
      }
      push(@ATTEMPTED,$single);
   }

   foreach $all (@ALL) {
      undef($top); undef($bottom);
      foreach ($j = 0;$j <= @lines;$j++) {
         if ($lines[$j] =~ /<!--top: $all-->/) {
            $top = $j;
         }
         elsif ($lines[$j] =~ /<!--end: $all-->/) {
            $bottom = $j;
         }
      }
      if ($top && $bottom) {
         $diff = ($bottom - $top);
         $diff++;
         for ($k = $top;$k <= $bottom;$k++) {
            if ($lines[$k] =~ /<!--top: (.*)-->/) {
               push(@DELETE,$1);
            }
         }
         splice(@lines, $top, $diff);
         foreach $delete (@DELETE) {
            $filename = "$basedir/$mesgdir/$delete\.$ext";
            if (-e $filename) {
               unlink($filename) || push(@NOT_REMOVED,$delete);
            }
            else {
               push(@NO_FILE,$delete);
            }
            push(@ATTEMPTED,$delete);
         }
      }
      else {
         push(@TOP_BOT,$all);
      }
   }

   open(WWWBOARD,">$basedir/$mesgfile");
   print WWWBOARD @lines;
   close(WWWBOARD);      

   &return_html($FORM{'type'});

}

###########################################################################
# Remove Action by Date or Author                                         #
#       This portion is used by the method remove_by_date or 		  #
#   remove_by_author.     		  				  #
###########################################################################

elsif ($FORM{'action'} eq 'remove_by_date_or_author') {

   &check_passwd;

   @used_values = split(/\s/,$FORM{'used_values'});
   foreach $used_value (@used_values) {
      @misc_values = split(/\s/,$FORM{$used_value});
      foreach $misc_value (@misc_values) {
         push(@SINGLE,$misc_value);
      }
   }

   open(MSGS,"$basedir/$mesgfile");
   @lines = <MSGS>;
   close(MSGS);

   foreach $single (@SINGLE) {
      foreach ($j = 0;$j <= @lines;$j++) {
         if ($lines[$j] =~ /<!--top: $single-->/) {
            splice(@lines, $j, 3);
            $j -= 3;
         }
         elsif ($lines[$j] =~ /<!--end: $single-->/) {
            splice(@lines, $j, 1);
            $j--;
         }
      }
      $filename = "$basedir/$mesgdir/$single\.$ext";
      if (-e $filename) {
         unlink("$filename") || push(@NOT_REMOVED,$single);
      }
      else {
         push(@NO_FILE,$single);
      }
      push(@ATTEMPTED,$single);
   }

   open(WWWBOARD,">$basedir/$mesgfile");
   print WWWBOARD @lines;
   close(WWWBOARD);

   &return_html($FORM{'type'});

}

elsif ($FORM{'action'} eq 'change_passwd') {

   open(PASSWD,"$basedir/$passwd_file") || &error(passwd_file);
   $passwd_line = <PASSWD>;
   chop($passwd_line) if $passwd_line =~ /\n$/;
   close(PASSWD);

   ($username,$passwd) = split(/:/,$passwd_line);

   if (!($FORM{'passwd_1'} eq $FORM{'passwd_2'})) {
      &error(not_same);
   }

   $test_passwd = crypt($FORM{'password'}, substr($passwd, 0, 2));
   if ($test_passwd eq $passwd && $FORM{'username'} eq $username) {
      open(PASSWD,">$basedir/$passwd_file") || &error(no_change);
      $new_password = crypt($FORM{'passwd_1'}, substr($passwd, 0, 2));
      if ($FORM{'new_username'}) {
         $new_username = $FORM{'new_username'};
      }
      else {
         $new_username = $username;
      }
      print PASSWD "$new_username:$new_password";
      close(PASSWD);
   }
   else {
      &error(bad_combo);
   }

   &return_html(change_passwd);
}

else {
   print <<EOT;
Content-type: text/html


<html>
<head>
<title>
Administrative Control Panel
</title>
</head>
<body bgcolor=#FFFFFF text=#000000>
<center>
<h2>
<font color=maroon face=arial>
Administrative Control Panel for the WWWBoard
</font>
</h2>
</center>
<br>
<font face=arial size=2>
Delete posts to the Message Board using the following options:<br>
<br>
<hr size=4 width=75% color=navy>
<br>
<ul>
<li>Remove Files
<ul>
<li><a href=\"$cgi_url\?remove\">Remove Files</a>
<li><a href=\"$cgi_url\?removeby_num\">Remove Files by Mesage Number</a>
<li><a href=\"$cgi_url\?remove_by_author\">Remove Files by Author</a>
</ul>
</ul><br><hr size=4 width=75% color=navy>
EOT
}

#######################
# Parse Form Subroutine

sub parse_form {

   # Get the input
   read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});

   # Split the name-value pairs
   @pairs = split(/&/, $buffer);

   foreach $pair (@pairs) {
      ($name, $value) = split(/=/, $pair);

      # Un-Webify plus signs and %-encoding
      $value =~ tr/+/ /;
      $value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;

      $FORM{$name} = $value;
   }
}

sub return_html {
   $type = $_[0];
   if ($type eq 'remove') {
      print "Content-type: text/html\n\n<html><head><title>Results of Message Board Removal</title></head>\n";
      print "<body><center><h1>Results of Message Board Removal</h1></center>\n";
   }
   elsif ($type eq 'remove_by_num') {
      print "Content-type: text/html\n\n<html><head><title>Results of Message Board Removal by Number</title></head>\n";
      print "<body><center><h1>Results of Message Board Removal by Number</h1></center>\n";
   }
   elsif ($type eq 'remove_by_date') {
      print "Content-type: text/html\n\n<html><head><title>Results of Message Board Removal by Date</title></head>\n";
      print "<body><center><h1>Results of Message Board Removal by Date</h1></center>\n";
   }
   elsif ($type eq 'remove_by_author') {
      print "Content-type: text/html\n\n<html><head><title>Results of Message Board Removal by Author</title></head>\n";
      print "<body><center><h1>Results of Message Board Removal by Author</h1></center>\n";
   }
   elsif ($type eq 'change_passwd') {
      print "Content-type: text/html\n\n<html><head><title>WWWBoard WWWAdmin Password Changed</title></head>\n";
      print "<body><center><h1>WWWBoard WWWAdmin Password Changed</h1></center>\n";
      print "Your Password for WWWBoard WWWAdmin has been changed!  Results are below:<p><hr size=7 width=75%><p>\n";
      print "<b>New Username: $new_username<p>\n";
      print "New Password: $FORM{'passwd_1'}</b><p>\n";
      print "<hr size=7 width=75%><p>\n";
      print "Do not forget these, since they are now encoded in a file, and not readable!.\n";
      print "</body></html>\n";
   }
   if ($type =~ /^remove/) {
      print "Below is a short summary of what messages were removed from $mesgpage and the\n";
      print "$mesgdir directory.  All files that the script attempted to remove, were removed,\n";
      print "unless there is an error message stating otherwise.<p><hr size=7 width=75%><p>\n";
 
      print "<b>Attempted to Remove:</b> @ATTEMPTED<p>\n";
      if (@NOT_REMOVED) {
         print "<b>Files That Could Not Be Deleted:</b> @NOT_REMOVED<p>\n";
      }
      if (@NO_FILE) {
         print "<b>Files Not Found:</b> @NO_FILE<p>\n";
      }
      print "<hr size=7 width=75%><center><font size=-1>\n";
      print "[ <a href=\"$cgi_url\?remove\">Remove</a> ] [ <a href=\"$cgi_url\?remove_by_date\">Remove by Date</a> ] [ <a href=\"$cgi_url\?remove_by_author\">Remove by Author</a> ] [ <a href=\"$cgi_url\?remove_by_num\">Remove by Message Number</a> ] [ <a 


href=\"$baseurl/$mesgpage\">$title</a> ]\n";
      print "</font></center><hr size=7 width=75%>\n";
      print "</body></html>\n";
   }
}

sub error {
   $error = $_[0];
   if ($error eq 'bad_combo') {
      print "Content-type: text/html\n\n<html><head><title>Bad Username - Password Combination</title></head>\n";
      print "<body><center><h1>Bad Username - Password Combination</h1></center>\n";
      print "You entered and invalid username password pair.  Please try again.<p>\n";
      &passwd_trailer
   }
   elsif ($error eq 'passwd_file') {
      print "Content-type: text/html\n\n<html><head><title>Could Not Open Password File For Reading</title></head>\n";
      print "<body><center><h1>Could Not Open Password File For Reading</h1></center>\n";
      print "Could not open the password file for reading!  Check permissions and try again.<p>\n";
      &passwd_trailer
   }
   elsif ($error eq 'not_same') {
      print "Content-type: text/html\n\n<html><head><title>Incorrect Password Type-In</title></head>\n";
      print "<body><center><h1>Incorrect Password Type-In</h1></center>\n";
      print "The passwords you typed in for your new password were not the same.\n";
      print "You may have mistyped, please try again.<p>\n";
      &passwd_trailer
   }
   elsif ($error eq 'no_change') {
      print "Content-type: text/html\n\n<html><head><title>Could Not Open Password File For Writing</title></head>\n";
      print "<body><center><h1>Could Not Open Password File For Writing</h1></center>\n";
      print "Could not open the password file for writing!  Password not changed!<p>\n";
      &passwd_trailer
   }

   exit;
}

sub passwd_trailer {
   print "<hr size=7 width=75%><center><font size=-1>\n";
   print "[ <a href=\"$cgi_url\">WWWAdmin</a> ] [ <a href=\"$baseurl/$mesgpage\">$title</a> ]\n";
   print "</font></center><hr size=7 width=75%>\n";
   print "</body></html>\n";
}

sub check_passwd {
   open(PASSWD,"$basedir/$passwd_file") || &error(passwd_file);
   $passwd_line = <PASSWD>;
   chop($passwd_line) if $passwd_line =~ /\n$/;
   close(PASSWD);

   ($username,$passwd) = split(/:/,$passwd_line);

   $test_passwd = crypt($FORM{'password'}, substr($passwd, 0, 2));
   if (!($test_passwd eq $passwd && $FORM{'username'} eq $username)) {
      &error(bad_combo);
   }
}
v
