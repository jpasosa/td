#!/usr/local/bin/perl -w

require "submit.conf";



$date=`date +%D`;
$hour=`date +%l`;
chomp $hour;
$minute=`date +%M`;
chomp $minute;
$ampm=`date +%p`;
chomp $ampm;
$time="$hour:$minute $ampm";
chomp $date;
chomp $time;


INSERT HERE

$mesgdir = "messages";
$datafile = "data.txt";
$mesgfile = "index.html";
$faqfile = "faq.html";
$ext = "html";


# Done
###########################################################################

###########################################################################
# Configure Options

$allow_html = 0;	# 1 = YES; 0 = NO
$quote_text = 1;	# 1 = YES; 0 = NO
$subject_line = 0;	# 0 = Quote Subject Editable; 1 = Quote Subject 
			#   UnEditable; 2 = Don't Quote Subject, Editable.

# Done
###########################################################################

open (FILE,"$forum_dir");
@top=<FILE>;
close(FILE);

# Get the Data Number
&get_number;

# Get Form Information
&parse_form;

# Put items into nice variables
&get_variables;

#GABE'S GAY CHECK
&GabeCheck;

# Open the new file and write information to it.
&new_file;

# Open the Main WWWBoard File to add link
&main_page;

# Now Add Thread to Individual Pages
if ($num_followups >= 1) {
   &thread_pages;
}

# Return the user HTML
&return_html;

# Increment Number
&increment_num;

&SendOrigEmail;

&SendAdminEmail;

############################
# Get Data Number Subroutine

sub get_number {
   open(NUMBER,"$basedir/$datafile");
   $num = <NUMBER>;
   close(NUMBER);
   if ($num == 99999)  {
      $num = "1";
   }
   else {
      $num++;
   }
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
      $value =~ s/<!--(.|\n)*-->//g;

      if ($allow_html != 1) {
         $value =~ s/<([^>]|\n)*>//g;
      }
      else {
         unless ($name eq 'body') {
	    $value =~ s/<([^>]|\n)*>//g;
         }
      }

      $FORM{$name} = $value;
   }

}

###############
# Get Variables

sub get_variables {

   if ($FORM{'followup'}) {
      $followup = "1";
      @followup_num = split(/,/,$FORM{'followup'});
      $num_followups = @followups = @followup_num;
      $last_message = pop(@followups);
      $origdate = "$FORM{'origdate'}";
      $origname = "$FORM{'origname'}";
      $origsubject = "$FORM{'origsubject'}";
   }
   else {
      $followup = "0";
   }

   if ($FORM{'name'}) {
      $name = "$FORM{'name'}";
      $name =~ s/"//g;
      $name =~ s/<//g;
      $name =~ s/>//g;
      $name =~ s/\&//g;
   }
   else {
      &error(no_name);
   }

   if ($FORM{'email'} =~ /.*\@.*\..*/) {
      $email = "$FORM{'email'}";
   }

   if ($FORM{'subject'}) {
      $subject = "$FORM{'subject'}";
      $subject =~ s/\&/\&amp\;/g;
      $subject =~ s/"/\&quot\;/g;
   }
   else {
      &error(no_subject);
   }

   if ($FORM{'url'} =~ /.*\:.*\..*/ && $FORM{'url_title'}) {
      $message_url = "$FORM{'url'}";
      $message_url_title = "$FORM{'url_title'}";
   }

   if ($FORM{'img'} =~ /.*tp:\/\/.*\..*/) {
      $message_img = "$FORM{'img'}";
   }

   if ($FORM{'body'}) {
      $body = "$FORM{'body'}";
      $body =~ s/\cM//g;
      $body =~ s/\n\n/<p>/g;
      $body =~ s/\n/<br>/g;

      $body =~ s/&lt;/</g; 
      $body =~ s/&gt;/>/g; 
      $body =~ s/&quot;/"/g;
   }
   else {
      &error(no_body);
   }

   if ($quote_text == 1) {
      $hidden_body = "$body";
      $hidden_body =~ s/</&lt;/g;
      $hidden_body =~ s/>/&gt;/g;
      $hidden_body =~ s/"/&quot;/g;
   }
}

sub GabeCheck {

if (!($email=~ /\@/ && $email=~ /\./)) {
print <<EOT;
Content-type: text/html


<html>
<head>
<title>WWWBoard: Add a Posting</title>
<BODY BGCOLOR="White" TEXT="Black" LINK="Blue" VLINK="Navy" ALINK="Maroon">
</head>

<center>
<table width=600 cellpadding=0 cellspacing=0>
<tr><td width=100%>
<center>
<font color=maroon face=arial size=5><b>WWWBoard New Message Error</b></font><br>
</center>
     
<br>
<br>
   
<form method=POST action="submit.cgi">
<center>      
<table width=600>
<tr><td width=50>
&nbsp;&nbsp;
</td><td width=550>

<font face=arial size=2>
<B>The e-mail address you entered is invalid</B> Please submit your posting with your e-mail address so you will know when someone has responded to your posting.
<br>
<br>
<hr size=1>   
<br>
<br>

<table width=100%>
<tr><td width=20%>
<font face=arial size=2>
<b>Name:</b></font>
</td><td width=80%>
<input type=text name="name" size=50 value=$name">
</td></tr>
<tr><td width=20%>
<font face=arial size=2>
<b>E-Mail:</b></font>
</td><td width=80%>
<input type=text name="email" size=50>
</td></tr>
<tr><td width=20%>
<font face=arial size=2>
<b>Subject:</b></font>
</td><td width=80%>
<input type=text name="subject" size=50 value="$subject">
</td></tr>    
</table>
<br>
<font face=arial size=2>
<b><U><font color=navy>Message to Post</font></U></b><br>
<br>
<textarea COLS=55 ROWS=10 name="body">$body</textarea>
<br>
<br>
<table width=100%>
<tr><td width=100%>
<br>
<center>
<input type=submit value="Post Message"> &nbsp; <input type=reset value="Reset Form">
</form>
</td></tr>
</table>  

</td></tr>
</table>

<br>
<br>
<hr size=1>

<br>
<br>
<br>
<br>
<br>

</td></tr>
</table>

</td></tr> 
</table>

</body>
</html>

EOT
exit;
	}
}

#####################
# New File Subroutine

sub new_file {

   open(NEWFILE,">$basedir/$mesgdir/$num\.$ext") || die $!;
print NEWFILE <<EOT;
<html>
<head>
<title>WWWBoard New Message: Message $num: $subject</title>
<BODY BGCOLOR="White" TEXT="Black" LINK="Blue" VLINK="Navy" ALINK="Maroon">
</head>

<center>
<table width=600 cellpadding=0 cellspacing=0>
<tr><td width=100%>
<center>
<font color=maroon face=arial size=5><b>WWWBoard New Message: Message $num: $subject</b></font><br>
</center>  
<br>
<br>
<center>
<font size="4" face="arial,helvetica">
<br>
<font color=maroon><b>
WWWBoard: Message $num
</b></font>
<br>
</center><br>		
<font face=arial size=2>
<hr size=1 width=75% color=navy>
<center> 
[ <a href="#followups">Follow Ups</a> ] [ <a href="#postfp">Post Followup</a> ] [ <a href="$baseurl/$mesgfile">$title</a> ] 
</center>
<hr size=1 width=75% color=navy><br>
<br>
<table width=600>
<tr><td width=70>
&nbsp;&nbsp;
</td><td width=530>
<font face=arial size=2><b>Posted by</b> <A HREF="mailto:$email">$name</a> on $date at $time<br>
<br><b>Subject:</b> &nbsp; $subject
<br>
<br>
<br>
<font face=arial size=2>
<b><U><font color=navy>Message Posted</font></U></b>
<br>
<br>
</td></tr></table>
<table width=600>
<tr><td width=68>
</td><td width=532>
<table width=85%>
<tr><td>
<font face=arial size=2>
EOT

   if ($followup == 1) {
      print NEWFILE "<u>In Reply to: <a href=\"$last_message\.$ext\">$origsubject</a> posted by $origname on $origdate:</u><p>\n";
   }

   print NEWFILE <<EOT;
$body
</td></tr></table>
</td></tr></table>
<br>
<br>
<table width=600>
<tr>
<td width=70>
&nbsp;&nbsp;
</td>
<td width=530><font face=arial size=2>
<hr size=1 width=470 color=navy align=left>
<br>
<a name="followups"><B>Follow Ups:</B></a><br>
<ul><!--insert: $num-->
</ul><!--end: $num-->
<br>
<hr size=1 width=470 color=navy align=left>
<br><a name="postfp"><br><br>
<center><font color=maroon size=4><B>Post a Followup</B></a></font></center><p>
<form method=POST action="$cgi_url">
EOT

   print NEWFILE "<input type=hidden name=\"followup\" value=\"";
   if ($followup == 1) {
      foreach $followup_num (@followup_num) {
         print NEWFILE "$followup_num,";
      }
   }
   print NEWFILE "$num\">\n";

print NEWFILE <<EOT;
<input type=hidden name="origname" value="$name">
<input type=hidden name="origemail" value="$email">
<input type=hidden name="origsubject" value="$subject">
<input type=hidden name="origdate" value="$date at $time">

<table width=100%>
<tr><td width=20%>
<font face=arial size=2>
<b>Name:</b></font>
</td><td width=80%>
<input type=text name="name" size=50>
</td></tr>
<tr><td width=20%>
<font face=arial size=2>
<b>E-Mail:</b></font>
</td><td width=80%>
<input type=text name="email" size=50>
</td></tr>
EOT

if ($subject_line == 1) {
      if ($subject_line =~ /^Re:/) {
         print NEWFILE <<EOT;
<tr><td width=20%>
<font face=arial size=2>
<b>Subject:</b></font>
</td><td width=80%>
<input type="hidden" name="subject" value="$subject" size=50>
Subject: <b>$subject</b>
</td></tr>  
</table>
<br>
EOT
      }
      else {
         print NEWFILE <<EOT;
<tr><td width=20%>
<font face=arial size=2>
<b>Subject:</b></font>
</td><td width=80%>
<input type=hidden name="subject" value="Re: $subject" size=50>
Subject: <b>Re: $subject</b>
</td></tr>  
</table>
<br>
EOT
      }
   } 
   elsif ($subject_line == 2) {
         print NEWFILE <<EOT;
<tr><td width=20%>
<font face=arial size=2>
<b>Subject:</b></font>
</td><td width=80%>
<input type=text name="subject" size=50>
</td></tr>
</table> 
<br>
EOT

   }
   else {
      if ($subject =~ /^Re:/) {
         print NEWFILE <<EOT;
<tr><td width=20%>
<font face=arial size=2>
<b>Subject:</b></font>
</td><td width=80%>
<input type=text name="subject" value="$subject" size=50>
</td></tr>
</table> 
<br>
EOT
      }
      else {
         print NEWFILE <<EOT;
<tr><td width=20%>
<font face=arial size=2>
<b>Subject:</b></font>
</td><td width=80%>
<input type=text name="subject" value="Re: $subject" size=50>
</td></tr>
</table> 
<br>
EOT
      }
   }

   print NEWFILE <<EOT;
<font face=arial size=2>
<b><U><font color=navy>Message to Post</font></U></b><br>
<br>
<textarea COLS=55 ROWS=10 name="body">
EOT
   if ($quote_text == 1) {
      @chunks_of_body = split(/\&lt\;p\&gt\;/,$hidden_body);
      foreach $chunk_of_body (@chunks_of_body) {
         @lines_of_body = split(/\&lt\;br\&gt\;/,$chunk_of_body);
         foreach $line_of_body (@lines_of_body) {
            print NEWFILE ": $line_of_body\n";
         }
         print NEWFILE "\n";
      }
   }
   print NEWFILE <<EOT;
</textarea>
<br>
<br>
<table width=100%>
<tr><td width=100%>
<br>
<center>
<input type=submit value="Post Followup Message"> &nbsp; <input type=reset value="Reset Form">
</form>
</center>
<br>
<br>
<hr size=1 width=95% color=navy align=left>
<font face=arial size=2>
[ <a href="../faq.html"><FONT COLOR="blue" onMouseOver="this.style.color = 'maroon'" onMouseOut="this.style.color = 'blue'">Posting Rules</font></a>
| <a href="#followups"><FONT COLOR="blue" onMouseOver="this.style.color = 'maroon'" onMouseOut="this.style.color = 'blue'">Follow Ups</font></a> |
<a href="../index.html"><FONT COLOR="blue" onMouseOver="this.style.color = 'maroon'" onMouseOut="this.style.color = 'blue'">Return to WWWBoard</font></a> ]
<hr size=1 width=95% color=navy align=left>
</td></tr>
</table>

</td></tr>
</table>

</td></tr>
</table>

</body>
</html>
EOT
   close(NEWFILE);
}


###############################
# Main WWWBoard Page Subroutine

sub main_page {
   open(MAIN,"$basedir/$mesgfile") || die $!;
   @main = <MAIN>;
   close(MAIN);

   open(MAIN,">$basedir/$mesgfile") || die $!;
   if ($followup == 0) {
      foreach $main_line (@main) {
         if ($main_line =~ /<!--begin-->/) {
            print MAIN "<!--begin-->\n";
	    print MAIN "<!--top: $num--><a href=\"$mesgdir/$num\.$ext\"><IMG SRC=\"newmessage.gif\" border=0><\/a> \&nbsp\; <a href=\"$mesgdir/$num\.$ext\">$subject</a> - <b>$name</b> \&nbsp\; Posted on $date at $time\n";
            print MAIN "(<!--responses: $num-->0)\n";
            print MAIN "<ul><!--insert: $num-->\n";
            print MAIN "</ul><!--end: $num-->\n";
         }
         else {
            print MAIN "$main_line";
         }
      }
   }
   else {
      foreach $main_line (@main) {
	 $work = 0;
         if ($main_line =~ /<ul><!--insert: $last_message-->/) {
            print MAIN "<ul><!--insert: $last_message-->\n";
            print MAIN "<!--top: $num--><a href=\"$mesgdir/$num\.$ext\"><IMG SRC=\"response.gif\" border=0><\/a> \&nbsp\; <a href=\"$mesgdir/$num\.$ext\">$subject</a> - <b>$name</b> \&nbsp\; Posted on $date at $time\n";
            print MAIN "(<!--responses: $num-->0)\n";
            print MAIN "<ul><!--insert: $num-->\n";
            print MAIN "</ul><!--end: $num-->\n";
         }
         elsif ($main_line =~ /\(<!--responses: (.*)-->(.*)\)/) {
            $response_num = $1;
            $num_responses = $2;
            $num_responses++;
            foreach $followup_num (@followup_num) {
               if ($followup_num == $response_num) {
                  print MAIN "(<!--responses: $followup_num-->$num_responses)\n";
		  $work = 1;
               }
            }
            if ($work != 1) {
               print MAIN "$main_line";
            }
         }
         else {
            print MAIN "$main_line";
         }
      }
   }
   close(MAIN);
}

############################################
# Add Followup Threading to Individual Pages
sub thread_pages {

   foreach $followup_num (@followup_num) {
      open(FOLLOWUP,"$basedir/$mesgdir/$followup_num\.$ext");
      @followup_lines = <FOLLOWUP>;
      close(FOLLOWUP);

      open(FOLLOWUP,">$basedir/$mesgdir/$followup_num\.$ext");
      foreach $followup_line (@followup_lines) {
         $work = 0;
         if ($followup_line =~ /<ul><!--insert: $last_message-->/) {
	        print FOLLOWUP "<ul><!--insert: $last_message-->\n";
            print FOLLOWUP "<br><!--top: $num--><a href=\"$num.$ext\"><IMG SRC=\"response.gif\" border=0><\/a> \&nbsp\; <a href=\"$num.$ext\">$subject<\/a> <b>$name<\/b> &nbsp; Posted at: $date\n";
            print FOLLOWUP "(<!--responses: $num-->0)\n";
            print FOLLOWUP "<ul><!--insert: $num-->\n";
            print FOLLOWUP "</ul><!--end: $num-->\n";
         }
         elsif ($followup_line =~ /\(<!--responses: (.*)-->(.*)\)/) {
            $response_num = $1;
            $num_responses = $2;
            $num_responses++;
            foreach $followup_num (@followup_num) {
               if ($followup_num == $response_num) {
                  print FOLLOWUP "(<!--responses: $followup_num-->$num_responses)\n";
                  $work = 1;
               }
            }
            if ($work != 1) {
               print FOLLOWUP "$followup_line";
            }
         }
         else {
            print FOLLOWUP "$followup_line";
         }
      }
      close(FOLLOWUP);
   }
}

sub return_html {
print <<EOT;
Content-type: text/html


<html>
<head>
<title>WWWBoard: Message Added</title>
<BODY BGCOLOR="White" TEXT="Black" LINK="Blue" VLINK="Navy" ALINK="Maroon">
</head>

<center>
<table width=600 cellpadding=0 cellspacing=0>
<tr><td width=100%>
<center>
<font color=maroon face=arial size=5><b>New Message Added to WWWBoard</b></font><br>
</center>  
<br>
<br>		
<font face=arial size=2>

<hr size=1 width=75% color=navy>
<center> [ <a href=\"$baseurl/$mesgdir/$num\.$ext\"><FONT COLOR="blue" onMouseOver="this.style.color = 'maroon'" onMouseOut="this.style.color = 'blue'">Go To Your Message</font></a> | <a href=\"$baseurl/$mesgfile\"><FONT COLOR="blue" onMouseOver="this.style.color = 'maroon'" onMouseOut="this.style.color = 'blue'">Back to WWWBoard</font></a> ]</center>
<hr size=1 width=75% color=navy>

<br>
<table width=600>
<tr><td width=67>
&nbsp;&nbsp;
</td><td width=533>

<center>
<table width=100%>
<tr><td width=30%>
<font face=arial size=2>
<b>Your Name:
</td><td width=70%>
<font face=arial size=2>
$name
</td></tr>
<tr><td width=30%>
<font face=arial size=2>
<b>E-mail Address:</b>
</td><td width=70%>
<font face=arial size=2>
$email
</td></tr>
<tr><td width=30%>
<font face=arial size=2>
<b>Subject:</b>
</td><td width=70%>
<font face=arial size=2>
$subject
</td></tr>
</table>
</center>

<br>
<br>
<table width=87%>
<tr><td>
<font face=arial size=2>
<b><U><font color=navy>Message You Posted</font></U></b><br>
<br>
$body
<br>
<br>
</td></tr>
</table>

</td></tr>
</table>

<hr size=1 width=75% color=navy>
<br>
<br>
<br>
<br>
<br>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
EOT

open (MAIL,"|/usr/lib/sendmail $FORM{'email'}") || die print "Content-type: text/html\n\nError!";
                print MAIL <<EOT;
From: "WWWBoard Administrator <$forumemail>"
Reply-to: "$name <$email>"
Subject: $subject

Hello $name,

Thank you for posting your message in the 
my WWWBoard.  I will be responding to your
message soon.  

Best Regards,

WWWBoard Administrator
EOT
                close(MAIL);
}

sub increment_num {
   open(NUM,">$basedir/$datafile") || die $!;
   print NUM "$num";
   close(NUM);
}

sub error {
   $error = $_[0];

   print "Content-type: text/html\n\n";

   if ($error eq 'no_name') {
      print <<EOT;
<html>
<head>
<title>WWWBoard: Add Message: Error-No Name</title>
</head>
<BODY BGCOLOR="White" TEXT="Black" LINK="Blue" VLINK="Navy" ALINK="Maroon">
<a name="top">
<center>
<font size="5" face="arial,helvetica"><br>
<font color=maroon><b>WWWBoard New Message Error</b></font><br>
</center>
		
<br>
<br>

<form method=POST action="submit.cgi">

<center>
<table width=600>
<tr><td width=600>

<font face=arial size=2>
<B>You forgot to enter your name in your message.</B> Please fill out the form below and remember to include your name.  Otherwise, your message cannot be posted.<br>
<br>
<br>
<hr size=1>
<br>
<br>

<table width=100%>
<tr><td width=20%>
<font face=arial size=2>
<b>Name:</b></font>
</td><td width=80%>
<input type=text name="name" size=50 value="$name">
</td></tr>
<tr><td width=20%>
<font face=arial size=2>
<b>E-Mail:</b></font>
</td><td width=80%>
<input type=text name="email" size=50 value="$email">
</td></tr>
<tr><td width=20%>
<font face=arial size=2>
<b>Subject:</b></font>
</td><td width=80%>
<input type=text name="subject" size=50 value="$subject">
</td></tr>
</table>
<br>
<font face=arial size=2>
<b><U><font color=navy>Message to Post</font></U></b><br>
<br>
<textarea COLS=55 ROWS=10 name="body"></textarea>
<br>
<br>
<table width=100%>
<tr><td width=100%>
<br>
<center>
<input type=submit value="Post Message"> &nbsp; <input type=reset value="Reset Form">
</form>
</td></tr>
</table>

</td></tr>
</table>       

<br>
<br>
<br>
<br>
<br>

</td></tr>
</table>

</td></tr>
</table>

</body>
</html>

EOT

   }

   elsif ($error eq 'no_subject') {
      print <<EOT;

<html>
<head>
<title>WWWBoard: Add Message: Error-No Subject</title>
</head>
<BODY BGCOLOR="White" TEXT="Black" LINK="Blue" VLINK="Navy" ALINK="Maroon">
<a name="top">
<center>
<font size="5" face="arial,helvetica"><br>
<font color=maroon><b>WWWBoard New Message Error</b></font><br>
</center>
		
<br>
<br>

<form method=POST action="submit.cgi">

<center>
<table width=600>
<tr><td width=600>

<font face=arial size=2>
<B>You forgot to enter a subject for your message.</B> Please fill out the form below and remember to include a subject.  Otherwise, your message cannot be posted.<br>
<br>
<br>
<hr size=1>
<br>
<br>

<table width=100%>
<tr><td width=20%>
<font face=arial size=2>
<b>Name:</b></font>
</td><td width=80%>
<input type=text name="name" size=50 value="$name">
</td></tr>
<tr><td width=20%>
<font face=arial size=2>
<b>E-Mail:</b></font>
</td><td width=80%>
<input type=text name="email" size=50 value="$email">
</td></tr>
<tr><td width=20%>
<font face=arial size=2>
<b>Subject:</b></font>
</td><td width=80%>
<input type=text name="subject" size=50 value="$subject">
</td></tr>
</table>
<br>
<font face=arial size=2>
<b><U><font color=navy>Message to Post</font></U></b><br>
<br>
<textarea COLS=55 ROWS=10 name="body" value="$body"></textarea>
<br>
<br>
<table width=100%>
<tr><td width=100%>
<br>
<center>
<input type=submit value="Post Message"> &nbsp; <input type=reset value="Reset Form">
</form>
</td></tr>
</table>

</td></tr>
</table>       

<br>
<br>
<br>
<br>
<br>

</td></tr>
</table>

</td></tr>
</table>

</body>
</html>

EOT

   }
   elsif ($error eq 'no_body') {
      print <<EOT;

<html>
<head>
<title>WWWBoard: Add Message: Error-No Message</title>
</head>
<BODY BGCOLOR="White" TEXT="Black" LINK="Blue" VLINK="Navy" ALINK="Maroon">
<a name="top">
<center>
<font size="5" face="arial,helvetica"><br>
<font color=maroon><b>WWWBoard New Message Error</b></font><br>
</center>
		
<br>
<br>

<form method=POST action="submit.cgi">

<center>
<table width=600>
<tr><td width=600>

<font face=arial size=2>
<B>You forgot to enter a message.</B> Please fill out the form below and remember to include a message to post.  Otherwise, your message cannot be posted.<br>
<br>
<br>
<hr size=1>
<br>
<br>

<table width=100%>
<tr><td width=20%>
<font face=arial size=2>
<b>Name:</b></font>
</td><td width=80%>
<input type=text name="name" size=50 value="$name">
</td></tr>
<tr><td width=20%>
<font face=arial size=2>
<b>E-Mail:</b></font>
</td><td width=80%>
<input type=text name="email" size=50 value="$email">
</td></tr>
<tr><td width=20%>
<font face=arial size=2>
<b>Subject:</b></font>
</td><td width=80%>
<input type=text name="subject" size=50 value="$subject">
</td></tr>
</table>
<br>
<font face=arial size=2>
<b><U><font color=navy>Message to Post</font></U></b><br>
<br>
<textarea COLS=55 ROWS=10 name="body" value="$body"></textarea>
<br>
<br>
<table width=100%>
<tr><td width=100%>
<br>
<center>
<input type=submit value="Post Message"> &nbsp; <input type=reset value="Reset Form">
</form>
</td></tr>
</table>

</td></tr>
</table>       

<br>
<br>
<br>
<br>
<br>

</td></tr>
</table>

</td></tr>
</table>

</body>
</html>
EOT
   }
   else {
      print "ERROR!  Undefined.\n";
   }
   exit;
}


sub SendAdminEmail {
	if ($subject =~ /^Re:/) { }else{
		open (MAIL,"|/usr/lib/sendmail $mailto") || die print "Content-type: text/html\n\nError!";
		print MAIL <<EOT;
From: "Your WWWBoard"
Reply-to: "WWWBoard"
Subject: New Message in WWWBoard
Content-type: text/html   

<html>
<br>
<font face=arial size=2>
<center>
<B><U><font color=maroon font size=4>You have a New Message Posted in the $forumname</font></U></B><br>
</center>
<br>
<br>
<b><font color=green>Message URL:</b></font> &nbsp;<a href="$baseurl\/$mesgdir/$num\.$ext">$baseurl\/$mesgdir/$num\.$ext</a><br>
<br>
<hr size=1 width=100%>
<br>
<br>
<b><font color=navy size=3>
<U>Below is the Message Posted</U><br>
</b></font>
<br>
<table width=60% cellpadding=5>
<tr><td width=10% valign=top>
<font color=maroon size=2 face=arial><B>From:</B></font>
</td><td width=50% valign=top>
<font face=arial size=2><a href="mailto:$email">$name</a></font>
</td></tr>
<tr><td width=10% valign=top>
<font color=maroon size=2 face=arial><B>Subject:</B></font><br>
<br>
</td><td width=50% valign=top>
<font face=arial size=2>$subject</font><br>
<br>
</td></tr>
<tr><td colspan=2 bgcolor=#E6E6E6>
<font face=arial size=2>$body</font>
</td></tr>
</table>
<br>
<form method="POST" action="$baseurl/admin.cgi">
<input type=hidden name="action" value="remove">
<input type=hidden name="username" value="$mailto">
<input type=hidden name="password" value="wwwboard">
<input type=hidden name="min" value="1">
<input type=hidden name="max" value="$num">
<input type=hidden name="type" value="remove">
<input type=hidden name="$num" value="single" checked>
<font color=green size=2><b>Delete this message from the WWWBoard:</font> &nbsp;<input type=submit value="Yes">
</form>
<br>
<br>
<br>
<hr size=1 width=100%>
<br>
<br>
<B><font face=arial size=3 color=navy><U>Reply to the Message in the Textbox Below</U></font></B><br>
<form method="POST" action="$cgi_url">
<input type=hidden name="followup" value="$num">
<input type=hidden name="origname" value="$name">
<input type=hidden name="origemail" value="$email">
<input type=hidden name="origsubject" value="$subject">
<input type=hidden name="origdate" value="$long_date">
<input type=hidden name="name" size=50 value="WWWBoard Administrator">
<input type=hidden name="email" size=50 value="$forumemail">
<input type=hidden name="subject" size=50 value="Re: $subject">
<textarea COLS=55 ROWS=15 name="body">
On $long_date, $name submitted the following Message:

$body


The WWWBoard Administrator responded with the following message.


</textarea>
<br>
<br>
<br>
<table width=60%>
<tr><td>
<center><input type=submit value="Submit Response"> &nbsp; <input type=reset value="Reset Response">
</td></tr>
</table>
<br>
<br>
</html>
EOT
	close (MAIL);
	}
}

sub SendOrigEmail {
	if ($subject =~ /^Re:/ && $FORM{'origemail'} ne "") {
		open (MAIL,"|/usr/lib/sendmail $FORM{'origemail'}") || die print "Content-type: text/html\n\nbryan sucks 2";
		print MAIL <<EOT;
From: "WWWBoard Administrator <$forumemail>"
Reply-to: "$origname <$origemail>"
Subject: Re: $origsubject

Hello $origname,

The message you posted in my WWWBoard
on $origdate has a reply which can 
be viewed at the URL below.

$baseurl\/$mesgdir/$num\.$ext.


Best Regards,

WWWBoard Administrator
EOT
		close(MAIL);
	}
}




