EXAMPLE - CLAN IDENTIFIER
==========================

In this example, it is demonstrated how to use this extension to add a "CLAN" to your profile, and show it in all relevant places.

I have chosen the "Game of Thrones House" theme as the Clan.  There are some images that represent these different Clans,
stored in an images folder.

These images are presented in all possible places where Profile Fields are shown: in the Member Profile, in the Memberlist, in
the Viewtopic mini profile, and in the Private Message mini profile.

Given that these elements are not positioned in standard event places, each one of them uses a small JavaScript to reposition it,
and a few embedded CSS attributes to give them the right appearance.

Of course, you will need to adapt all these (CSS, JS and HTML in events) if you want to reuse it.

To use this example in your forum, simply copy all the contents of this folder to the root of the extension folder, disable and
re-enable the extension (to execute the migration file that installs the profile field), and then configure a few profiles
configuring the "GoT House" to test behavior.

To remove it, if you do not want to continue to use it, you will have to remove the created Profile Field ("jx_got_house"), 
and remove the files that you have copied.

