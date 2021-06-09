import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { injectIntl } from 'react-intl';
import * as actions from './redux/actions';
import { withRouter } from 'react-router-dom';
import { getJsonApi } from 'jsonapi-front';
import { propagateModel } from '../../common';
import { PortalLoader, createSuccess, modifySuccess, showErrors } from '../ui';
import Form from './Form';
import { getActionsButtons } from './';

export class Input extends Component {
  static propTypes = {
    cause: PropTypes.object.isRequired,
    actions: PropTypes.object.isRequired,
    loader: PropTypes.bool,
  };
  static defaultProps = {
    loader: true,
  };

  constructor(props) {
    super(props);
    this.state = {
      id: this.props.cauId || 0,
      item: null,
    };
    this.onSubmit = this.onSubmit.bind(this);
    this.onCancel = this.onCancel.bind(this);
    this.onPrint = this.onPrint.bind(this);
  }

  componentDidMount() {
    this.props.actions.loadOne(this.state.id).then(item => {
      this.setState({ item: item });
    });
  }

  onCancel(event) {
    if (event) {
      event.preventDefault();
    }
    this.props.onClose();
  }

  onSubmit(datas = {}, close = true) {
    delete datas.default_blob;
    if (this.state.id > 0) {
      this.props.actions
        .updateOne(this.state.id, datas)
        .then(item => {
          modifySuccess();
          if (this.props.onClose && close) {
            this.props.onClose();
          } else {
            this.setState({ item: item });
          }
        })
        .catch(errors => {
          showErrors(this.props.intl, errors);
        });
    } else {
      this.props.actions
        .createOne(datas)
        .then(item => {
          createSuccess();
          if (this.props.onClose && close) {
            this.props.onClose();
          } else {
            this.setState({ id: item.id, item: item });
          }
        })
        .catch(errors => {
          showErrors(this.props.intl, errors);
        });
    }
  }

  onPrint(ediId = 0) {
    let idx = this.props.editions.findIndex(elem => elem.id === ediId);
    if (idx < 0) {
      idx = 0;
    }
    this.props.actions.printOne(this.state.id, this.props.editions[idx].id);
  }

  render() {
    const { item, id } = this.state;
    return (
      <div className="cause-modify global-card">
        {!item ? (
          <PortalLoader show={this.props.loader} />
        ) : (
          <div>
            {item && (
              <Form
                item={item}
                modify={id > 0}
                cause_types={this.props.causeType.items}
                tab_datas={this.props.data.items}
                subspecies={this.props.subspecies.items}
                tab_configs={this.props.config.items}
                tab={this.props.[[:FEATURE_LOWER:]].tab}
                tabs={this.props.[[:FEATURE_LOWER:]].tabs}
                errors={id > 0 ? this.props.[[:FEATURE_LOWER:]].updateOneError : this.props.[[:FEATURE_LOWER:]].createOneError}
                actionsButtons={getActionsButtons(this)}
                onSubmit={this.onSubmit}
                onCancel={this.onCancel}
                onClose={this.props.onClose}
              />
            )}
          </div>
        )}
      </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    data: state.data,
    config: state.config,
    cause: state.cause,
    causeType: state.causeType,
    subspecies: state.subspecies,
  };
}

function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators({ ...actions, propagateModel }, dispatch),
  };
}

export default injectIntl(withRouter(connect(mapStateToProps, mapDispatchToProps)(Input)));
