import React, { Component } from 'react'
import axios from 'axios'
import {
    Button,
    Col,
    DropdownItem,
    Modal,
    ModalBody,
    Nav,
    NavItem,
    NavLink,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import 'react-dates/lib/css/_datepicker.css'
import SuccessMessage from '../../common/SucessMessage'
import ErrorMessage from '../../common/ErrorMessage'
import Details from './Details'
import Items from './Items'
import Documents from './Documents'
import OrderModel from '../../models/OrderModel'
import { CalculateLineTotals, CalculateSurcharges, CalculateTotal } from '../../common/InvoiceCalculations'
import CustomFieldsForm from '../../common/CustomFieldsForm'
import InvoiceSettings from '../../common/InvoiceSettings'
import Notes from '../../common/Notes'
import DropdownMenuBuilder from '../../common/DropdownMenuBuilder'
import AddButtons from '../../common/AddButtons'
import Contacts from './Contacts'
import Emails from '../../emails/Emails'
import { icons } from '../../common/_icons'
import { translations } from '../../common/_translations'
import NoteTabs from '../../common/NoteTabs'
import Detailsm from './Detailsm'
import Contactsm from './Contactsm'
import DefaultModalHeader from '../../common/ModalHeader'
import DefaultModalFooter from '../../common/ModalFooter'
import CustomerModel from '../../models/CustomerModel'

export default class EditOrder extends Component {
    constructor (props) {
        super(props)

        const data = this.props.order ? this.props.order : null
        this.orderModel = new OrderModel(data, this.props.customers)
        this.initialState = this.orderModel.fields
        this.orderModel.task_id = this.props.task_id
        this.state = this.initialState

        this.updateData = this.updateData.bind(this)
        this.saveData = this.saveData.bind(this)
        this.setTotal = this.setTotal.bind(this)
        this.handleDelete = this.handleDelete.bind(this)
        this.buildForm = this.buildForm.bind(this)
        this.handleInput = this.handleInput.bind(this)
        this.handleAddFiled = this.handleAddFiled.bind(this)
        this.handleFieldChange = this.handleFieldChange.bind(this)
        this.updatePriceData = this.updatePriceData.bind(this)
        this.calculateTotals = this.calculateTotals.bind(this)
        this.toggleTab = this.toggleTab.bind(this)
        this.toggle = this.toggle.bind(this)
        this.handleContactChange = this.handleContactChange.bind(this)
        this.handleSurcharge = this.handleSurcharge.bind(this)
        this.calculateSurcharges = this.calculateSurcharges.bind(this)
        this.handleWindowSizeChange = this.handleWindowSizeChange.bind(this)
        this.handleTaskChange = this.handleTaskChange.bind(this)

        this.total = 0
        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.settings = user_account[0].account.settings
    }

    componentWillMount () {
        window.addEventListener('resize', this.handleWindowSizeChange)
    }

    componentDidMount () {
        /* if (!this.state.id) {
            if (Object.prototype.hasOwnProperty.call(localStorage, 'orderForm')) {
                const storedValues = JSON.parse(localStorage.getItem('orderForm'))
                this.setState({ ...storedValues }, () => console.log('new state', this.state))
            }
        } */

        if (this.props.task_id) {
            this.handleTaskChange()
        }

        if (this.props.order && this.props.order.customer_id) {
            const contacts = this.orderModel.contacts
            this.setState({ contacts: contacts })
        }
    }

    // make sure to remove the listener
    // when the component is not mounted anymore
    componentWillUnmount () {
        window.removeEventListener('resize', this.handleWindowSizeChange)
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    handleWindowSizeChange () {
        this.setState({ is_mobile: window.innerWidth <= 768 })
    }

    handleClick () {
        axios.post(`/api/tasks/products/${this.props.task_id}`, {
            products: this.state.selectedProducts
        })
            .then((response) => {
                this.setState({ success: true })
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    handleMultiSelect (e) {
        this.setState({ selectedProducts: Array.from(e.target.selectedOptions, (item) => item.value) })
    }

    handleContactChange (e) {
        const invitations = this.orderModel.buildInvitations(e.target.value, e.target.checked)
        // update the state with the new array of options
        this.setState({ invitations: invitations }, () => console.log('invitations', invitations))
    }

    handleInput (e) {
        if (e.target.name === 'customer_id') {
            const customer_data = this.orderModel.customerChange(e.target.value)

            this.setState({
                customerName: customer_data.name,
                contacts: customer_data.contacts,
                address: customer_data.address
            }, () => localStorage.setItem('orderForm', JSON.stringify(this.state)))

            if (this.settings.convert_product_currency === true) {
                const customer = new CustomerModel(customer_data.customer)
                const currency_id = customer.currencyId
                const currency = JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === currency_id)
                const exchange_rate = currency[0].exchange_rate
                this.setState({ exchange_rate: exchange_rate, currency_id: currency_id })
            }
        }

        if (e.target.name === 'tax') {
            const name = e.target.options[e.target.selectedIndex].getAttribute('data-name')
            const rate = e.target.options[e.target.selectedIndex].getAttribute('data-rate')

            this.setState({
                tax: rate,
                tax_rate_name: name
            }, () => {
                localStorage.setItem('orderForm', JSON.stringify(this.state))
                this.calculateTotals()
            })

            return
        }

        if (e.target.name === 'is_amount_discount') {
            this.setState({ is_amount_discount: e.target.value === 'true' })
            return
        }

        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value

        this.setState({
            [e.target.name]: value
        }, () => localStorage.setItem('orderForm', JSON.stringify(this.state)))
    }

    handleSurcharge (e) {
        const value = (!e.target.value) ? ('') : ((e.target.type === 'checkbox') ? (e.target.checked) : (parseFloat(e.target.value)))

        this.setState({
            [e.target.name]: value
        }, () => this.calculateSurcharges())
    }

    calculateSurcharges (x) {
        const surcharge_totals = CalculateSurcharges({ surcharges: this.state })

        this.setState({
            total_custom_values: surcharge_totals.total_custom_values,
            total_custom_tax: surcharge_totals.total_custom_tax
        }, () => this.calculateTotals())
    }

    updateData (rowData) {
        this.setState(prevState => ({
            line_items: [...prevState.line_items, rowData]
        }), () => localStorage.setItem('orderForm', JSON.stringify(this.state)))
    }

    calculateTotals () {
        const totals = CalculateTotal({ invoice: this.state })

        this.setState({
            total: totals.total,
            discount_total: totals.discount_total,
            tax_total: totals.tax_total,
            sub_total: totals.sub_total
        }, () => localStorage.setItem('orderForm', JSON.stringify(this.state)))
    }

    updatePriceData (index) {
        const line_items = this.state.line_items.slice()
        line_items[index] = CalculateLineTotals({
            currentRow: line_items[index],
            settings: this.settings,
            invoice: this.state
        })

        this.setState({ line_items: line_items }, () => localStorage.setItem('orderForm', JSON.stringify(this.state)))
    }

    handleFieldChange (line_items, row) {
        this.setState({ line_items: line_items }, () => {
            this.calculateTotals()
            this.updatePriceData(row)
        })
    }

    handleAddFiled () {
        this.setState((prevState, props) => {
            return {
                line_items: this.state.line_items.concat({
                    unit_discount: 0,
                    unit_tax: 0,
                    quantity: 0,
                    unit_price: 0,
                    product_id: 0
                })
            }
        })
    }

    handleDelete (idx) {
        const newTasks = this.state.line_items.filter((task, tIndex) => {
            return idx !== tIndex
        })

        this.setState({ line_items: newTasks })
    }

    setTotal (total) {
        this.total = total
    }

    handleTaskChange () {
        axios.get(`/api/products/tasks/${this.props.task_id}/1,2`)
            .then((r) => {
                this.setState(r.data)
                this.orderModel = new OrderModel(r.data, this.props.customers)
                const contacts = this.orderModel.contacts
                this.setState({ contacts: contacts })
            })
            .catch((e) => {
                console.warn(e)
            })
    }

    getFormData () {
        return {
            currency_id: this.state.currency_id,
            exchange_rate: this.state.exchange_rate,
            is_amount_discount: this.state.is_amount_discount,
            design_id: this.state.design_id,
            account_id: this.state.account_id,
            number: this.state.number,
            assigned_to: this.state.assigned_to,
            tax_rate: this.state.tax,
            tax_rate_name: this.state.tax_rate_name,
            task_id: this.props.task_id,
            due_date: this.state.due_date,
            customer_id: this.state.customer_id,
            line_items: this.state.line_items,
            total: this.state.total,
            balance: this.props.credit && this.props.credit.balance ? this.props.credit.balance : this.state.total,
            sub_total: this.state.sub_total,
            tax_total: this.state.tax_total,
            discount_total: this.state.discount_total,
            public_notes: this.state.public_notes,
            private_notes: this.state.private_notes,
            terms: this.state.terms,
            footer: this.state.footer,
            po_number: this.state.po_number,
            date: this.state.date,
            custom_value1: this.state.custom_value1,
            custom_value2: this.state.custom_value2,
            custom_value3: this.state.custom_value3,
            custom_value4: this.state.custom_value4,
            transaction_fee: this.state.transaction_fee,
            transaction_fee_tax: this.state.transaction_fee_tax,
            shipping_cost: this.state.shipping_cost,
            shipping_cost_tax: this.state.shipping_cost_tax,
            invitations: this.state.invitations,
            gateway_fee: this.state.gateway_fee,
            gateway_percentage: this.state.gateway_percentage
        }
    }

    saveData () {
        this.setState({ loading: true })
        this.orderModel.save(this.getFormData()).then(response => {
            if (!response) {
                this.setState({
                    loading: false,
                    showErrorMessage: true,
                    errors: this.orderModel.errors,
                    message: this.orderModel.error_message
                })
                return
            }

            if (!this.state.id) {
                const firstInvoice = response
                const allInvoices = this.props.orders
                allInvoices.push(firstInvoice)
                this.props.action(allInvoices)
                localStorage.removeItem('orderForm')
                this.setState(this.initialState)
                return
            }

            const index = this.props.orders.findIndex(order => order.id === this.state.id)
            this.props.orders[index] = response
            this.props.action(this.props.orders)
            this.setState({ loading: false })
        })
    }

    buildForm () {
        const successMessage = this.state.showSuccessMessage !== false && this.state.showSuccessMessage !== ''
            ? <SuccessMessage message={this.state.showSuccessMessage}/> : null
        const errorMessage = this.state.showErrorMessage === true
            ? <ErrorMessage message={this.state.message.length > 0 ? this.state.message : 'Something went wrong'}/> : null

        const tabs = <Nav tabs className="nav-justified disable-scrollbars">
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
                    {translations.contacts}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '3' ? 'active' : ''}
                    onClick={() => {
                        this.toggleTab('3')
                    }}>
                    {translations.items}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '4' ? 'active' : ''}
                    onClick={() => {
                        this.toggleTab('4')
                    }}>
                    {translations.notes}
                </NavLink>
            </NavItem>
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '5' ? 'active' : ''}
                    onClick={() => {
                        this.toggleTab('5')
                    }}>
                    {translations.documents}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '6' ? 'active' : ''}
                    onClick={() => {
                        this.toggleTab('6')
                    }}>
                    {translations.email}
                </NavLink>
            </NavItem>
        </Nav>

        const details = this.state.is_mobile
            ? <Detailsm hide_customer={this.state.id === null} address={this.state.address}
                customerName={this.state.customerName} handleInput={this.handleInput}
                customers={this.props.customers}
                errors={this.state.errors} order={this.state}
            /> : <Details handleInput={this.handleInput}
                customers={this.props.customers}
                errors={this.state.errors} order={this.state}
            />

        const custom = <CustomFieldsForm handleInput={this.handleInput} custom_value1={this.state.custom_value1}
            custom_value2={this.state.custom_value2}
            custom_value3={this.state.custom_value3}
            custom_value4={this.state.custom_value4}
            custom_fields={this.props.custom_fields}/>

        const contacts = this.state.is_mobile
            ? <Contactsm address={this.state.address} customerName={this.state.customerName}
                handleInput={this.handleInput} invoice={this.state}
                errors={this.state.errors}
                contacts={this.state.contacts}
                invitations={this.state.invitations}
                handleContactChange={this.handleContactChange}/>
            : <Contacts hide_customer={this.state.id === null} address={this.state.address}
                customerName={this.state.customerName}
                handleInput={this.handleInput} invoice={this.state} errors={this.state.errors}
                contacts={this.state.contacts}
                invitations={this.state.invitations} handleContactChange={this.handleContactChange}/>

        const settings = <InvoiceSettings handleSurcharge={this.handleSurcharge} settings={this.state}
            errors={this.state.errors} handleInput={this.handleInput}
            discount={this.state.discount}
            is_amount_discount={this.state.is_amount_discount}
            design_id={this.state.design_id}/>

        const items = <Items customers={this.props.customers} order={this.state} errors={this.state.errors}
            handleFieldChange={this.handleFieldChange}
            handleAddFiled={this.handleAddFiled} setTotal={this.setTotal}
            handleDelete={this.handleDelete}/>

        const notes = !this.state.is_mobile
            ? <NoteTabs private_notes={this.state.private_notes} public_notes={this.state.public_notes}
                terms={this.state.terms} footer={this.state.footer} errors={this.state.errors}
                handleInput={this.handleInput}/>
            : <Notes private_notes={this.state.private_notes} public_notes={this.state.public_notes}
                terms={this.state.terms} footer={this.state.footer} errors={this.state.errors}
                handleInput={this.handleInput}/>

        const email_editor = this.state.id
            ? <Emails model={this.orderModel} emails={this.state.emails} template="email_template_order"
                show_editor={true}
                customers={this.props.customers} entity_object={this.state} entity="order"
                entity_id={this.state.id}/> : null

        const documents = this.state.id ? <Documents order={this.state}/> : null

        const dropdownMenu = this.state.id
            ? <DropdownMenuBuilder invoices={this.props.orders} formData={this.getFormData()}
                model={this.orderModel}
                task_id={this.state.task_id}
                handleTaskChange={this.handleTaskChange}
                action={this.props.action}/> : null

        const form = this.state.is_mobile
            ? <React.Fragment>

                {tabs}

                <TabContent activeTab={this.state.activeTab} className="bg-transparent">
                    <TabPane tabId="1">
                        {details}
                        {custom}
                    </TabPane>

                    <TabPane tabId="2">
                        {contacts}
                    </TabPane>

                    <TabPane tabId="3">
                        {settings}
                        {items}
                    </TabPane>

                    <TabPane tabId="4">
                        {notes}
                    </TabPane>

                    <TabPane tabId="5">
                        {documents}
                    </TabPane>

                    <TabPane tabId="6">
                        {email_editor}
                    </TabPane>
                </TabContent>
            </React.Fragment>

            : <React.Fragment>
                <Nav tabs>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '1' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('1')
                            }}>
                            {translations.order}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('2')
                            }}>
                            {translations.email}
                        </NavLink>
                    </NavItem>
                </Nav>

                <TabContent activeTab={this.state.activeTab} className="bg-transparent">
                    <TabPane tabId="1">
                        <Row form>
                            <Col md={4}>
                                {details}
                                {custom}
                            </Col>

                            <Col md={4}>
                                {settings}
                            </Col>

                            <Col md={4}>
                                {contacts}
                            </Col>
                        </Row>
                        {items}

                        <Row form>
                            <Col md={6}>
                                {notes}
                            </Col>

                            <Col md={6}>
                                {documents}
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="2">
                        {email_editor}
                    </TabPane>
                </TabContent>
            </React.Fragment>

        return (
            <div>
                {dropdownMenu}
                {successMessage}
                {errorMessage}
                {form}

            </div>
        )
    }

    toggle () {
        this.setState({
            modalOpen: !this.state.modalOpen,
            errors: []
        }, () => {
            if (!this.state.modalOpen) {
                this.setState({
                    public_notes: '',
                    tax: null,
                    tax_rate_name: '',
                    private_notes: '',
                    transaction_fee: null,
                    shipping_cost: null,
                    gateway_fee: null,
                    gateway_percentage: false,
                    transaction_fee_tax: null,
                    shipping_cost_tax: null,
                    custom_value1: '',
                    custom_value2: '',
                    custom_value3: '',
                    custom_value4: '',
                    terms: '',
                    footer: '',
                    partial: 0,
                    partial_due_date: null,
                    invoice_id: null,
                    customer_id: null,
                    company_id: null,
                    status_id: null,
                    line_items: [],
                    invitations: []
                }, () => localStorage.removeItem('orderForm'))
            }
        })
    }

    render () {
        const form = this.buildForm()
        const { success, loading } = this.state
        const button = this.props.add === true ? <AddButtons toggle={this.toggle}/>
            : <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_order}
            </DropdownItem>
        const theme = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme'

        if (this.props.modal) {
            return (
                <React.Fragment>
                    {button}
                    <Modal isOpen={this.state.modalOpen} toggle={this.toggle} className={this.props.className}
                        size="lg">
                        <DefaultModalHeader toggle={this.toggle} title={translations.edit_order}/>

                        <ModalBody className={theme}>
                            {form}
                        </ModalBody>

                        <DefaultModalFooter show_success={this.orderModel.isEditable} toggle={this.toggle}
                            saveData={this.saveData}
                            loading={loading}/>
                    </Modal>
                </React.Fragment>
            )
        }

        return (
            <div>

                {success && <div className="alert alert-success" role="alert">
                    Products added to task successfully
                </div>}

                {form}
                <Button color="success" onClick={this.saveData}>Save</Button>
            </div>
        )
    }
}