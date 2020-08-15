import React from 'react'
import { Modal, ModalBody, Nav, NavItem, NavLink, TabContent, TabPane } from 'reactstrap'
import AddButtons from '../common/AddButtons'
import SettingsForm from './SettingsForm'
import DetailsForm from './DetailsForm'
import CustomFieldsForm from '../common/CustomFieldsForm'
import Notes from '../common/Notes'
import ExpenseModel from '../models/ExpenseModel'
import { translations } from '../common/_translations'
import DefaultModalHeader from '../common/ModalHeader'
import DefaultModalFooter from '../common/ModalFooter'

class AddExpense extends React.Component {
    constructor (props) {
        super(props)

        this.expenseModel = new ExpenseModel(null, this.props.customers)
        this.initialState = this.expenseModel.fields
        this.state = this.initialState

        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.handleInput = this.handleInput.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'expenseForm')) {
            const storedValues = JSON.parse(localStorage.getItem('expenseForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    handleInput (e) {
        if (e.target.name === 'currency_id') {
            const exchange_rate = this.expenseModel.getExchangeRateForCurrency(e.target.value)
            this.setState({ exchange_rate: exchange_rate })
        }

        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value

        this.setState({
            [e.target.name]: value
        }, () => localStorage.setItem('expenseForm', JSON.stringify(this.state)))
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
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
        this.setState({ loading: true })

        const data = {
            amount: this.state.amount,
            customer_id: this.state.customer_id,
            currency_id: this.state.currency_id,
            exchange_rate: this.state.exchange_rate,
            company_id: this.state.company_id,
            public_notes: this.state.public_notes,
            private_notes: this.state.private_notes,
            transaction_reference: this.state.transaction_reference,
            category_id: this.state.category_id,
            date: this.state.date,
            payment_type_id: this.state.payment_type_id,
            include_documents: this.state.include_documents,
            create_invoice: this.state.create_invoice,
            payment_date: this.state.payment_date,
            custom_value1: this.state.custom_value1,
            custom_value2: this.state.custom_value2,
            custom_value3: this.state.custom_value3,
            custom_value4: this.state.custom_value4
        }

        this.expenseModel.save(data).then(response => {
            if (!response) {
                this.setState({ errors: this.expenseModel.errors, message: this.expenseModel.error_message })
                return
            }
            this.props.expenses.push(response)
            this.props.action(this.props.expenses)
            localStorage.removeItem('expenseForm')
            this.setState(this.initialState)
        })
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState(this.initialState, () => localStorage.removeItem('expenseForm'))
            }
        })
    }

    render () {
        const { message, loading } = this.state
        const theme = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme'

        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <DefaultModalHeader toggle={this.toggle} title={translations.add_expense}/>

                    <ModalBody className={theme}>

                        {message && <div className="alert alert-danger" role="alert">
                            {message}
                        </div>}

                        <Nav tabs>
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '1' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('1')
                                    }}>
                                    {translations.details}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '2' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('2')
                                    }}>
                                    {translations.settings}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '3' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('3')
                                    }}>
                                    {translations.notes}
                                </NavLink>
                            </NavItem>
                        </Nav>
                        <TabContent activeTab={this.state.activeTab}>
                            <TabPane tabId="1">
                                <DetailsForm errors={this.state.errors}
                                    expense={this.state}
                                    handleInput={this.handleInput}
                                    customers={this.props.customers} companies={this.props.companies}/>

                                <CustomFieldsForm handleInput={this.handleInput}
                                    custom_value1={this.state.custom_value1}
                                    custom_value2={this.state.custom_value2}
                                    custom_value3={this.state.custom_value3}
                                    custom_value4={this.state.custom_value4}
                                    custom_fields={this.props.custom_fields}/>

                            </TabPane>

                            <TabPane tabId="2">
                                <SettingsForm expense={this.state} errors={this.state.errors}
                                    handleInput={this.handleInput}/>
                            </TabPane>

                            <TabPane tabId="3">
                                <Notes errors={this.state.errors} public_notes={this.state.public_notes}
                                    private_notes={this.state.private_notes} handleInput={this.handleInput}/>
                            </TabPane>
                        </TabContent>
                    </ModalBody>

                    <DefaultModalFooter show_success={true} toggle={this.toggle}
                        saveData={this.handleClick.bind(this)}
                        loading={loading}/>
                </Modal>
            </React.Fragment>
        )
    }
}

export default AddExpense
