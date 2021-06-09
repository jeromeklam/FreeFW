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
import { causeTypeAsOptions, causeTypeFind } from '../[[:FEATURE_SERVICE:]]-type/functions.js';
import { subspeciesAsOptions } from '../subspecies';
import { InputPicker as ClientInputPicker } from '../client';
import { InputPicker as SiteInputPicker } from '../site';
import { InputPicker as CauseInputPicker } from './';
import { InlineSponsorships } from '../sponsorship';
import { InlineDonations } from '../donation';
import { InlinePhotos, InlineSponsors, InlineNews } from './';

const afterChange = (name, item) => {
  switch (name) {
    case 'cau_name':
      item.cau_code = item.cau_name;
      break;
    case 'cau_to':
      if (item.cau_to) {
        item.cau_public = false;
        item.cau_available = false;
      }
      break;
    case 'cause_type.id':
      if (item.cause_type && item.cause_type.id && item.__cause_types) {
        item.cause_type = causeTypeFind(item.__cause_types, item.cause_type.id);
      }
      if (item.cause_type) {
        if (item.cause_type.caut_mnt_type === 'ANNUAL') {
          item.cau_mnt_left = item.cause_type.caut_max_mnt;
        }  
      }
      break;
    default:
      break;
  }
  return item;
};

function Form(props) {
  const nYear = new Date().getFullYear();
  let item = props.item;
  item.cau_code = item.cau_name;
  item.__cause_types = props.cause_types;
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
  const tabs = [
    {
      key: '1',
      name: 'identification',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].form.main',
        defaultMessage: 'Mission',
      }),
      shortcut: 'A',
      icon: 'cause',
    },
    {
      key: '2',
      name: 'divers',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].form.more',
        defaultMessage: 'Complement',
      }),
      shortcut: 'D',
      icon: 'misc',
    },
  ];
  const modifTabs = [
    {
      key: '3',
      name: 'sponsorship',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].tab.sponsorships',
        defaultMessage: 'Sponsorships',
      }),
      shortcut: 'I',
      icon: 'client',
    },
    {
      key: '4',
      name: 'donation',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].tab.donations',
        defaultMessage: 'Donations',
      }),
      shortcut: 'C',
      icon: 'misc',
    },
    {
      key: '5',
      name: 'picture',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].tab.pictures',
        defaultMessage: 'Pictures',
      }),
      shortcut: 'C',
      icon: 'misc',
    },
    {
      key: '6',
      name: 'sponsor',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].tab.sponsors',
        defaultMessage: 'Sponsors',
      }),
      shortcut: 'C',
      icon: 'misc',
    },
    {
      key: '7',
      name: 'news',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].tab.news',
        defaultMessage: 'Nouvelles',
      }),
      shortcut: 'N',
      icon: 'news',
    },
  ];
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
      {values.__currentTab === '1' && (
        <div>
          <Row>
            <Col size={{ xs: 36, sm: 7 }}>
              <InputSelect
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.causeType',
                  defaultMessage: 'Mission',
                })}
                name="cause_type.id"
                labelTop={true}
                value={values.cause_type ? values.cause_type.id : null}
                addempty={true}
                onChange={handleChange}
                options={causeTypeAsOptions(props.cause_types)}
              />
            </Col>
            <Col size={{ xs: 36, sm: 7 }}>
              <InputText
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.name',
                  defaultMessage: 'Name',
                })}
                name="cau_name"
                id="cau_name"
                labelTop={true}
                required={true}
                value={values.cau_name}
                onChange={handleChange}
                error={getErrorMessage('cau_name')}
              />
            </Col>
            {values.cause_type && values.cause_type.caut_family === 'ANIMAL' && (
              <Col size={{ xs: 36, sm: 10 }}>
                <InputSelect
                  label={props.intl.formatMessage({
                    id: 'app.features.[[:FEATURE_LOWER:]].form.subspecies',
                    defaultMessage: 'Subspecies',
                  })}
                  name="subspecies.id"
                  labelTop={true}
                  value={values.subspecies ? values.subspecies.id : null}
                  addempty={true}
                  onChange={handleChange}
                  options={subspeciesAsOptions(props.subspecies)}
                />
              </Col>
            )}
            <Col size={{ xs: 36, sm: 12 }}>
              <SiteInputPicker
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.site',
                  defaultMessage: 'Location',
                })}
                labelTop={true}
                key="site"
                name="site"
                item={values.site || null}
                onChange={handleChange}
              />
            </Col>
          </Row>
          <Row>
            <Col size={{ xs: 36, sm: 6 }}>
              {values.cause_type && values.cause_type.caut_family === 'ANIMAL' && (
                <InputSelect
                  label={props.intl.formatMessage({
                    id: 'app.features.[[:FEATURE_LOWER:]].form.sex',
                    defaultMessage: 'Sex',
                  })}
                  labelTop={true}
                  name="cau_sex"
                  id="cau_sex"
                  value={values.cau_sex}
                  onChange={handleChange}
                  options={[
                    { label: 'Male', value: 'M' },
                    { label: 'Femelle', value: 'F' },
                  ]}
                />
              )}
            </Col>
            <Col size={{ xs: 36, sm: 6 }}>
              {values.cause_type && values.cause_type.caut_family === 'ANIMAL' && (
                <InputSpin
                  label={props.intl.formatMessage({
                    id: 'app.features.[[:FEATURE_LOWER:]].form.cauYear',
                    defaultMessage: 'Born in',
                  })}
                  name="cau_year"
                  id="cau_year"
                  maxValue={nYear}
                  minValue={1990}
                  labelTop={true}
                  value={values.cau_year}
                  onChange={handleChange}
                />
              )}
            </Col>
            <Col size={{ xs: 0, sm: 2 }} />
            <Col size={{ xs: 36, sm: 7 }}>
              <InputCheckbox
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.public',
                  defaultMessage: 'Show on site',
                })}
                name="cau_public"
                labelTop={true}
                checked={values.cau_public === true}
                onChange={handleChange}
              />
            </Col>
            <Col size={{ xs: 36, sm: 7 }}>
              <InputCheckbox
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.available',
                  defaultMessage: 'Sponsorship',
                })}
                name="cau_available"
                labelTop={true}
                checked={values.cau_available === true}
                onChange={handleChange}
              />
            </Col>
            <Col size={{ xs: 0, sm: 1 }} />
            <Col size={{ xs: 36, sm: 7 }}>
              <InputMonetary
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.mnt',
                  defaultMessage: 'Raised',
                })}
                labelTop={true}
                name="cau_mnt"
                id="cau_mnt"
                inputMoney="EUR"
                dbMoney="EUR"
                value={values.cau_mnt}
                disabled={true}
              />
            </Col>
          </Row>
          <Row>
            <Col size={{ xs: 36, sm: 9 }}>
              <InputDate
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.from',
                  defaultMessage: 'From',
                })}
                labelTop={true}
                name="cau_from"
                id="cau_from"
                value={values.cau_from}
                onChange={handleChange}
              />
            </Col>
            <Col size={{ xs: 36, sm: 9 }}>
              <InputDate
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.to',
                  defaultMessage: 'End',
                })}
                labelTop={true}
                name="cau_to"
                id="cau_to"
                value={values.cau_to}
                onChange={handleChange}
              />
            </Col>
            <Col size={{ xs: 36, sm: 6 }}>
              {values.cau_end && (
                <InputData
                  key="cau_string_3"
                  name="cau_string_3"
                  labelTop={true}
                  value={values.cau_string_3}
                  datas={props.tab_datas}
                  config={props.tab_configs}
                  onChange={handleChange}
                />
              )}
            </Col>
            <Col size={{ xs: 36, sm: 5 }} />
            <Col size={{ xs: 36, sm: 7 }}>
              <InputMonetary
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.left',
                  defaultMessage: 'Left',
                })}
                labelTop={true}
                name="cau_mnt_left"
                id="cau_mnt_left"
                inputMoney="EUR"
                dbMoney="EUR"
                value={values.cau_mnt_left}
                disabled={true}
              />
            </Col>
          </Row>
          {values.cause_type && values.cause_type.caut_family === 'ANIMAL' && (
            <Row>
              <Col size={{ xs: 36, sm: 18 }}>
                <CauseInputPicker
                  label={props.intl.formatMessage({
                    id: 'app.features.[[:FEATURE_LOWER:]].form.parent1',
                    defaultMessage: 'Father',
                  })}
                  labelTop={true}
                  key="parent1"
                  name="parent1"
                  item={values.parent1 || null}
                  onChange={handleChange}
                />
              </Col>
              <Col size={{ xs: 36, sm: 18 }}>
                <CauseInputPicker
                  label={props.intl.formatMessage({
                    id: 'app.features.[[:FEATURE_LOWER:]].form.parent2',
                    defaultMessage: 'Mother',
                  })}
                  labelTop={true}
                  key="parent2"
                  name="parent2"
                  item={values.parent2 || null}
                  onChange={handleChange}
                />
              </Col>
            </Row>
          )}
          {values.cause_type.caut_certificat && (
            <Row>
              <Col size={{ xs: 36, sm: 12 }}>
                <InputMonetary
                  label={props.intl.formatMessage({
                    id: 'app.features.[[:FEATURE_LOWER:]].form.unitBase',
                    defaultMessage: 'Certificate base quantity',
                  })}
                  labelTop={true}
                  name="cau_unit_base"
                  id="cau_unit_base"
                  inputMoney="EUR"
                  dbMoney="EUR"
                  value={values.cau_unit_base}
                  onChange={handleChange}
                />
              </Col>
              <Col size={{ xs: 36, sm: 12 }}>
                <InputText
                  label={props.intl.formatMessage({
                    id: 'app.features.[[:FEATURE_LOWER:]].form.unitUnit',
                    defaultMessage: 'Certificate base unit',
                  })}
                  name="cau_unit_unit"
                  id="cau_unit_unit"
                  labelTop={true}
                  required={true}
                  value={values.cau_unit_unit}
                  onChange={handleChange}
                  error={getErrorMessage('cau_unit_unit')}
                />
              </Col>
              <Col size={{ xs: 36, sm: 12 }}>
                <InputMonetary
                  label={props.intl.formatMessage({
                    id: 'app.features.[[:FEATURE_LOWER:]].form.unitMnt',
                    defaultMessage: 'Certificate base amount',
                  })}
                  labelTop={true}
                  name="cau_unit_mnt"
                  id="cau_unit_mnt"
                  inputMoney="EUR"
                  dbMoney="EUR"
                  value={values.cau_unit_mnt}
                  onChange={handleChange}
                />
              </Col>
            </Row>
          )}
        </div>
      )}
      {values.__currentTab === '2' && (
        <div>
          {values.cause_type && values.cause_type.caut_family === 'ANIMAL' && (
            <Row>
              <Col size={{ xs: 36, sm: 12 }}>
                <ClientInputPicker
                  label={props.intl.formatMessage({
                    id: 'app.features.[[:FEATURE_LOWER:]].form.proprietary',
                    defaultMessage: 'Sanitary',
                  })}
                  key="proprietary"
                  name="proprietary"
                  labelTop={true}
                  item={values.proprietary || null}
                  onChange={handleChange}
                />
              </Col>
            </Row>
          )}
          <Row>
            <Col size={{ xs: 36 }}>
              <InputTextarea
                label={props.intl.formatMessage({
                  id: 'app.features.[[:FEATURE_LOWER:]].form.desc',
                  defaultMessage: 'Description',
                })}
                labelTop={true}
                name="cau_desc"
                value={values.cau_desc}
                onChange={handleChange}
              />
            </Col>
          </Row>
        </div>
      )}
      {values.__currentTab === '3' && (
        <div className="border border-secondary rounded overflow-x-hidden">
          <InlineSponsorships mode="cause" id={values.id} />
        </div>
      )}
      {values.__currentTab === '4' && (
        <div className="border border-secondary rounded overflow-x-hidden">
          <InlineDonations mode="cause" id={values.id} />
        </div>
      )}
      {values.__currentTab === '5' && (
        <div className="border border-secondary rounded overflow-x-hidden">
          <InlinePhotos cauId={values.id} />
        </div>
      )}
      {values.__currentTab === '6' && (
        <div className="border border-secondary rounded overflow-x-hidden">
          <InlineSponsors cauId={values.id} />
        </div>
      )}
      {values.__currentTab === '7' && (
        <div className="border border-secondary rounded overflow-x-hidden">
          <InlineNews cauId={values.id} />
        </div>
      )}
    </ResponsiveModalOrForm>
  );
}

export default injectIntl(Form);
