import { call } from 'core/ajax';

export const saveWidgetConfig = (
    widget,
    configvalue
) => call([{
    methodname: 'local_accessibility_savewidgetconfig',
    args: { widget, configvalue }
}])[0];
