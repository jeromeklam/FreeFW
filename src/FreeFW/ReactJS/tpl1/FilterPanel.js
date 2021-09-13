import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { injectIntl } from 'react-intl';
import { FilterPanel as UIFilterPanel, Filter } from 'react-bootstrap-front';
import * as actions from './redux/actions';
import {
  Calendar as CalendarIcon,
  DelOne as ClearIcon,
  FilterEmpty as FilterEmptyIcon,
  FilterFull as FilterFullIcon,
  FilterClear as FilterClearIcon,
  FilterDefault as FilterDefaultIcon,
  FilterClearDefault as FilterClearDefaultIcon,
  SimpleCancel as CancelPanelIcon,
  Search as ValidPanelIcon,
} from '../icons';
import { getCols } from './';

export class FilterPanel extends Component {
  static propTypes = {
    [[:FEATURE_CAMEL:]]: PropTypes.object.isRequired,
    actions: PropTypes.object.isRequired,
  };

  constructor(props) {
    super(props);
    this.state = {
      timer: null
    }
    this.onFilter = this.onFilter.bind(this);
  }

  onFilter(filters, sort) {
    this.props.onToggleRightPanel();
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

  render() {
    const cols = getCols(this);
    return (
      <UIFilterPanel
        calIcon={<CalendarIcon className="text-secondary" />}
        clearIcon={<ClearIcon className="text-warning" />}
        filterFullIcon={<FilterFullIcon color="white" />}
        filterEmptyIcon={<FilterEmptyIcon color="white" />}
        filterClearIcon={<FilterClearIcon color="white" />}
        filterDefaultIcon={<FilterDefaultIcon color="white" />}
        filterClearDefaultIcon={<FilterClearDefaultIcon color="white" />}
        validPanelIcon={<ValidPanelIcon />}
        cancelPanelIcon={<CancelPanelIcon />}
        cols={cols}
        filters={this.props.[[:FEATURE_CAMEL:]].filters}
        sort={this.props.[[:FEATURE_CAMEL:]].sort}
        onToggleFilter={this.onFilter}
        t={this.props.intl.formatMessage}
        simpleMode={true}
      />
    );
  }
}

/* istanbul ignore next */
function mapStateToProps(state) {
  return {
    [[:FEATURE_CAMEL:]]: state.[[:FEATURE_CAMEL:]],
  };
}

/* istanbul ignore next */
function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators({ ...actions }, dispatch),
  };
}

export default injectIntl(connect(mapStateToProps, mapDispatchToProps)(FilterPanel));
