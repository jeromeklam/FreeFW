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
export const getActionsButtons = ({ onPrint, state }) => {
  if (state.id > 0) {
    return [
      {
        theme: 'secondary',
        hidden: false,
        function: () => onPrint(),
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
  ];
};
