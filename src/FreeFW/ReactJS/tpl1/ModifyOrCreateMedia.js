import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import Flag from 'react-world-flags';
import { injectIntl } from 'react-intl';
import { getJsonApi, getNewModel } from 'jsonapi-front';
import { InputHidden, InputText } from 'react-bootstrap-front';
import { ResponsiveModalOrForm, CenteredLoading3Dots, InputTextarea, modifySuccess, createSuccess } from '../ui';
import * as actions from './redux/actions';

let camd_id = 0;

const getNewCauseMediaLang = (id, subject, text, lang) => {
  return {
    id: id,
    type: '[[:FEATURE_MODEL:]]MediaLang',
    caml_subject: subject,
    caml_text: text,
    lang: {
      id: lang,
      type: 'FreeFW_Lang',
    },
  };
};

export class ModifyOrCreateMedia extends Component {
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
    let lang = '368';
    camd_id--;
    let version = getNewCauseMediaLang(camd_id, '', '', lang);
    if (props.item && props.item.versions) {
      props.item.versions.forEach(vers => {
        if (vers.lang.id === lang) {
          version = vers;
        }
      });
    }
    this.state = {
      item: null,
      cauId: props.cauId,
      caumId: props.caumId || 0,
      __currentTab: lang,
      version: version,
    };
    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleCancel = this.handleCancel.bind(this);
    this.handleNavTab = this.handleNavTab.bind(this);
    this.handleChange = this.handleChange.bind(this);
  }

  componentDidMount() {
    this.props.actions.loadOneMedia(this.state.caumId).then(result => {
      const item = this.props.[[:FEATURE_LOWER:]].loadOneMediaItem;
      let version = getNewCauseMediaLang(0, '', '', this.state.__currentTab);
      if (item && item.versions) {
        item.versions.forEach(vers => {
          if (vers.lang.id === this.state.__currentTab) {
            version = vers;
          }
        });
      }
      this.setState({ item: item, version: version });
    });
  }

  handleSubmit(ev) {
    if (ev) {
      ev.preventDefault();
    }
    let { item } = this.state;
    item.caum_code = 'NEWS';
    item.caum_type = 'HTML';
    if (!item.cause || !item.[[:FEATURE_LOWER:]].id || item.[[:FEATURE_LOWER:]].id === '') {
      item.cause = getNewModel('[[:FEATURE_MODEL:]]', this.state.cauId);
    }
    let obj = getJsonApi(item, '[[:FEATURE_MODEL:]]Media');
    if (parseInt(this.state.caumId, 10) > 0) {
      this.props.actions.updateOneMedia(this.state.caumId, obj).then(result => {
        modifySuccess();
        this.props.onClose();
      });
    } else {
      this.props.actions.createOneMedia(obj)
      .then(result => {
        createSuccess();
        this.props.onClose();
      })
      .catch(errors => {
        console.log("FK création comm",errors);
      });
    }
  }

  handleCancel(ev) {
    if (ev) {
      ev.preventDefault();
    }
    this.props.onClose();
  }

  handleNavTab(id) {
    const { item, __currentTab } = this.state;
    let version = getNewCauseMediaLang(0, '', '', __currentTab);
    if (item && item.versions) {
      item.versions.forEach(vers => {
        if (vers.lang.id === id) {
          version = vers;
        }
      });
    }
    this.setState({ __currentTab: id, version: version });
  }

  handleChange(ev) {
    const { __currentTab } = this.state;
    let item = this.state.item;
    camd_id--;
    let version = getNewCauseMediaLang(camd_id, '', '', __currentTab);
    let idxVers = -1;
    if (item && item.versions) {
      idxVers = item.versions.findIndex(elem => elem.lang.id === __currentTab);
    } else {
      item.versions = [];
    }
    if (idxVers >= 0) {
      version = item.versions[idxVers];
    }
    version[ev.target.name] = ev.target.value;
    if (idxVers >= 0) {
      item.versions[idxVers] = version;
    } else {
      item.versions.push(version);
    }
    this.setState({ item: item, version: version });
  }

  render() {
    const { intl } = this.props;
    let tabs = [];
    if (this.props.lang) {
      this.props.lang.flags.forEach(oneLang => {
        if (oneLang.lang_flag !== null && oneLang.lang_flag !== '') {
          tabs.push({
            key: oneLang.id,
            name: oneLang.lang_code,
            label: <Flag code={oneLang.lang_flag} height={24} />,
          });
        }
      });
    }
    const { item, version, __currentTab } = this.state;
    return (
      <div className="cause-modify-or-create-media">
        {item !== null ? (
          <ResponsiveModalOrForm
            title="Journal"
            className="m-5"
            tab={__currentTab}
            tabs={tabs}
            size="xl"
            onSubmit={this.handleSubmit}
            onCancel={this.handleCancel}
            onNavTab={this.handleNavTab}
            onClose={this.props.onClose}
            modal={true}
          >
            <InputHidden name="id" id="id" value={item.id} />
            {this.props.lang.flags.map(oneLang => {
              if (oneLang.id === __currentTab) {
                return (
                  <div key={`media-${oneLang.id}`}>
                    <div className="row">
                      <div className="col-xs-w36">
                        <InputText
                          Text
                          name="caml_subject"
                          label={
                            intl.formatMessage({
                              id: 'app.features.news.form.subject',
                              defaultMessage: 'Subject',
                            }) +
                            ' (' +
                            oneLang.lang_code +
                            ')'
                          }
                          value={version.caml_subject}
                          onChange={this.handleChange}
                        />
                      </div>
                    </div>
                    <div className="row">
                      <div className="col-xs-w36">
                        <InputTextarea
                          Text
                          name="caml_text"
                          label={
                            intl.formatMessage({
                              id: 'app.features.news.form.text',
                              defaultMessage: 'Text',
                            }) +
                            ' (' +
                            oneLang.lang_code +
                            ')'
                          }
                          value={version.caml_text}
                          onChange={this.handleChange}
                        />
                      </div>
                    </div>
                  </div>
                );
              }
              return null;
            })}
          </ResponsiveModalOrForm>
        ) : (
          <div>{this.props.loader && <CenteredLoading3Dots />}</div>
        )}
      </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    cause: state.cause,
    lang: state.lang,
  };
}

function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators({ ...actions }, dispatch),
  };
}

export default injectIntl(
  connect(
    mapStateToProps,
    mapDispatchToProps,
  )(ModifyOrCreateMedia),
);
