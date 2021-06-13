import React, { Component } from 'react';
import { injectIntl } from 'react-intl';
import PropTypes from 'prop-types';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import * as actions from './redux/actions';
import { normalizedObjectModeler } from 'jsonapi-front';
import { ResponsiveQuickSearch, FILTER_OPER_EQUAL } from 'react-bootstrap-front';
import { Search as SearchIcon } from '../icons';
import { showErrors, deleteSuccess, messageSuccess, List as UiList } from '../ui';
import { getGlobalActions, getInlineActions, getCols, getColsSimple } from './';
import { InlinePhotos, InlineNews, InlineSponsors, Input, getSelectActions } from './';
import { loadDonations } from '../donation/redux/actions';
import { InlineSponsorships } from '../sponsorship';
import { InlineDonations } from '../donation';
import { getCausetype } from '../[[:FEATURE_SERVICE:]]-type';
import { getEditions, printEdition } from '../edition';

export class List extends Component {
  static propTypes = {
    .[[:FEATURE_LOWER:]]: PropTypes.object.isRequired,
    actions: PropTypes.object.isRequired,
  };

  static getDerivedStateFromProps(props, state) {
    if (props.match.params.cautId !== state.cautId) {
      return { cautId: props.match.params.cautId };
    }
    return null;
  }

  constructor(props) {
    super(props);
    const .[[:FEATURE_LOWER:]]Type = getCausetype(this.props..[[:FEATURE_LOWER:]]Type.items, this.props.match.params.cautId);
    this.state = {
      timer: null,
      id: -1,
      mode: null,
      item: null,
      models: props.edition.models,
      editions: getEditions(props.edition.models, '[[:FEATURE_MODEL:]]'),
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
  }

  componentDidMount() {
    this.props.actions.loadMore(false, true);
  }

  onSelect(id) {
    this.props.actions.onSelect(id);
  }

  onCreate(event) {
    this.setState({ id: 0 });
  }

  onGetOne(id) {
    this.setState({ id: id });
  }

  onClose() {
    this.setState({ id: -1 });
  }

  onDelOne(id) {
    this.props.actions
      .delOne(id)
      .then(result => {
        deleteSuccess();
        this.props.actions.loadMore({}, true);
      })
      .catch(errors => {
        showErrors(this.props.intl, errors);
      });
  }

  onReload(event) {
    if (event) {
      event.preventDefault();
    }
    this.props.actions.loadMore({}, true);
  }

  onQuickSearch(quickSearch) {
    this.props.actions.updateQuickSearch(quickSearch);
    let timer = this.state.timer;
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(() => {
      this.props.actions.loadMore({}, true);
    }, this.props.loadTimeOut);
    this.setState({ timer: timer });
  }

  onUpdateSort(col, way, pos = 99) {
    this.props.actions.updateSort(col.col, way, pos);
    let timer = this.state.timer;
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(() => {
      this.props.actions.loadMore({}, true);
    }, this.props.loadTimeOut);
    this.setState({ timer: timer });
  }

  onSetFiltersAndSort(filters, sort) {
    this.props.actions.setFilters(filters);
    this.props.actions.setSort(sort);
    let timer = this.state.timer;
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(() => {
      this.props.actions.loadMore({}, true);
    }, this.props.loadTimeOut);
    this.setState({ timer: timer });
  }

  onClearFilters(def = false) {
    console.log(def);
    this.props.actions.initFilters(def);
    this.props.actions.initSort();
    let timer = this.state.timer;
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(() => {
      this.props.actions.loadMore({}, true);
    }, this.props.loadTimeOut);
    this.setState({ timer: timer });
  }

  onLoadMore(event) {
    this.props.actions.loadMore();
  }

  onSelectList(obj, list) {
    if (obj) {
      if (list) {
        this.setState({ mode: list, item: obj });
      } else {
        this.setState({ item: obj });
      }
    } else {
      this.setState({ mode: false, item: null });
    }
  }

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

  itemClassName(item) {
    if (item && item.cau_to !== null && item.cau_to !== '') {
      return 'row-line-warning';
    }
    return '';
  }

  onPrint(ediId, item) {
    if (item) {
      this.props.actions.printOne(item.id, ediId);
    }
  }

  render() {
    const { intl } = this.props;
    // Les items à afficher avec remplissage progressif
    let items = [];
    if (this.props.[[:FEATURE_LOWER:]].items.[[:FEATURE_MODEL:]]) {
      items = normalizedObjectModeler(this.props.[[:FEATURE_LOWER:]].items, '[[:FEATURE_MODEL:]]');
    }
    const globalActions = getGlobalActions(this);
    const inlineActions = getInlineActions(this);
    let cols = getCols(this);
    // L'affichage, items, loading, loadMoreError
    let search = '';
    const crit = this.props.[[:FEATURE_LOWER:]].filters.findFirst('cau_name');
    if (crit) {
      search = crit.getFilterCrit();
    }
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
        icon={<SearchIcon className="text-secondary" />}
      />
    );
    // Select actions
    const selectActions = getSelectActions(this);
    // InLine components
    let inlineComponent = null;
    return (
      <div>
        <UiList
          title={intl.formatMessage({
            id: 'app.features.[[:FEATURE_LOWER:]].list.title',
            defaultMessage: '...',
          })}
          intl={intl}
          cols={cols}
          items={items}
          counter={this.props.[[:FEATURE_LOWER:]].items.SORTEDELEMS.length + ' / ' + this.props.[[:FEATURE_LOWER:]].items.TOTAL}
          quickSearch={quickSearch}
          mainCol="cau_name"
          inlineActions={inlineActions}
          currentItem={this.state.item}
          currentInline={this.state.mode}
          inlineComponent={inlineComponent}
          globalActions={globalActions}
          sort={this.props.[[:FEATURE_LOWER:]].sort}
          filters={this.props.[[:FEATURE_LOWER:]].filters}
          onSearch={this.onQuickSearch}
          onSort={this.onUpdateSort}
          onSetFiltersAndSort={this.onSetFiltersAndSort}
          onClearFilters={this.onClearFilters}
          onLoadMore={this.onLoadMore}
          onClick={this.onSelectList}
          loadMorePending={this.props.[[:FEATURE_LOWER:]].loadMorePending}
          loadMoreFinish={this.props.[[:FEATURE_LOWER:]].loadMoreFinish}
          loadMoreError={this.props.[[:FEATURE_LOWER:]].loadMoreError}
          fClassName={this.itemClassName}
          selected={this.props.[[:FEATURE_LOWER:]].selected}
          selectMenu={selectActions}
          onSelect={this.onSelect}
        />
        {this.state.id >= 0 && (
          <Input
            modal={true}
            id={this.state.id}
            onClose={this.onClose}
            loader={false}
            editions={this.state.editions}
          />
        )}
      </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    loadTimeOut: state.auth.loadTimeOut,
    [[:FEATURE_LOWER:]]: state.[[:FEATURE_LOWER:]],
    edition: state.edition,
  };
}

function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators({ ...actions }, dispatch),
  };
}

export default injectIntl(connect(mapStateToProps, mapDispatchToProps)(List));
