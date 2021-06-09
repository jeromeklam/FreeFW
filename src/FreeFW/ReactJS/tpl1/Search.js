import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { jsonApiNormalizer, normalizedObjectModeler, objectToQueryString } from 'jsonapi-front';
import { SearchModal } from '../ui';
import { freeAssoApi } from '../../common';
import { displayItemPicker } from './';

export default class Search extends Component {
  static propTypes = {
    title: PropTypes.string.isRequired,
    show: PropTypes.bool.isRequired,
    onClose: PropTypes.func.isRequired,
  };

  constructor(props) {
    super(props);
    this.state = {
      search: '',
      list: [],
      loading: false,
      finish: false,
    };
    this.onChange = this.onChange.bind(this);
    this.onSearch = this.onSearch.bind(this);
    this.onClear = this.onClear.bind(this);
  }

  onClear() {
    this.setState({ loading: false, finish: true, list: [] });
  }

  onSearch(filters) {
    if (!this.state.loading) {
      const addUrl = objectToQueryString(filters);
      const doRequest = freeAssoApi.get('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SERVICE:]]' + addUrl, {});
      this.setState({ loading: true, finish: false, list: [] });
      doRequest.then(result => {
        let items = [];
        if (result && result.data) {
          const lines = jsonApiNormalizer(result.data);
          items = normalizedObjectModeler(lines, '[[:FEATURE_MODEL:]]');
        }
        this.setState({ loading: false, finish: true, list: items });
      });
    }
  }

  onChange(event) {
    this.setState({ [event.target.name]: event.target.value });
  }

  render() {
    const filters = [{ name: 'cau_name', label: 'Nom', type: 'text' }];
    return (
      <SearchModal
        title={this.props.title}
        show={this.props.show}
        loading={this.state.loading}
        onClose={this.props.onClose}
        onClear={this.onClear}
        onSearch={this.onSearch}
        onSelect={this.props.onSelect}
        list={this.state.list}
        pickerDisplay={displayItemPicker}
        filters={filters}
      />
    );
  }
}
