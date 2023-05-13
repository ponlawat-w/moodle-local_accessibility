import { call } from 'core/ajax';

export const saveOptionConfig = (
    optionname,
    configvalue
) => call([{
    methodname: 'local_accessibility_saveoptionconfig',
    args: { optionname, configvalue }
}])[0];
