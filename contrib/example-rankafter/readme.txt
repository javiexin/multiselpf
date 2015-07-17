EXAMPLE - EXTRA RANK
=====================

In this example, it is demonstrated how to use this extension to add a second (or third) rank to your profile, and show it
in most relevant places.

This is intended to be positioned AFTER the standard rank, and it loads any of the images in the images/ranks folder
as additional ranks.  I have tested this with the "Star Ranks 2.0.3" rank set downloaded from here:
https://www.phpbb.com/customise/db/style/phpbb3_star_ranks/
The reason is that it has multiple rank images, both for regular ranks, administrative ranks and special ranks.

These images are presented in most possible places where Profile Fields are shown: in the Member Profile, in the Memberlist,
and in the Viewtopic mini profile.  They are NOT shown in the Private Message mini profile.

These elements are positioned in new standard event places, that correspond to new events that have been requested for 3.1.6,
and that are included in the changes performed to test this extension.  No CSS and no JavaScript is used here.  You may need
to adapt this to fit your needs.

To use this example in your forum, simply copy all the contents of this folder to the root of the extension folder, disable and
re-enable the extension (to execute the migration file that installs the profile field), and then configure a few profiles
with the new additional rank.

To remove it, if you do not want to continue to use it, you will have to remove the created Profile Field ("jx_rank_admin"), 
and remove the files that you have copied.
