# Accessibility Widgets

Accessibility widgets (subplugins) need to be placed in this directory (`{path/to/moodle}/local/accessibility/widgets/`).

## Widget Development Instructions

It is possible to develop a custom accessibility widget as a subplugin.
Here is the basic information for developing widget plugins:
- Plugin type name: `accessibility`
- Plugin directory: `/local/accessibility/widgets/`

There are some examples from default widget plugin can be found on GitHub:
- [moodle-accessibility_fontsize](https://github.com/ponlawat-w/moodle-accessibility_fontsize)
- [moodle-accessibility_fontface](https://github.com/ponlawat-w/moodle-accessibility_fontface)
- [moodle-accessibility_textcolour](https://github.com/ponlawat-w/moodle-accessibility_textcolour)
- [moodle-accessibility_backgroundcolour](https://github.com/ponlawat-w/moodle-accessibility_backgroundcolour)

### Main Class

Each widget plugin needs to have `accessibility_{widgetname}.php` class file placed in widget directory.
The class extends [`\local_accessibility\widgets\widgetbase`](../classes/widgetbase.php) abstract class.
The following abstract methods need to be implmented:
- `getcontent()` which returns HTML content to display in accessibility panel.

### Configuration Value Storage

If the widget is customisable its value by user, it can call `setuserconfig` and `getuserconfig` method from parent class. These methods support saving data for both logged-in users and guest users.
If the interaction of users is through javascript, to save configuration, it can call [`local_accessibility_savewidgetconfig`](../classes/external/savewidgetconfig.php) API or import `saveWidgetConfig` javascript function from [`local/accessibility/common`](../amd/src/common.js) module.

### Presets

There are a number of common widget presets that a new widget can also derive from without writing from scratch.

- `rangewidget`: widgets that have slider and store numeric values, such as [`fontsize`](https://github.com/ponlawat-w/moodle-accessibility_fontsize), can be created by extending [`\local_accessibility\widgets\rangewidget`](../classes/rangewidget.php) abstract class.
- `colourwidget`: widgets that store values in hex colour and have colourpicker as display, such as [`textcolour`](https://github.com/ponlawat-w/moodle-accessibility_textcolour) and [`backgroundcolour`](https://github.com/ponlawat-w/moodle-accessibility_backgroundcolour), can be created by extending [`\local_accessibility\widgets\colourwidget`](../classes/colourwidget.php) abstract class.
