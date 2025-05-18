![WikiStax Logo](/src/WikiStaxDarkLogo.png "WikiStax Logo" style="width:300px;")
==================
Please Note: This tool is currently in early development and is not recommend for use yet.

This is a admin management tool for a MediaWiki farm instanace.

Version: 0.7.1-Alpha

## Requirements

This requires a MediaWiki farm setup, this is tested using mediawiki 1.39 but other versions should work.

The MariaDB/MySQL root requires remote connections. This doesn't currently work, see **Known bugs** for details.

The www-data user (or whichever your web browser runs as) permission to execute sudo commands.

To edit the susodoers file run:
  
  sudo visudo

Add the line:

  # Allow user www-data to execute sudo command crontab
  www-data ALL=(ALL) NOPASSWD: /usr/bin/crontab


## Install

Install instructions - coming soon.

## Known bugs

 The current known issues are:

- when importing the VanillaDB.sql file, the root user has issues connecting to the database. The database should ideally allow remote access to the root user.
- the common LocalSettings.php is not being populated with the Wiki field configuration.

The remove Wiki and PDF Archive need extra work.

## Future development

The aim of this tool is to have a simpler way to install in the future rather than having to import the databse and manually update the config.php file.

We are also looking at an automated section that will allow you to automate the boring stuff.

If you want to assist in the project please take a fork, or if you would like to work with us, [please contact us via our website](https://dafocreative.com).

![DAFO Creative Logo](/src/DAFO_logo.png "DAFO Creative Logo" style="width:300px;")

&copy; 2021 - <script>document.write(new Date().getFullYear());</script> [DAFO Creative LLC](https:/dafocreative.com).