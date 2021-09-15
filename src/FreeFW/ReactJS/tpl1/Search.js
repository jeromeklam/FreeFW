import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { injectIntl } from 'react-intl';
import {
  jsonApiNormalizer,
  normalizedObjectModeler,
  objectToQueryString,
  getNewNormalizedObject,
} from 'jsonapi-front';
import { Filter } from 'react-bootstrap-front';
import { SearchModal, ResponsiveInlineList } from '../ui';
import { freeAssoApi } from '../../common';
import { displayItemPicker, getCols } from './';

/**
 * Recherche liée au champ de saisie assistée du modèle (picker)
 */
export class Search extends Component {
  static propTypes = {
    title: PropTypes.string.isRequired,
    show: PropTypes.bool.isRequired,
    onSelect: PropTypes.func.isRequired,
    onClose: PropTypes.func.isRequired,
  };

  /**
   * Param update ??
   *
   * @param {Object} props
   * @param {Object} state
   */
  static getDerivedStateFromProps(props, state) {
    if (props.conditions !== state.conditions) {
      return { conditions: props.conditions };
    }
    return null;
  }
  
  /**
   * Constructeur
   */
  constructor(props) {
    super(props);
    this.state = {
      search: '',
      list: [],
      lines: getNewNormalizedObject('[[:FEATURE_MODEL:]]'),
      loading: false,
      finish: false,
      page: 1,
      size: 10,
      total: 0,
      conditions: props.conditions,
      filters: new Filter(),
    };
    this.onChange = this.onChange.bind(this);
    this.onSearch = this.onSearch.bind(this);
    this.onClear = this.onClear.bind(this);
    this.onMore = this.onMore.bind(this);
  }

  /**
   * Suppression des critères de sélection
   */
  onClear() {
    this.setState({ loading: false, finish: true, list: [] });
  }

  /**
   * Page suivante
   */
  onMore() {
    this.onSearch(this.state.filters, this.state.page + 1);
  }

  /**
   * Lancement de la recherche en fonction de critères
   *
   * On utilise loading pour avoir l'état du chargement et items pour stocker les résultats
   * Ce sera paginé...
   *
   * @param {Filter} filters
   */
  onSearch(filters, page = null) {
    if (!page) {
      page = 1;
    }
    if (!this.state.loading) {
      this.state.conditions.forEach(cond => filters.addFilter(cond.field, cond.value, cond.oper));
      const crits = filters.asJsonApiObject();
      const dSort = [[:FEATURE_SORT:]];
      let sort = '';
      dSort.forEach(elt => {
        let add = elt.col;
        if (elt.way === 'down') {
          add = '-' + add;
        }
        if (sort === '') {
          sort = add;
        } else {
          sort = sort + ',' + add;
        }
      });
      let params = {
        ...crits,
        page: { number: page, size: this.state.size },
      };
      if (sort !== '') {
        params.sort = sort;
      }
      const addUrl = objectToQueryString(params);
      const doRequest = freeAssoApi.get('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SNAKE:]]' + addUrl, {});
      this.setState({ loading: true, finish: false, filters: filters, page: page + 1 });
      doRequest
        .then(result => {
          let items = [];
          let lines = getNewNormalizedObject('[[:FEATURE_MODEL:]]');
          if (result && result.data) {
            if (page > 1) {
              lines = jsonApiNormalizer(result.data, this.state.lines);
            } else {
              lines = jsonApiNormalizer(result.data);
            }
            items = normalizedObjectModeler(lines, '[[:FEATURE_MODEL:]]');
          }
          this.setState({
            loading: false,
            finish: true,
            list: items,
            lines: lines,
            total: lines.TOTAL,
          });
        })
        .catch(err => {
          this.setState({
            loading: false,
            finish: true,
            list: [],
            lines: getNewNormalizedObject('[[:FEATURE_MODEL:]]'),
            total: 0,
          });
        });
    }
  }

  /**
   * Prise en compte des changements de critères
   *    Nom du critère + valeur
   */
  onChange(event) {
    this.setState({ [event.target.name]: event.target.value });
  }

  /**
   * Render
   */
  render() {
    let filters = this.state.filters;
    this.state.conditions.forEach(cond => filters.addFilter(cond.field, cond.value, cond.oper));
    const cols = getCols(this);
    return (
      <SearchModal
        title={this.props.intl.formatMessage({
          id: 'app.features.[[:FEATURE_LOWER:]].search.title',
          defaultMessage: 'Rechercher',
        })}
        show={this.props.show}
        loading={this.state.loading}
        onClose={this.props.onClose}
        onClear={this.onClear}
        onSearch={this.onSearch}
        onSelect={this.props.onSelect}
        list={
          <ResponsiveInlineList
            cols={cols}
            {...this.props}
            items={this.state.list}
            onMore={() => this.onMore}
            total={this.state.total}
            loading={this.state.loading}
          />
        }
        pickerDisplay={displayItemPicker}
        filters={filters}
        cols={cols}
        t={this.props.intl.formatMessage}
      />
    );
  }
}

export default injectIntl(Search);
