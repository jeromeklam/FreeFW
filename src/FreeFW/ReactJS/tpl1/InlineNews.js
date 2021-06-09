import React, { Component } from 'react';
import classnames from 'classnames';
import PropTypes from 'prop-types';
import { bindActionCreators } from 'redux';
import { injectIntl, FormattedMessage } from 'react-intl';
import { connect } from 'react-redux';
import Flag from 'react-world-flags';
import { normalizedObjectModeler } from 'jsonapi-front';
import { HoverObserver, ResponsiveConfirm } from 'react-bootstrap-front';
import { CenteredLoading3Dots, deleteSuccess, showErrors } from '../ui';
import * as actions from './redux/actions';
import { GetOne as GetOneIcon, DelOne as DelOneIcon, AddOne as AddOneIcon } from '../icons';
import { ModifyOrCreateMedia } from './';

export class InlineNews extends Component {
  static propTypes = {
    cause: PropTypes.object.isRequired,
    actions: PropTypes.object.isRequired,
  };

  static getDerivedStateFromProps(props, state) {
    if (props.cauId !== state.id) {
      return { id: props.cauId };
    }
    return null;
  }

  constructor(props) {
    super(props);
    this.state = {
      id: props.cauId,
      lang_id: '368',
      caum_id: null,
      confirm: null,
      hover: false,
    };
    this.onConfirmMedia = this.onConfirmMedia.bind(this);
    this.onAddMedia = this.onAddMedia.bind(this);
    this.onUpdateMedia = this.onUpdateMedia.bind(this);
    this.onCloseMedia = this.onCloseMedia.bind(this);
    this.onMouseLeave = this.onMouseLeave.bind(this);
    this.onMouseEnter = this.onMouseEnter.bind(this);
    this.onChangeLang = this.onChangeLang.bind(this);
    this.onDeleteMedia = this.onDeleteMedia.bind(this);
  }

  componentDidMount() {
    this.props.actions.loadNews(this.state.id, true).then(result => {});
  }

  componentDidUpdate(prevProps, prevState) {
    if (prevState.id !== this.state.id) {
      this.props.actions.loadNews(this.state.id, true).then(result => {});
    }
  }

  onConfirmMedia(p_id) {
    this.setState({ confirm: p_id });
  }

  onUpdateMedia(p_id) {
    this.setState({ caum_id: p_id });
  }

  onAddMedia(p_id) {
    this.setState({ caum_id: p_id });
  }

  onDeleteMedia(p_id) {
    this.props.actions
      .delOneMedia(p_id)
      .then(result => {
        deleteSuccess();
        this.onCloseMedia();
      })
      .catch(errors => showErrors(this.props.intl, errors));
  }

  onCloseMedia() {
    this.setState({ caum_id: null, confirm: null });
    this.props.actions.loadNews(this.state.id, true).then(result => {});
  }

  onMouseLeave() {
    this.setState({ hover: 0 });
  }

  onMouseEnter(id) {
    this.setState({ hover: id });
  }

  onChangeLang(lang_id) {
    this.setState({ lang_id: lang_id });
  }

  render() {
    let news = [];
    let counter = 0;
    if (this.props.[[:FEATURE_LOWER:]].news.[[:FEATURE_MODEL:]]Media) {
      news = normalizedObjectModeler(this.props.[[:FEATURE_LOWER:]].news, '[[:FEATURE_MODEL:]]Media');
    }
    return (
      <div>
        <div className="cause-inline-news">
          {this.props.[[:FEATURE_LOWER:]].loadNewsPending || !this.props.lang.flags ? (
            <CenteredLoading3Dots />
          ) : (
            <div className="inline-list">
              <div className="row row-title row-line">
                <div className="col-xs-w30 col-last text-right">
                  <span>
                    <FormattedMessage
                      id="app.features.news.list.currentLang"
                      defaultMessage="Current lang"
                    />
                  </span>
                  {this.props.lang.flags.map(oneLang => {
                    if (oneLang.id === this.state.lang_id) {
                      return <Flag key={oneLang.id} className="ml-2" code={oneLang.lang_flag} height={24} />;
                    }
                    return '';
                  })}
                  <span className="ml-5">
                    <FormattedMessage
                      id="app.features.news.list.otherLangs"
                      defaultMessage="Other mangs"
                    />
                  </span>
                  {this.props.lang.flags.map(oneLang => {
                    if (
                      oneLang.lang_flag &&
                      oneLang.lang_flag !== '' &&
                      parseInt(oneLang.id, 10) !== parseInt(this.state.lang_id, 10)
                    ) {
                      return (
                        <Flag
                          key={oneLang.id}
                          className="ml-2 flag-btn"
                          code={oneLang.lang_flag}
                          onClick={() => this.onChangeLang(oneLang.id)}
                          height={24}
                        />
                      );
                    }
                    return '';
                  })}
                </div>
                <div className="col-xs-w6 text-right">
                  <div className="btn-group col-toolbar">
                    <button
                      className="btn btn-inline btn-primary text-light"
                      onClick={() => {
                        this.onAddMedia(0);
                      }}
                    >
                      <AddOneIcon color="light" />
                    </button>
                  </div>
                </div>
              </div>
              {news &&
                news.map(item => {
                  if (item.caum_type !== 'HTML') {
                    return null;
                  }
                  let found = false;
                  counter++;
                  return (
                    <HoverObserver
                      onMouseEnter={() => {
                        this.onMouseEnter(item.id);
                      }}
                      key={item.caum_id}
                      onMouseLeave={this.onMouseLeave}
                    >
                      <div
                        className={classnames(
                          'row row-line',
                          counter % 2 !== 1 ? 'row-odd' : 'row-even',
                        )}
                        key={item.caum_id}
                      >
                        <div className="col-xs-w36">
                          {item.versions &&
                            item.versions.map(version => {
                              if (version.lang.id === this.state.lang_id) {
                                found = true;
                                return (
                                  <div className="site-text-sample pt-2" key={version.lang.id}>
                                    <div className="mb-2">
                                      <span className="site-text-title">
                                        {version.caml_subject}
                                      </span>
                                      <div
                                        className={classnames(
                                          'btn-group ml-5 text-light float-right',
                                          this.state.hover !== item.id && 'd-none',
                                        )}
                                      >
                                        <button
                                          className="btn btn-inline btn-secondary text-light"
                                          onClick={() => {
                                            this.onUpdateMedia(item.id);
                                          }}
                                        >
                                          <GetOneIcon color="light" />
                                        </button>
                                        <button
                                          className="btn btn-inline btn-warning text-light"
                                          onClick={() => {
                                            this.onConfirmMedia(item.id);
                                          }}
                                        >
                                          <DelOneIcon color="light" />
                                        </button>
                                      </div>
                                    </div>
                                    <div
                                      dangerouslySetInnerHTML={{
                                        __html: version.caml_text,
                                      }}
                                    />
                                  </div>
                                );
                              }
                              return null;
                            })}
                          {!found && (
                            <div className="site-text-sample pt-2">
                              <div className="mb-2">
                                <div
                                  className={classnames(
                                    'btn-group ml-5 text-light float-right',
                                    this.state.hover !== item.id && 'd-none',
                                  )}
                                >
                                  <button
                                    className="btn btn-inline btn-secondary text-light"
                                    onClick={() => {
                                      this.onUpdateMedia(item.id);
                                    }}
                                  >
                                    <GetOneIcon color="light" />
                                  </button>
                                  <button
                                    className="btn btn-inline btn-warning text-light"
                                    onClick={() => {
                                      this.onConfirmMedia(item.id);
                                    }}
                                  >
                                    <DelOneIcon color="light" />
                                  </button>
                                </div>
                              </div>
                            </div>
                          )}
                        </div>
                      </div>
                    </HoverObserver>
                  );
                })}
            </div>
          )}
        </div>
        {this.state.caum_id !== null && (
          <ModifyOrCreateMedia
            cauId={this.state.id}
            caumId={this.state.caum_id}
            loader={false}
            modal={true}
            onClose={this.onCloseMedia}
          />
        )}
        {this.state.confirm > 0 && (
          <ResponsiveConfirm
            show={true}
            onClose={this.onCloseMedia}
            onConfirm={() => {
              this.onDeleteMedia(this.state.confirm);
            }}
          />
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
  )(InlineNews),
);
