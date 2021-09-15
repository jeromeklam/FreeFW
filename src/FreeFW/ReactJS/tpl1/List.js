import React, { Component } from 'react';
import { injectIntl } from 'react-intl';
import PropTypes from 'prop-types';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import * as actions from './redux/actions';
import { normalizedObjectModeler } from 'jsonapi-front';
import { Search as SearchIcon, Agent as MainIcon } from '../icons';
import { getEditions } from '../edition';
import {
  ResponsiveQuickSearch,
  List as UiList,
  showErrors,
  deleteSuccess,
  messageSuccess,
} from '../ui';
import {
  Input,
  getGlobalActions,
  getInlineActions,
  getCols,
  getSelectActions,
  getShortcuts
} from './';

/**
 * Gestion de la liste
 *
 * Spécificités de la liste :
 *     Il peut y avoir des filtres par défaut 
 *     Il faut supprimer les filtres par défaut si on veut voir tout voir
 */
export class List extends Component {
  static propTypes = {
    [[:FEATURE_CAMEL:]]: PropTypes.object.isRequired,
    actions: PropTypes.object.isRequired,
    edition: PropTypes.object.isRequired,
    loadTimeOut: PropTypes.number,
  };
  static defaultProps = {
    loadTimeOut: 2000,
  };

  /**
   * Constructeur
   *
   * Initialisation du state local et lien des méthodes locales au contexte courant
   */
  constructor(props) {
    super(props);
    // On récupère toutes les édtiions liées à l'objet
    const editions = getEditions(props.edition.models, '[[:FEATURE_MODEL:]]');
    this.state = {
      timer: null,
      mode: null,
      item: null,
      rightMode: 'shortcuts',
      rightShortcuts: getShortcuts(props.intl),
      models: props.edition.models,
      editions: editions,
    };
    this.onCreate = this.onCreate.bind(this);
    this.onGetOne = this.onGetOne.bind(this);
    this.onDelOne = this.onDelOne.bind(this);
    this.onReload = this.onReload.bind(this);
    this.onClose = this.onClose.bind(this);
    this.onLoadMore = this.onLoadMore.bind(this);
    this.onClearFilters = this.onClearFilters.bind(this);
    this.onQuickSearch = this.onQuickSearch.bind(this);
    this.onSetFiltersAndSort = this.onSetFiltersAndSort.bind(this);
    this.onUpdateSort = this.onUpdateSort.bind(this);
    this.onSelect = this.onSelect.bind(this);
    this.onSelectList = this.onSelectList.bind(this);
    this.onSelectMenu = this.onSelectMenu.bind(this);
    this.itemClassName = this.itemClassName.bind(this);
    this.onPrint = this.onPrint.bind(this);
    this.onPrevious = this.onPrevious.bind(this);
    this.onNext = this.onNext.bind(this);
    this.onUpdateShortcuts = this.onUpdateShortcuts.bind(this);
  }

  /**
   * Au chargement de la liste
   *
   * On demande de charger la liste depuis le début
   * On utilisera les filtres par défaut posés dans l'initialState
   */
  componentDidMount() {
    this.props.actions.loadMore(true);
  }

  /**
   * Sur sélection d'un élément dans la liste
   *
   * On va positionner dans le state global (store) l'élément sélectionné
   *
   * @param {String} id
   */
  onSelect(id) {
    this.props.actions.onSelect(id);
  }

  /**
   * Demande de création
   *
   * On indique 0 pour passer en création d'un nouvel élément
   */
  onCreate() {
    this.props.actions.setCurrent(0, 'add');
  }

  /**
   * Demande de modification
   *
   * On indique l'id de l'élément à modifier
   *
   * @param {String} id
   */
  onGetOne(id) {
    this.props.actions.setCurrent(id, 'modify');
  }

  /**
   * Précédent
   */
  onPrevious() {
    this.props.actions.setPrevious();
  }

  /**
   * Suivant
   */
  onNext() {
    this.props.actions.setNext();
  }

  /**
   * Femeture de la fenêtre de saisie
   */
  onClose() {
    this.props.actions.setCurrent(null, 'off');
  }

  /**
   * Demande de suppression
   *
   * @param {String} id
   */
  onDelOne(id) {
    this.props.actions
      .delOne(id)
      .then(result => {
        // Message de confirmation
        deleteSuccess();
      })
      .catch(errors => {
        // Affichage des erreurs
        showErrors(this.props.intl, errors);
      });
  }

  /**
   * Demande de rechargement de la liste
   *
   * @param {Event} ev
   */
  onReload(ev) {
    if (ev) {
      ev.preventDefault();
    }
    this.props.actions.loadMore(true);
  }

  /**
   * Sur demande de recherche rapide
   *
   * Le timer sert à gérer le multi action
   *
   * @param {String} quickSearch
   */
  onQuickSearch(quickSearch) {
    this.props.actions.updateQuickSearch(quickSearch);
    let timer = this.state.timer;
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(() => {
      this.props.actions.loadMore(true);
    }, this.props.loadTimeOut);
    this.setState({ timer: timer });
  }

  /**
   * Modification du tri
   *
   * Le timer sert à gérer le multi action
   *
   * @param {String} col colonne
   * @param {String} way sens
   * @param {Number} pos position
   */
  onUpdateSort(col, way, pos = 99) {
    this.props.actions.updateSort(col.col, way, pos);
    let timer = this.state.timer;
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(() => {
      this.props.actions.loadMore(true);
    }, this.props.loadTimeOut);
    this.setState({ timer: timer });
  }

  /**
   * Valisation de la fenêtre de filtres et tri
   *
   * Le timer sert à gérer le multi action
   *
   * @param {Filter} filters
   * @param {Array}  sort
   */
  onSetFiltersAndSort(filters, sort) {
    this.props.actions.setFilters(filters);
    this.props.actions.setSort(sort);
    let timer = this.state.timer;
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(() => {
      this.props.actions.loadMore(true);
    }, this.props.loadTimeOut);
    this.setState({ timer: timer });
  }

  /**
   * Nettoyge des filtres et du tri,
   *
   * Le timer sert à gérer le multi action
   *
   * @param {Boolean} def Pour revenir ausi aux filtres par défaut
   */
  onClearFilters(def = false) {
    this.props.actions.initFilters(def);
    this.props.actions.initSort();
    let timer = this.state.timer;
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(() => {
      this.props.actions.loadMore(true);
    }, this.props.loadTimeOut);
    this.setState({ timer: timer });
  }

  /**
   * Plus de résultats
   */
  onLoadMore() {
    this.props.actions.loadMore();
  }

  /**
   * Changement du mode d'affichage
   *
   * @param {Object} obj  L'objet
   * @param {String} list La partie à afficher
   */
  onSelectList(obj, list) {
    if (obj) {
      if (list) {
        this.setState({ mode: list, item: obj });
      } else {
        this.setState({ item: obj });
      }
      this.props.actions.setCurrent(obj.id);
    } else {
      this.setState({ mode: false, item: null });
    }
  }

  /**
   * Sélection d'une option du menu global
   *
   * @param {String} option
   */
  onSelectMenu(option) {
    switch (option) {
      case 'selectAll':
        this.props.actions.selectAll();
        break;
      case 'selectNone':
        this.props.actions.selectNone();
        break;
      case 'exportAll':
        this.props.actions.exportAsTab('all').then(res => {
          if (!res) {
            messageSuccess('Export demandé');
          }
        });
        break;
      case 'exportSelection':
        this.props.actions.exportAsTab('selection').then(res => {
          if (!res) {
            messageSuccess('Export demandé');
          }
        });
        break;
      default:
        break;
    }
  }

  /**
   * Permet de forcer la classe associée à un item
   *
   * @param {Object} item
   */
  itemClassName(item) {
    return '';
  }

  /**
   * Demande d'édition
   *
   * @param {Number} ediId Identifiant de l'édition
   * @param {Object} item  Objet à imprimer
   */
  onPrint(ediId, item) {
    if (item) {
      this.props.actions.printOne(item.id, ediId);
    }
  }

  /**
   * Update shortcuts
   *
   * @param {String} name
   * @param {String} forced
   */
  onUpdateShortcuts(name, forced = '') {
    console.log(name, forced);
    let shortcuts = this.state.rightShortcuts;
    let idx = shortcuts.findIndex(elem => elem.name === name);
    if (idx >= 0) {
      if (forced !== '') {
        switch (forced) {
          case 'maximized':
            shortcuts[idx].display = 'block';
            shortcuts[idx].size = 'maximized';
            break;
          case 'minimized':
            shortcuts[idx].display = 'block';
            shortcuts[idx].size = 'minimized';
            break;
          default:
            shortcuts[idx].display = 'none';
            break;
        }
      } else {
        if (shortcuts[idx].size === 'maximized') {
          if (shortcuts[idx].display === 'none') {
            shortcuts[idx].display = 'block';
          } else {
            shortcuts[idx].size = 'minimized';
          }
        } else {
          if (shortcuts[idx].size === 'minimized') {
            if (shortcuts[idx].display === 'none') {
              shortcuts[idx].display = 'block';
              shortcuts[idx].size = 'maximized';
            } else {
              shortcuts[idx].display = 'none';
            }
          }
        }
      }
    }
    this.setState({ rightShortcuts: shortcuts });
  }
  
  /**
   * render
   */
  render() {
    // Pour la gestion de l'internationalisation
    const { intl } = this.props;
    // Les items à afficher avec remplissage progressif
    let items = [];
    if (this.props.[[:FEATURE_CAMEL:]].items.[[:FEATURE_MODEL:]]) {
      items = normalizedObjectModeler(this.props.[[:FEATURE_CAMEL:]].items, '[[:FEATURE_MODEL:]]');
    }
    // Actions globales, inline et colonnes
    const globalActions = getGlobalActions(this);
    const inlineActions = getInlineActions(this);
    let cols = getCols(this);
    // L'affichage, items, loading, loadMoreError
    let search = '';
    const crit = this.props.[[:FEATURE_CAMEL:]].filters.findFirst('[[:FEATURE_MAINCOL:]]');
    if (crit) {
      search = crit.getFilterCrit();
    }
    // La gestion de la zone de recherche rapide
    const quickSearch = (
      <ResponsiveQuickSearch
        name="quickSearch"
        label={intl.formatMessage({
          id: 'app.features.[[:FEATURE_LOWER:]].list.search',
          defaultMessage: 'Search by ...',
        })}
        quickSearch={search}
        onSubmit={this.onQuickSearch}
        onChange={this.onSearchChange}
        icon={<SearchIcon className="text-secondary" size={0.8} />}
      />
    );
    // Select actions
    const selectActions = getSelectActions(this);
    // InLine components
    let currentItem = null;
    if (this.props.[[:FEATURE_CAMEL:]].currentId) {
      currentItem = normalizedObjectModeler(
        this.props.[[:FEATURE_CAMEL:]].items,
        '[[:FEATURE_MODEL:]]',
        this.props.[[:FEATURE_CAMEL:]].currentId,
      );
    }
    let inlineComponent = null;
    return (
      <div>
        <UiList
          title={intl.formatMessage({
            id: 'app.features.[[:FEATURE_LOWER:]].list.title',
            defaultMessage: '...',
          })}
          icon={<MainIcon />}
          intl={intl}
          cols={cols}
          items={items}
          counter={this.props.[[:FEATURE_CAMEL:]].items.SORTEDELEMS.length + ' / ' + this.props.[[:FEATURE_CAMEL:]].items.TOTAL}
          quickSearch={quickSearch}
          mainCol="[[:FEATURE_MAINCOL:]]"
          inlineActions={inlineActions}
          currentItem={currentItem}
          inlineComponent={inlineComponent}
          globalActions={globalActions}
          sort={this.props.[[:FEATURE_CAMEL:]].sort}
          filters={this.props.[[:FEATURE_CAMEL:]].filters}
          panelObject="[[:FEATURE_CAMEL:]]"
          onSearch={this.onQuickSearch}
          onSort={this.onUpdateSort}
          onSetFiltersAndSort={this.onSetFiltersAndSort}
          onClearFilters={this.onClearFilters}
          onLoadMore={this.onLoadMore}
          onClick={this.onSelectList}
          loadMorePending={this.props.[[:FEATURE_CAMEL:]].loadMorePending}
          loadMoreFinish={this.props.[[:FEATURE_CAMEL:]].loadMoreFinish}
          loadMoreError={this.props.[[:FEATURE_CAMEL:]].loadMoreError}
          fClassName={this.itemClassName}
          selected={this.props.[[:FEATURE_CAMEL:]].selected}
          selectMenu={selectActions}
          onSelect={this.onSelect}
          onPrevious={this.onPrevious}
          onNext={this.onNext}
          rightMode={this.state.rightMode}
          shortcuts={this.state.rightShortcuts}
          onUpdateShortcuts={this.onUpdateShortcuts}
        />
        {(this.props.[[:FEATURE_CAMEL:]].currentMode === 'add' || 
          this.props.[[:FEATURE_CAMEL:]].currentMode === 'modify') && (
          <Input
            modal={true}
            id={this.props.[[:FEATURE_CAMEL:]].currentMode === 'modify' ? this.props.[[:FEATURE_CAMEL:]].currentId : 0}
            onClose={this.onClose}
            loader={false}
            editions={this.state.editions}
          />
        )}
      </div>
    );
  }
}

/**
 * Quels éléments du store ai-je besoin en local
 * Ces éléments seront accessibles via this.props.<key>
 */
function mapStateToProps(state) {
  return {
    loadTimeOut: state.auth.loadTimeOut,
    [[:FEATURE_CAMEL:]]: state.[[:FEATURE_CAMEL:]],
    edition: state.edition,
  };
}

/**
 * Quelles actions du store ai-je besoin ?
 * Ces actions seront accessibles via this.props.actions.<action>
 */
function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators({ ...actions }, dispatch),
  };
}

/**
 * Injection de l'intl et de redux pour connecter le store
 */
export default injectIntl(connect(mapStateToProps, mapDispatchToProps)(List));
