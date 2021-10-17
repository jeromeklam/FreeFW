import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { injectIntl } from 'react-intl';
import * as actions from './redux/actions';
import { withRouter } from 'react-router-dom';
import { propagateModel } from '../../common';
import { KalaLoader, createSuccess, modifySuccess, showErrors } from '../ui';
import Form from './Form';
import { getActionsButtons } from './';

/**
 * Classe qui gère la saisie
 * Création Suppression Modification
 */
export class Input extends Component {
  static propTypes = {
    [[:FEATURE_CAMEL:]]: PropTypes.object.isRequired,
    actions: PropTypes.object.isRequired,
    editions: PropTypes.array,
    id: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    loader: PropTypes.bool,
    onClose: PropTypes.func,
    picker: PropTypes.bool,
  };
  static defaultProps = {
    id: 0,
    loader: true,
    onClose: null,
    editions: {},
    picker: false,
  };

  /**
   * Dois-je updater le state ?
   *
   * @param {Object} props Nouveaux paramètres
   * @param {Object} state State actuel
   */
  static getDerivedStateFromProps(props, state) {
    if (props.id !== state.id) {
      return { id: props.id };
    }
    return null;
  }

  /**
   * Constructeur
   *
   * Initialisation du state local et lien des méthodes locales au contexte courant
   * L'objet est garder dans le state local pour travailler et ne sera donc pas impacté par les modifications externes.
   *
   * @param {String} id       Identifiant de l'objet
   * @param {Array}  editions Editions disponibles pour cet objet
   */
  constructor(props) {
    super(props);
    this.state = {
      id: props.id || 0,
      item: null,
      editions: props.editions,
      loading: true,
    };
    this.onSubmit = this.onSubmit.bind(this);
    this.onCancel = this.onCancel.bind(this);
    this.onPrint = this.onPrint.bind(this);
    this.onPrevious = this.onPrevious.bind(this);
    this.onNext = this.onNext.bind(this);
  }

  /**
   * Au chargement de la saisie
   *
   * On va demander la lecture de l'enregistrement
   * En cas de création on recevra un élément vide prérempli.
   * On modification on recevra l'objet à modifier
   */
  componentDidMount() {
    this.setState({ loading: true });
    this.props.actions.loadOne(this.state.id).then(item => {
      this.setState({ item: item, loading: false });
    }).catch((errors) => {
      showErrors(this.props.intl, errors);
      this.setState({ loading: false });
      this.props.onClose();
    });
  }

  /**
   * Mise à jour, je recharge
   *
   * @param {Object} prevProps Paramètres précédents
   */
  componentDidUpdate(prevProps) {
    if (prevProps.id !== this.props.id) {
      this.setState({ loading: true });
      this.props.actions.loadOne(this.state.id).then(item => {
        this.setState({ item: item, loading: false });
      }).catch((errors) => {
        showErrors(this.props.intl, errors);
        this.setState({ loading: false });
        this.props.onClose();
      });
    }
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
   * Annulation, on ferme la fenêtre
   */
  onCancel(ev) {
    if (ev) {
      ev.preventDefault();
      ev.stopPropagation();
    }
    this.props.onClose();
  }

  /**
   * Sauvegarde
   *
   * @param {Object}  datas L'objet à modifier sous forme d'objet json simple
   * @param {Boolean} close Doit-on ferme la fenêtre on rester en saisie ?
   */
  onSubmit(datas = {}, close = true) {
    if (this.state.id > 0) {
      // Modification
      this.props.actions
        .updateOne(this.state.id, datas)
        .then(item => {
          // Message de confirmation
          modifySuccess();
          if (this.props.onClose && close) {
            this.props.onClose();
          } else {
            // On remet dans le state le nouvel élément retourné
            this.setState({ item: item });
          }
        })
        .catch(errors => {
          // Affichage des erreurs
          showErrors(this.props.intl, errors);
        });
    } else {
      // Création
      this.props.actions
        .createOne(datas)
        .then(item => {
          // Message de confirmation
          createSuccess();
          if (this.props.onClose && close) {
            this.props.onClose();
          } else {
            // On met dans le state l'élément enregistré, on passe en modification
            this.setState({ id: item.id, item: item });
          }
        })
        .catch(errors => {
          // Affichage des erreurs
          showErrors(this.props.intl, errors);
        });
    }
  }

  /**
   * Impression de l'objet en cours
   *
   * Par défaut la première édition disponible
   */
  onPrint(ediId = 0) {
    let idx = this.props.editions.findIndex(elem => elem.id === ediId);
    if (idx < 0) {
      idx = 0;
    }
    this.props.actions.printOne(this.state.id, this.props.editions[idx].id);
  }

  /**
   * render
   */
  render() {
    const { item, id, loading } = this.state;
    return (
      <div className=".[[:FEATURE_CAMEL:]]-modify global-card">
        {!item ? loading ? (
          <KalaLoader portal={true} />
        ) : (
          <></>
        ) : (
          <div>
            {this.props.[[:FEATURE_CAMEL:]].loadOnePending && <KalaLoader portal={true} />}
            {item && (
              <Form
                item={item}
                modify={id > 0}
                tab={this.props.[[:FEATURE_CAMEL:]].tab}
                tabs={this.props.[[:FEATURE_CAMEL:]].tabs}
                errors={id > 0 ? this.props.[[:FEATURE_CAMEL:]].updateOneError : this.props.[[:FEATURE_CAMEL:]].createOneError}
                actionsButtons={id > 0 ? getActionsButtons(this) : []}
                onSubmit={this.onSubmit}
                onCancel={this.onCancel}
                onPrevious={id > 0 && !this.props.picker && this.onPrevious}
                onNext={id > 0 && !this.props.picker && this.onNext}
                onClose={this.props.onClose}
                loader={
                  this.props.[[:FEATURE_CAMEL:]].loadOnePending ||
                  this.props.[[:FEATURE_CAMEL:]].createOnePending ||
                  this.props.[[:FEATURE_CAMEL:]].updateOnePending
                }
              />
            )}
          </div>
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
    [[:FEATURE_CAMEL:]]: state.[[:FEATURE_CAMEL:]],
  };
}

/**
 * Quelles actions du store ai-je besoin ?
 * Ces actions seront accessibles via this.props.actions.<action>
 */
function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators({ ...actions, propagateModel }, dispatch),
  };
}

/**
 * Injection de l'intl, du ruteur et de redux pour connecter le store
 */
export default injectIntl(withRouter(connect(mapStateToProps, mapDispatchToProps)(Input)));
