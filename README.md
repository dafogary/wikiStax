mwadmin
==================
Please Note: This tool is currently in early development and is not recommend for use yet.

This is a admin management tool for a MediaWiki farm instanace.

Version: 0.5-Beta

## Requirements


This requires a MediaWiki farm setup, this is tested using mediawiki 1.39 but other versions should work.

The MariaDB/MySQL root requires remote connections. This doesn't currently work, see **Known bugs** for details.

## Install

Install instructions - coming soon.

## Known bugs

When importing the vanilla.sql file, the root user has issues connecting to the database. The database should ideally allow remote access to the root user.

## Future development

The aim of this tool is to have a simpler way to install in the future rather than having to import the databse and manually update the config.php file.

We are also looking at an automated section that will allow you to automate the boring stuff.

# Contact

If you want to assist in the project please take a fork, or if you would like to work with us, [please contact us via our website](https://dafocreative.com).

&copy; 2021 - 2023 [DAFO Creative LLC](https:/dafocreative.com).
