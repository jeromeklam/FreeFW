import React from 'react';
import { injectIntl } from 'react-intl';
import {
  InputCheckbox,
  InputHidden,
  InputSelect,
  InputText,
  InputMonetary,
  Row,
  Col,
} from 'react-bootstrap-front';
import { InputDate, InputData, ResponsiveModalOrForm, InputTextarea, InputSpin } from '../ui';
import useForm from '../ui/useForm';

const afterChange = (name, item) => {
  switch (name) {
    default:
      break;
  }
  return item;
};

function Form(props) {
  let item = props.item;
  const {
    values,
    handleChange,
    handleSave,
    handleSubmit,
    handleCancel,
    handleNavTab,
    getErrorMessage,
  } = useForm(
    item,
    props.tab,
    props.onSubmit,
    props.onCancel,
    props.onNavTab,
    props.errors,
    props.intl,
    afterChange,
  );
  const tabs = [];
  const modifTabs = [];
  return (
    <ResponsiveModalOrForm
      title={values.cau_name ? values.cau_name : ''}
      className="m-5"
      tab={values.__currentTab}
      tabs={!props.modify ? tabs : tabs.concat(modifTabs)}
      size="xl"
      onSubmit={handleSubmit}
      onCancel={handleCancel}
      onNavTab={handleNavTab}
      onSave={handleSave}
      onClose={props.onClose}
      modal={true}
      actionsButtons={props.actionsButtons}
    >
      <InputHidden name="id" id="id" value={values.id} />
    </ResponsiveModalOrForm>
  );
}

export default injectIntl(Form);
