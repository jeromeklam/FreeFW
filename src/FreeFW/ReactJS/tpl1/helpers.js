import React from 'react';
import {
  AddOne as AddOneIcon,
  GetOne as GetOneIcon,
  DelOne as DelOneIcon,
  Print as PrintIcon,
} from '../icons';

/**
 *
 */
export const getGlobalActions = ({ props, onClearFilters, onCreate }) => {
  return [
    {
      name: 'create',
      label: props.intl.formatMessage({
        id: 'app.list.button.add',
        defaultMessage: 'Add',
      }),
      onClick: onCreate,
      theme: 'primary',
      icon: <AddOneIcon color="white" />,
      role: 'CREATE',
    },
  ];
};

/**
 *
 */
export const getActionsButtons = ({ onPrint, state, props }) => {
  if (state.id > 0 && props.editions && props.editions.length > 0) {
    let ediId =  state.item.cause_type.identity_edition ? state.item.cause_type.identity_edition.id : null;
    return [
      {
        theme: 'secondary',
        hidden: false,
        function: () => onPrint(ediId),
        icon: <PrintIcon title="Imprimer" />,
      },
    ];
  }
  return [];
};

/**
 *
 */
export const getSelectActions = ({ props, onSelectMenu }) => {
  let arrOne = [
    {
      name: 'selectAll',
      label: props.intl.formatMessage({
        id: 'app.list.options.selectAll',
        defaultMessage: 'Select all',
      }),
      onClick: () => {
        onSelectMenu('selectAll');
      },
    },
  ];
  const arrAppend = [
    {
      name: 'selectNone',
      label: props.intl.formatMessage({
        id: 'app.list.options.deselectAll',
        defaultMessage: 'Deselect all',
      }),
      onClick: () => {
        onSelectMenu('selectNone');
      },
    },
    { name: 'divider' },
    {
      name: 'exportSelect',
      label: props.intl.formatMessage({
        id: 'app.list.options.exportSelect',
        defaultMessage: 'Export selection',
      }),
      onClick: () => {
        onSelectMenu('exportSelection');
      },
    },
  ];
  if (props.[[:FEATURE_LOWER:]].selected.length > 0) {
    arrOne = arrOne.concat(arrAppend);
  }
  const arrStandard = [
    { name: 'divider' },
    {
      name: 'exportAll',
      label: props.intl.formatMessage({
        id: 'app.list.options.exportAll',
        defaultMessage: 'Export all',
      }),
      onClick: () => {
        onSelectMenu('exportAll');
      },
    },
  ];
  arrOne = arrOne.concat(arrStandard);
  return arrOne;
};

/**
 *
 */
export const getInlineActions = ({ props, onSelectList, onGetOne, onDelOne, onPrint, state }) => {
  const { editions } = state;
  let myEditions = [];
  editions.forEach(edition => {
    myEditions.push({ label: edition.edi_name, onClick: item => onPrint(edition.id, item) });
  });

  return [
    {
      name: 'print',
      label: props.intl.formatMessage({
        id: 'app.list.button.print',
        defaultMessage: 'Print',
      }),
      onClick: onPrint,
      theme: 'secondary',
      icon: <PrintIcon color="white" />,
      role: 'PRINT',
      param: 'object',
      active: myEditions.length > 0,
      options: myEditions,
    },
    {
      name: 'modify',
      label: props.intl.formatMessage({
        id: 'app.list.button.modify',
        defaultMessage: 'Modify',
      }),
      onClick: onGetOne,
      theme: 'secondary',
      icon: <GetOneIcon color="white" />,
      role: 'MODIFY',
    },
    {
      name: 'delete',
      label: props.intl.formatMessage({
        id: 'app.list.button.delete',
        defaultMessage: 'Delete',
      }),
      onClick: onDelOne,
      theme: 'warning',
      icon: <DelOneIcon color="white" />,
      role: 'DELETE',
    },
  ];
};

export const getCols = ({ props }) => {
  return [
    {
      name: 'photo',
      label: '',
      col: 'default_blob.caum_short_blob',
      size: '3',
      mob_size: '',
      sortable: false,
      title: true,
      first: true,
      selectable: true,
      type: 'thumbnail',
    },
    {
      name: 'id',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.id',
        defaultMessage: 'Number',
      }),
      col: 'id',
      size: '4',
      mob_size: '',
      title: true,
      sortable: true,
      filterable: false,
      hidden: true,
      card: { role: 'ID' },
    },
    {
      name: 'name',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.name',
        defaultMessage: 'Name',
      }),
      col: 'cau_name',
      size: '5',
      mob_size: '',
      title: true,
      sortable: true,
      filterable: { type: 'text' },
      card: { role: 'TITLE' },
    },
    {
      name: 'subspecies',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.subspecies',
        defaultMessage: 'Subspecies',
      }),
      col: 'subspecies.sspe_name',
      size: '8',
      mob_size: '',
      title: true,
      sortable: true,
      card: { role: 'FIELD' },
    },
    {
      name: 'sexe',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.sex',
        defaultMessage: 'Sex',
      }),
      col: 'cau_sex',
      size: '3',
      mob_size: '',
      type: 'switch',
      values: sexSelect,
      sortable: true,
      filterable: false,
      card: { role: 'FIELD' },
    },
    {
      name: 'cau_year',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.cauYear',
        defaultMessage: 'Born in',
      }),
      col: 'cau_year',
      size: '4',
      mob_size: '',
      type: 'numeric',
      title: true,
      sortable: true,
      card: { role: 'FIELD' },
    },
    {
      name: 'cau_mnt',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.mnt',
        defaultMessage: 'Raised',
      }),
      col: 'cau_mnt',
      size: '4',
      mob_size: '',
      type: 'monetary',
      title: true,
      fDisplay: (item, newContent) => {
        if (item.cau_to === '' || item.cau_to === null) {
          return newContent;
        } else {
          return '';
        }
      },
      filterable: { type: 'monetary' },
      sortable: true,
      card: { role: 'FIELD' },
    },
    {
      name: 'cau_mnt_left',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.left',
        defaultMessage: 'Left',
      }),
      col: 'cau_mnt_left',
      size: '4',
      mob_size: '',
      type: 'monetary',
      title: true,
      fDisplay: (item, newContent) => {
        if (item.cau_to === '' || item.cau_to === null) {
          if (item.cause_type && item.cause_type.caut_max_mnt > 0) {
            return newContent;
          }
        } else {
          return '-';
        }
      },
      filterable: { type: 'monetary' },
      sortable: true,
      card: { role: 'FIELD' },
    },
    {
      name: 'site',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.site',
        defaultMessage: 'Location',
      }),
      col: 'site.site_name',
      size: '5',
      mob_size: '',
      title: true,
      sortable: true,
    },
    {
      name: 'cau_to',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.end',
        defaultMessage: 'End',
      }),
      col: 'cau_to',
      size: '0',
      mob_size: '0',
      hidden: true,
      filterable: { type: 'date' },
    },
    {
      name: 'site',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.site',
        defaultMessage: 'Location',
      }),
      col: 'site.site_id',
      size: '0',
      mob_size: '0',
      hidden: true,
      filterable: {
        type: 'select',
        options: siteAsOptions(props.site.items),
      },
      last: true,
    },
  ];
};

export const getColsSimple = ({ props }) => {
  return [
    {
      name: 'photo',
      label: '',
      col: 'default_blob.caum_short_blob',
      size: '3',
      mob_size: '',
      sortable: false,
      title: true,
      first: true,
      type: 'thumbnail',
    },
    {
      name: 'id',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.id',
        defaultMessage: 'Number',
      }),
      col: 'id',
      size: '4',
      mob_size: '',
      title: true,
      sortable: true,
      filterable: false,
      hidden: true,
      card: { role: 'ID' },
    },
    {
      name: 'name',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.name',
        defaultMessage: 'Name',
      }),
      col: 'cau_name',
      size: '3',
      mob_size: '',
      title: true,
      sortable: true,
      filterable: { type: 'text' },
      card: { role: 'TITLE' },
    },
    {
      name: 'cau_mnt',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.mnt',
        defaultMessage: 'Raised',
      }),
      col: 'cau_mnt',
      size: '4',
      mob_size: '',
      type: 'monetary',
      title: true,
      fDisplay: (item, newContent) => {
        if (item.cau_to === '' || item.cau_to === null) {
          return newContent;
        } else {
          return '';
        }
      },
      filterable: { type: 'monetary' },
      sortable: true,
      card: { role: 'FIELD' },
    },
    {
      name: 'cau_mnt_left',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.left',
        defaultMessage: 'Left',
      }),
      col: 'cau_mnt_left',
      size: '4',
      mob_size: '',
      type: 'monetary',
      title: true,
      fDisplay: (item, newContent) => {
        if (item.cau_to === '' || item.cau_to === null) {
          if (item.cause_type && item.cause_type.caut_max_mnt > 0) {
            return newContent;
          }
        } else {
          return '-';
        }
      },
      filterable: { type: 'monetary' },
      sortable: true,
      card: { role: 'FIELD' },
    },
    {
      name: 'site',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.site',
        defaultMessage: 'Location',
      }),
      col: 'site.site_name',
      size: '3',
      mob_size: '',
      title: true,
      sortable: true,
    },
    {
      name: 'cau_to',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.end',
        defaultMessage: 'End',
      }),
      col: 'cau_to',
      size: '0',
      mob_size: '0',
      hidden: true,
      filterable: { type: 'date' },
    },
    {
      name: 'site',
      label: props.intl.formatMessage({
        id: 'app.features.[[:FEATURE_LOWER:]].list.col.site',
        defaultMessage: 'Location',
      }),
      col: 'site.site_id',
      size: '0',
      mob_size: '0',
      hidden: true,
      filterable: {
        type: 'select',
        options: siteAsOptions(props.site.items),
      },
      last: true,
    },
  ];
};
