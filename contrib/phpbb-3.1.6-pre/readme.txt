MODIFICATIONS REQUIRED TO 3.1.5
================================

These are the modifications needed to the phpbb 3.1.5 core so that Profile Fields work correctly, and allow for this extension to work.
IF THIS IS NOT INSTALLED, THE EXTENSION WILL NOT WORK.

The list of modifications included here is the following:

[*][url=https://github.com/phpbb/phpbb/pull/3717][ticket/13853] Flexible schema for profilefields step 1 configuration[/url]
[*][url=https://github.com/phpbb/phpbb/pull/3656][ticket/13867] Enable/disable mechanism for new profile field types[/url]
[*][url=https://github.com/phpbb/phpbb/pull/3719][ticket/13911] Add events to configure options for profile fields[/url]
[*][url=https://github.com/phpbb/phpbb/pull/3733][ticket/13934] Add enctype clause for profile fields[/url]
[*][url=https://github.com/phpbb/phpbb/pull/3724][ticket/13960] Profile field validation breaks ACP[/url]
[*][url=https://github.com/phpbb/phpbb/pull/3739][ticket/13982] Add events around ranks[/url]

To install, copy the CONTENTS of the "modified" folder to the root of your forum, overwritting the existing files.
For your convenience, I have included here a fresh copy of the standard 3.1.5 files in the "phpbb-3.1.5" folder.
To uninstall, simply copy the contents of this folder to the root of your forum, and this will revert the phpbb
files back to the standard 3.1.5.

