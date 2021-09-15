import React from 'react';
import { injectIntl } from 'react-intl';
import { getShortcuts } from './';
import useForm from '../ui/useForm';
import {
  ResponsiveModalOrForm,
  InputHidden,
  InputText,
  InputCheckbox,
  InputDate,
  InputDatetime,
  InputPhone,
  Row,
  Col,
} from '../ui';
import { Home as [[:FEATURE_CAMEL_FULL:]]Icon } from '../icons';

/**
 * Initialisation
 * 
 * @param {Object}   item
 * @param {Function} setItem
 */
const initItem = (item, setItem) => {
};

/**
 * Un champ a été modifié
 * 
 * @param {String}   field
 * @param {Object}   item
 * @param {Function} setitem
 */
const afterChange = (field, item, setItem) => {
  switch (field) {
    default:
      break;
  }
};

/**
 * Saisie des informations d'un modèle
 */
function Form(props) {
  let item = props.item;
  // On récupère tout ce qu'il faut pour travailler
  const {
    values,
    handleChange,
    handleSave,
    handleSubmit,
    handleCancel,
    handleNavTab,
    getErrorMessage,
    getCurrentTab,
  } = useForm(
    item,
    props.tab,
    props.onSubmit,
    props.onCancel,
    props.onNavTab,
    props.errors,
    props.intl,
    afterChange,
    initItem,
  );
  // Le formulaire
  return (
    <ResponsiveModalOrForm
      title={
        !props.modify && !values.[[:FEATURE_MAINCOL:]]
          ? props.intl.formatMessage({
              id: 'app.features.[[:FEATURE_LOWER:]].form.title',
              defaultMessage: 'New model',
            })
          : values.[[:FEATURE_MAINCOL:]]
      }
      className=""
      tab={getCurrentTab()}
      tabs={!props.modify ? getShortcuts(props.intl, 'new') : getShortcuts(props.intl, 'all')}
      size="md"
      onSubmit={handleSubmit}
      onCancel={handleCancel}
      onNavTab={handleNavTab}
      onSave={handleSave}
      onClose={handleCancel}
      onPrevious={props.onPrevious}
      onNext={props.onNext}
      modal={true}
      actionsButtons={props.actionsButtons}
      loader={props.loader ? props.loader : false}
    >
      <InputHidden name="id" id="id" value={values.id} />
      [[:FEATURE_FIELDS:]]
    </ResponsiveModalOrForm>
  );
}

/**
 * Injection de l'intl
 */
export default injectIntl(Form);
