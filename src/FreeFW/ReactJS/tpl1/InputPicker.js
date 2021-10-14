import React, { Component } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';
import { freeAssoApi } from '../../common';
import { More, Zoom, DelOne } from '../icons';
import { InputPicker as DefaultInputPicker, ButtonPicker as DefaultButtonPicker } from '../ui';
import { Search, Input, displayItemPicker, getPickerDisplay } from './';

/**
 * Gestion du champ de saisie assisté
 * avec recherche
 * auto-complète
 * suppression
 * et zoom
 * Pour le moment, il n'y a pas de création depuis ce champ
 */
export default class InputPicker extends Component {
  static propTypes = {
    conditions: PropTypes.array,
    name: PropTypes.string.isRequired,
    item: PropTypes.object,
    label: PropTypes.string.isRequired,
    labelTop: PropTypes.bool,
    required: PropTypes.bool,
    disabled: PropTypes.bool,
    list: PropTypes.element.isRequired,
    onChange: PropTypes.func.isRequired,
    onAdd: PropTypes.func,
    addIcon: PropTypes.element,
    error: PropTypes.element,
    pickerButton: PropTypes.bool,
  };
  static defaultProps = {
    conditions: [],
    item: null,
    required: false,
    disabled: false,
    labelTop: true,
    onAdd: null,
    addIcon: null,
    error: false,
    pickerButton: true,
  };

  /**
   * Met à jour le champ en fonction d'un changement dans le store par rapport au modèle affiché
   */
  static getDerivedStateFromProps(props, state) {
    if (props.item !== state.item || props.conditions !== state.conditions) {
      let value = null;
      let display = '';
      let zoomBtn = false;
      if (props.item) {
        value = props.item.id || '';
        display = getPickerDisplay(props.item);
        if (props.pickerButton && props.item.id > 0) {
          zoomBtn = true;
        }
      }
      return {
        item: props.item,
        value: value,
        display: display,
        zoomButton: zoomBtn,
        conditions: props.conditions
      };
    }
    return null;
  }
  
  /**
   * Constructor
   
   * @param {Object} props
   */
  constructor(props) {
    super(props);
    let value = '';
    let display = '';
    let zoomBtn = false;
    if (props.item) {
      value = props.item.id || '';
      display = getPickerDisplay(props.item);
      if (props.pickerButton && props.item.id > 0) {
        zoomBtn = true;
      }
    }
    this.state = {
      search: false,
      item: props.item || null,
      conditions: props.conditions,
      list: [],
      value: value,
      display: display,
      autocomplete: false,
      source: false,
      zoom: false,
      zoomButton: zoomBtn,
    };
    this.onMore = this.onMore.bind(this);
    this.onZoom = this.onZoom.bind(this);
    this.onClear = this.onClear.bind(this);
    this.onZoom = this.onZoom.bind(this);
    this.onChange = this.onChange.bind(this);
    this.onSelect = this.onSelect.bind(this);
    this.onCloseMore = this.onCloseMore.bind(this);
  }

  /**
   * Changement de valeurs du champ
   */
  onChange(event) {
    if (this.state.source) {
      this.state.source.cancel();
    }
    const CancelToken = axios.CancelToken;
    const source = CancelToken.source();
    const headers = {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      cancelToken: source.token,
    };
    const search = '' + event.target.value;
    this.setState({ display: search, loading: true, cancel: source });
    if (search.length >= 2) {
      freeAssoApi
        .get(process.env.REACT_APP_BO_URL + '/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SNAKE:]]/autocomplete/' + search, {
          headers: headers,
        })
        .then(result => {
          if (result && result.data && result.data.data) {
            const list = result.data.data;
            if (Array.isArray(list) && list.length === 1) {
              this.props.onChange({
                target: { name: this.props.name, value: list[0].id, type: '[[:FEATURE_MODEL:]]' },
              });
            } else {
              this.setState({ list: list, loading: false });
            }
          } else {
            this.setState({ list: [], loading: false });
          }
        })
        .catch(err => {
          this.setState({ list: [], loading: false });
        });
    }
  }

  /**
   * Ouverture de la recherche
   */
  onMore() {
    this.setState({ search: true, autocomplete: false });
  }

  /**
   * Quand on a un modèle on peut zoomer
   */
  onZoom() {
    this.setState({ zoom: true });
  }

  /**
   * Le champ est vidé
   */
  onClear() {
    this.setState({ autocomplete: false });
    this.props.onChange({
      target: { name: this.props.name, value: null, type: '[[:FEATURE_MODEL:]]' },
    });
  }

  /**
   * Gestion de la sélection d'un modèle depuis la recherche
   */
  onSelect(item) {
    console.log(item);
    this.setState({ search: false, autocomplete: false, list: [] });
    if (item) {
      this.props.onChange({
        target: { name: this.props.name, value: item.id, type: '[[:FEATURE_MODEL:]]' },
      });
    }
  }

  /**
   * Gestion de la fermeture de la recherche sans sélection
   */
  onCloseMore() {
    this.setState({ search: false, zoom: false });
  }

  render() {
    return (
      <div className=".[[:FEATURE_CAMEL:]]-picker">
        {!this.state.zoomButton ? (
          <div className=".[[:FEATURE_CAMEL:]]-input-picker">
            <DefaultInputPicker
              {...this.props}
              name={this.props.name}
              label={this.props.label}
              labelTop={this.props.labelTop || false}
              value={this.state.value || ''}
              list={this.props.list || this.state.list}
              display={this.state.display}
              onChange={this.props.onFineChange || this.onChange}
              onMore={this.onMore}
              error={this.props.error}
              onSelect={this.onSelect}
              required={this.props.required || false}
              pickerId="[[:FEATURE_ID_FIELD:]]"
              pickerDisplay={displayItemPicker}
              conditions={this.state.conditions || {}}
              moreIcon={<More className="text-secondary" size={0.8} />}
              zoomIcon={<Zoom className="text-secondary" size={0.8} />}
              clearIcon={<DelOne className="text-warning" size={0.8} />}
              onZoom={this.props.simple && !this.props.disabled ? null : this.onZoom}
              onClear={this.props.simple && this.props.disabled ? null : this.onClear}
              pickerUp={this.props.pickerButton ? false : true}
            />
            <Search
              title={this.props.label}
              show={this.state.search}
              conditions={this.state.conditions || {}}
              onSelect={this.onSelect}
              onClose={this.onCloseMore}
            />
          </div>
        ) : (
          <div className=".[[:FEATURE_CAMEL:]]-button-picker">
            <DefaultButtonPicker
              label={this.props.label}
              labelTop={this.props.labelTop || false}
              value={this.state.value}
              display={this.state.display}
              disabled={this.props.disabled || false}
              required={this.props.required || false}
              clearIcon={<DelOne className="text-warning" size={0.8} />}
              onZoom={this.onZoom}
              onClear={this.onClear}
            />
          </div>
        )}
        {this.state.zoom && (
          <Input
            loader={false}
            modal={true}
            id={this.state.item.id}
            onClose={this.onCloseMore}
            picker={true}
          />
        )}
      </div>
    );
  }
}
