import React, { Component } from 'react'
import {
    DropdownItem,
    Dropdown,
    DropdownToggle, DropdownMenu
} from 'reactstrap'
import axios from 'axios'
import SuccessMessage from './SucessMessage'
import ErrorMessage from './ErrorMessage'
import { icons, translations } from './_icons'

export default class DropdownMenuBuilder extends Component {
    constructor (props, context) {
        super(props, context)
        this.state = {
            dropdownOpen: false,
            showSuccessMessage: false,
            showErrorMessage: false
        }

        this.model = this.props.model
        this.toggleMenu = this.toggleMenu.bind(this)
        this.changeStatus = this.changeStatus.bind(this)
    }

    downloadPdf (response, id) {
        const linkSource = `data:application/pdf;base64,${response.data.data}`
        const downloadLink = document.createElement('a')
        const fileName = `invoice_${id}.pdf`

        downloadLink.href = linkSource
        downloadLink.download = fileName
        downloadLink.click()
    }

    changeStatus (action) {
        if (!this.props.model.fields.id) {
            return false
        }

        const data = this.props.formData
        axios.post(`${this.props.model.url}/${this.props.model.fields.id}/${action}`, data)
            .then((response) => {
                let message = `${action} completed successfully`

                if (action === 'download') {
                    this.downloadPdf(response, this.props.model.fields.id)
                    message = translations.downloaded
                }

                if (action === 'clone_to_invoice') {
                    // this.props.invoices.push(response.data)
                    // this.props.action(this.props.invoices)
                    message = `Invoice was cloned successfully. Invoice ${response.data.number} has been created`
                }

                if (action === 'clone_to_quote') {
                    // this.props.invoices.push(response.data)
                    // this.props.action(this.props.invoices)
                    message = `Quote was created successfully. Quote ${response.data.number} has been created`
                }

                if (action === 'clone_to_credit') {
                    // this.props.invoices.push(response.data)
                    // this.props.action(this.props.invoices)
                    message = `Credit was created successfully. Credit ${response.data.number} has been created`
                }

                if (action === 'clone_to_order') {
                    // this.props.invoices.push(response.data)
                    // this.props.action(this.props.invoices)
                    message = `Order was created successfully. Order ${response.data.number} has been created`
                }

                if (action === 'clone_to_expense') {
                    // this.props.invoices.push(response.data)
                    // this.props.action(this.props.invoices)
                    message = `Expense was created successfully. Expense ${response.data.number} has been created`
                }

                if (action === 'approve') {
                    message = `The ${this.props.model.entity} ${translations.approved}`
                }

                if (action === 'mark_sent') {
                    message = `The ${this.props.model.entity} ${translations.sent}`
                }

                if (action === 'mark_paid') {
                    message = `The ${this.props.model.entity} ${translations.paid}.`
                }

                if (action === 'cancel') {
                    message = `The ${this.props.model.entity} ${translations.cancelled_invoice}`
                }

                if (action === 'reverse') {
                    message = `The ${this.props.model.entity} ${translations.reversed_invoice}`
                }

                if (action === 'refund') {
                    message = `The ${this.props.model.entity} ${translations.refunded}`
                }

                if (action === 'email') {
                    message = translations.emailed
                }

                this.setState({
                    showSuccessMessage: message,
                    showErrorMessage: false
                })
            })
            .catch((error) => {
                this.setState({ showErrorMessage: true })
                console.warn(error)
            })
    }

    toggleMenu (event) {
        this.setState({
            dropdownOpen: !this.state.dropdownOpen
        })
    }

    getOption (option) {
        switch (option) {
            case 'pdf':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('download')}><i
                        className={`fa ${icons.download} mr-2`}/>{translations.download}</DropdownItem>

            case 'email':
                return <DropdownItem className="primary" onClick={() => this.changeStatus('email')}>
                    <i className={`fa ${icons.email} mr-2`}/>{translations.send_email}
                </DropdownItem>

            case 'approve':
                return <DropdownItem className="primary" onClick={() => this.changeStatus('approve')}>
                    <i className={`fa ${icons.approve} mr-2`}/>{translations.approve}
                </DropdownItem>

            case 'markSent':
                return <DropdownItem onClick={() => this.changeStatus('mark_sent')}>
                    <i className={`fa ${icons.mark_sent} mr-2`}/>{translations.mark_sent}
                </DropdownItem>

            case 'cloneToInvoice':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('clone_to_invoice')}>
                    <i className={`fa ${icons.clone} mr-2`}/> Clone Invoice
                </DropdownItem>

            case 'cloneToQuote':
                return <DropdownItem className="primary" onClick={() => this.changeStatus('clone_to_quote')}>
                    <i className={`fa ${icons.clone} mr-2`}/> Clone To Quote
                </DropdownItem>

            case 'cloneCreditToQuote':
                return <DropdownItem className="primary" onClick={() => this.changeStatus('clone_credit_to_quote')}>
                    <i className={`fa ${icons.clone} mr-2`}/> Clone Credit To Quote
                </DropdownItem>

            case 'cloneInvoiceToQuote':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('clone_invoice_to_quote')}>
                    <i className={`fa ${icons.clone} mr-2`}/> Clone To Quote
                </DropdownItem>

            case 'dispatch':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('dispatch')}>
                    <i className={`fa ${icons.clone} mr-2`}/> Dispatch
                </DropdownItem>

            case 'cloneOrderToInvoice':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('clone_order_to_invoice')}>
                    <i className={`fa ${icons.clone} mr-2`}/> Clone Order To Invoice
                </DropdownItem>

            case 'cloneOrderToQuote':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('clone_order_to_quote')}>
                    <i className={`fa ${icons.clone} mr-2`}/> Clone Order To Quote
                </DropdownItem>

            case 'cloneQuoteToInvoice':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('clone_quote_to_invoice')}>
                    <i className={`fa ${icons.clone} mr-2`}/> Clone To Invoice
                </DropdownItem>

            case 'cloneToCredit':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('clone_to_credit')}>
                    <i className={`fa ${icons.clone} mr-2`}/>Clone Credit
                </DropdownItem>

            case 'clone_to_order':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('clone_to_order')}>
                    <i className={`fa ${icons.clone} mr-2`}/>Clone Order
                </DropdownItem>

            case 'markPaid':
                return <DropdownItem color="primary" onClick={() => this.changeStatus('mark_paid')}>
                    <i className={`fa ${icons.mark_paid} mr-2`}/>{translations.mark_paid}
                </DropdownItem>

            case 'cloneExpense':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('clone_to_expense')}>
                    <i className={`fa ${icons.clone} mr-2`}/> Clone Expense
                </DropdownItem>

            case 'delete':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('delete')}>
                    <i className={`fa ${icons.delete} mr-2`}/>{translations.delete}</DropdownItem>

            case 'cancel':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('cancel')}>
                    <i className={`fa ${icons.cancel} mr-2`}/> {translations.cancel}
                </DropdownItem>

            case 'reverse':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('reverse')}>
                    <i className={`fa ${icons.reverse} mr-2`}/>{translations.reverse}
                </DropdownItem>

            case 'archive':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('archive')}>
                    <i className={`fa ${icons.archive} mr-2`}/>{translations.archive}
                </DropdownItem>

            case 'getProducts':
                return <DropdownItem className="primary" onClick={this.props.handleTaskChange}>
                    <i className={`fa ${icons.products} mr-2`}/>Get Products
                </DropdownItem>

            case 'refund':
                return <DropdownItem className="primary"
                    onClick={() => this.changeStatus('refund')}>{translations.refund}</DropdownItem>
        }
    }

    render () {
        const menuOptions = this.props.model.buildDropdownMenu()

        const actions = []

        menuOptions.forEach((element) => {
            actions.push(this.getOption(element))
        })

        const successMessage = this.state.showSuccessMessage !== false && this.state.showSuccessMessage !== ''
            ? <SuccessMessage message={this.state.showSuccessMessage}/> : null
        const errorMessage = this.state.showErrorMessage === true
            ? <ErrorMessage message="Something went wrong"/> : null

        return (
            <React.Fragment>
                <Dropdown isOpen={this.state.dropdownOpen} toggle={this.toggleMenu}>
                    <DropdownToggle caret>
                        Actions
                    </DropdownToggle>

                    <DropdownMenu className="text-white">
                        {actions}
                    </DropdownMenu>
                </Dropdown>
                {successMessage}
                {errorMessage}
            </React.Fragment>
        )
    }
}
