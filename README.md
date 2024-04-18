# Accessibility #

Accessibility plugin

This plugin requires subplugins (widgets) to be installed separatedly inside directory `widgets` of this plugin.
After installing widget plugins, site administrator needs to enable widgets by going to _Site Administration > Plugins > Accessibility Widgets > Manage Enabled Widgets_.

[Instructions to develop a widget plugin](./widgets/README.md)

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing via git
From moodle root 

git  clone git@github.com:ponlawat-w/moodle-local_accessibility.git  local/accessibility

`cd local/accessibility`

At this point the plugin will be installed but without any widgets. To add the widgets you can check out by tag as follows.
Tags can be found at https://github.com/ponlawat-w/moodle-local_accessibility/tags. Select the one you want and checkout as follows

`git checkout v1.0.1-with-widgets`

This will result in a warning about being in 'detached HEAD' state that will not affect testing

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/accessibility

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2023 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
