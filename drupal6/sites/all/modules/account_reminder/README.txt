// $Id: README.txt,v 1.2.2.2 2009/07/28 06:48:53 jaydub Exp $

Module description
==================

Account Reminder will resend the Drupal new user welcome email to those 
users who have registered but who have not yet logged into your site 
for the first time. The admin can control the initial time before 
sending a reminder, the time period between subsequent reminders and the 
total number of reminders to send. The email that is sent is also 
fully customizable.

Installation and Configuration
==============================

1) Copy the the Account Reminder module files to your Drupal modules
   directory (e.g. /sites/all/modules)

2) Enable the Account Reminder module on the Drupal modules page
   at admin/build/modules.

3) Grant the 'administer account reminder' permission to the roles
   desired on the Drupal user permissions page /admin/user/permissions.

4) Once the Account Reminder module has been installed you can configure
   the settings at admin/settings/account_reminder.

  a) Days till initial reminder - The number of days to wait before sending
     the first reminder email.

  b) Days between reminders - The number of days to wait before sending
     additional reminder emails.

  c) Total number of reminders - The number of reminders to send in total.

  d) BCC Email address - If you wish to set a BCC for reminder emails, set it here.

  e) Email subject and email body - Enter the text of the reminder email you wish
     to send out.

Credits
=======

Module developed by Pip the Hobbit and Mike Dixon, of http://www.computerminds.co.uk 
initially for the site http://www.booktribes.com

Drupal 5 upgrade work by maartenvg

The module was updated for better MySQL and PostgreSQL compatibility
and ported to Drupal 6 by Jeff Warrington http://drupal.org/user/46257
