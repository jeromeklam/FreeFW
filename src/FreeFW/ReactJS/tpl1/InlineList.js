import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { jsonApiNormalizer, objectToQueryString, normalizedObjectModeler } from 'jsonapi-front';
import { Loading3Dots } from 'react-bootstrap-front';
import { SimpleLabel as DataSimpleLabel } from '../data';
import { freeAssoApi } from '../../common';

export default class InlineList extends Component {
  static propTypes = {
    site_id: PropTypes.string.isRequired,
  };

  constructor(props) {
    super(props);
    this.state = {
      site_id: this.props.site_id,
      list: [],
      loading: false,
    };
  }

  componentDidMount() {
    if (!this.state.loading) {
      this.setState({ loading: true });
      const params = { filter: { site_id: this.props.site_id } };
      const addUrl = objectToQueryString(params);
      const doRequest = freeAssoApi.get('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SERVICE:]]' + addUrl, {});
      doRequest
        .then(result => {
          const lines = jsonApiNormalizer(result.data);
          const items = normalizedObjectModeler(lines, '[[:FEATURE_MODEL:]]');
          this.setState({ loading: false, list: items });
        })
        .catch(err => {
          this.setState({ loading: false, list: [] });
        });
    }
  }

  render() {
    return (
      <div className="cause-inline-list">
        {this.state.loading ? (
          <Loading3Dots />
        ) : (
          <div>
            {this.state.list &&
              this.state.list.map(item => {
                return (
                  <p key={item.id} title={item.cau_name}>{item.cause_type.caut_name}
                    &nbsp;( {item.cau_sex} )
                    &nbsp;( <DataSimpleLabel code="COULEUR" value={item.cau_string_1} /> )
                  </p>
                );
              })}
          </div>
        )}
      </div>
    );
  }
}
