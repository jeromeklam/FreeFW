import React from 'react';
import {
  AddOne as AddOneIcon,
  GetOne as GetOneIcon,
  DelOne as DelOneIcon,
  Print as PrintIcon,
} from '../icons';
import { displayColBool } from '../ui';
 
 /**
 * Shortcuts
 *
 * @param {Object} intl
 * @param {String} mode
 */
export const getShortcuts = (intl, mode = 'all') => {
  let shortcuts = [];
  shortcuts.push({
    name: 'ident',
    icon: '',
    size: 'maximized',
    display: 'block',
    position: 1,
    label: intl.formatMessage({
      id: 'app.features.[[:FEATURE_LOWER:]].form.tabs.ident',
      defaultMessage: 'Identification',
    }),
  });
  if (mode === 'all') {
  }
  return shortcuts;
}
 
/**
 * Gestion de l'affichage du champ en fonction du modèle 
 */
export function getPickerDisplay(item) {
  let display = '';
  if (item && item.id > 0) {
    display = item.[[:FEATURE_MAINCOL:]];
  }
  return display;
}

/**
 * Affichage des agents dans la liste des propositions de la recherhe 
 * ou dans la liste proposées sous le champ en autocomplète
 */
export const displayItemPicker = item => {
  return (
    <p className="input-picker-line">
      <span className="input-picker-left">{item.[[:FEATURE_MAINCOL:]]}</span>
    </p>
  );
};

/**
 * Actions disponibles depuis l'en-tête de la liste des agents
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
      icon: <AddOneIcon />,
      role: 'CREATE',
    },
  ];
};

/**
 * Actions disponibles sur la fiche (en plus de l'enregistrement et de l'annulation)
 */
export const getActionsButtons = ({ onPrint, state }) => {
  const { editions } = state;
  let myOptions = [];
  let printabled = false;
  if (editions && Array.isArray(editions) && editions.length > 0) {
    printabled = true;
    editions.forEach(edition => {
      myOptions.push({
        id: edition.id,
        label: edition.edi_name,
        onClick: () => onPrint(edition.id),
      });
    });
  }
  return [
    {
      theme: 'secondary',
      hidden: !printabled,
      options: myOptions,
      optionsAlign: 'top-left',
      function: () => onPrint(),
      icon: <PrintIcon title="Imprimer" />,
    },
  ];
};

/**
 * Actions disponibles depuis l'en-tête de la liste des agents pour agir sur les lignes de la liste
 *    Sélection lignes 
 *    Dé-sélection 
 *    ... 
 *    Actions sur lignes sélectionnées
 *    ou sur toutes les lignes 
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
  if (props.[[:FEATURE_CAMEL:]].selected.length > 0) {
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
 * Actions disponibles sur une ligne de la liste des agents au survol de cette ligne
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
      icon: <PrintIcon />,
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
      icon: <GetOneIcon />,
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
      icon: <DelOneIcon />,
      role: 'DELETE',
    },
  ];
};

/**
 * Descriptions de toutes les colonnes de la liste
 * Même les colonnes cachées
 * Ces colonnes peuvent ou non données des champs dans les filtres
 * (une info peut de pas être affichée dans la liste mais on peut peut-être mettre un filtre dessus)
 */
export const getCols = ({ props }) => {
  return [
    [[:FEATURE_COLS:]]
  ];
};
