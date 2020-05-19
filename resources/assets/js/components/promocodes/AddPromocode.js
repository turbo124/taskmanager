import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label } from 'reactstrap'
import axios from 'axios'
import AddButtons from '../common/AddButtons'
import { translations } from '../common/_icons'
import Datepicker from '../common/Datepicker'

export default class AddPromocode extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            scope: 'order',
            scope_value: '',
            description: '',
            reward: '',
            quantity: 0,
            amount: 1,
            expiry_date: '',
            loading: false,
            errors: [],
            values: []
        }

        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'promocodeForm')) {
            const storedValues = JSON.parse(localStorage.getItem('promocodeForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value
        }, () => localStorage.setItem('promocodeForm', JSON.stringify(this.state)))
    }

    handleVariations (values) {
        this.setState({ values: values }, () => console.log('values', this.state.values))
    }

    hasErrorFor (field) {
        return !!this.state.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.state.errors[field][0]}</strong>
                </span>
            )
        }
    }

    handleClick () {
        axios.post('/api/promocodes', {
            scope: this.state.scope,
            scope_value: this.state.scope_value,
            description: this.state.description,
            reward: this.state.reward,
            quantity: this.state.quantity,
            amount: this.state.amount,
            expiry_date: this.state.expiry_date
        })
            .then((response) => {
                console.log('response', response)
                // this.props.promocodes.push(response)
                // this.props.action(this.props.promocodes)
                localStorage.removeItem('promocodeForm')
                this.setState({
                    scope: 'order',
                    scope_value: 0,
                    description: '',
                    reward: 0,
                    quantity: 1,
                    amount: 0,
                    expiry_date: ''
                })
                this.toggle()
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState({
                    name: '',
                    target_url: ''
                }, () => localStorage.removeItem('promocodeForm'))
            }
        })
    }

    render () {
        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.add_promocode}
                    </ModalHeader>
                    <ModalBody>
                        <FormGroup>
                            <Label for="name">{translations.scope} <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('scope') ? 'is-invalid' : ''} type="select" name="scope"
                                id="name" value={this.state.scope}
                                onChange={this.handleInput.bind(this)}>
                                <option value="order">Order</option>
                                <option value="product">Product</option>
                            </Input>
                            {this.renderErrorFor('scope')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="name">{translations.scope_value}<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('scope_value') ? 'is-invalid' : ''} type="text" name="scope_value"
                                id="scope_value" value={this.state.scope_value} placeholder={translations.scope_value}
                                onChange={this.handleInput.bind(this)} />
                            {this.renderErrorFor('scope_value')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="name">{translations.description}<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('description') ? 'is-invalid' : ''} type="text" name="description"
                                id="scope_value" value={this.state.description} placeholder={translations.description}
                                onChange={this.handleInput.bind(this)} />
                            {this.renderErrorFor('description')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="name">{translations.quantity}<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('description') ? 'is-invalid' : ''} type="text" name="quantity"
                                id="scope_value" value={this.state.quantity} placeholder={translations.quantity}
                                onChange={this.handleInput.bind(this)} />
                            {this.renderErrorFor('quantity')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="name">{translations.amount_to_create}<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('amount') ? 'is-invalid' : ''} type="text" name="amount"
                                id="scope_value" value={this.state.amount} placeholder={translations.amount_to_create}
                                onChange={this.handleInput.bind(this)} />
                            {this.renderErrorFor('amount')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="name">{translations.redeemable}<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('reward') ? 'is-invalid' : ''} type="text" name="reward"
                                id="reward" value={this.state.reward} placeholder={translations.redeemable}
                                onChange={this.handleInput.bind(this)} />
                            {this.renderErrorFor('reward')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="date">{translations.expiry_date}(*):</Label>
                            <Datepicker name="expiry_date" date={this.state.expiry_date} handleInput={this.handleInput.bind(this)}
                                className={this.hasErrorFor('expiry_date') ? 'form-control is-invalid' : 'form-control'}/>
                            {this.renderErrorFor('expiry_date')}
                        </FormGroup>
                    </ModalBody>

                    <ModalFooter>
                        <Button color="primary" onClick={this.handleClick.bind(this)}>{translations.save}</Button>
                        <Button color="secondary" onClick={this.toggle}>{translations.close}</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}
