import React, { Component } from 'react'
import axios from 'axios'
import {
    Button,
    Col,
    DropdownItem,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader,
    Nav,
    NavItem,
    NavLink,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import 'react-dates/lib/css/_datepicker.css'
import moment from 'moment'
import SuccessMessage from '../common/SucessMessage'
import ErrorMessage from '../common/ErrorMessage'
import AddButtons from '../common/AddButtons'
import Details from './Details'
import Contacts from './Contacts'
import Items from './Items'
import Documents from './Documents'
import Notes from '../common/Notes'
import CustomFieldsForm from '../common/CustomFieldsForm'
import InvoiceSettings from '../common/InvoiceSettings'
import { CalculateLineTotals, CalculateSurcharges, CalculateTotal } from '../common/InvoiceCalculations'
import QuoteModel from '../models/QuoteModel'
import DropdownMenuBuilder from '../common/DropdownMenuBuilder'
import Emails from '../emails/Emails'
import { icons, translations } from '../common/_icons'
import NoteTabs from '../common/NoteTabs'

class EditInvoice extends Component {
    constructor (props, context) {
        super(props, context)

        const data = this.props.invoice ? this.props.invoice : null
        this.quoteModel = new QuoteModel(data, this.props.customers)
        this.initialState = this.quoteModel.fields
        this.state = this.initialState

        this.updateData = this.updateData.bind(this)
        this.saveData = this.saveData.bind(this)
        this.setTotal = this.setTotal.bind(this)
        this.toggle = this.toggle.bind(this)
        this.handleTaskChange = this.handleTaskChange.bind(this)
        this.handleDelete = this.handleDelete.bind(this)
        this.buildForm = this.buildForm.bind(this)
        this.setRecurring = this.setRecurring.bind(this)
        this.handleInput = this.handleInput.bind(this)
        this.handleAddFiled = this.handleAddFiled.bind(this)
        this.handleFieldChange = this.handleFieldChange.bind(this)
        this.updatePriceData = this.updatePriceData.bind(this)
        this.calculateTotals = this.calculateTotals.bind(this)
        this.toggleTab = this.toggleTab.bind(this)
        this.handleContactChange = this.handleContactChange.bind(this)
        this.handleWindowSizeChange = this.handleWindowSizeChange.bind(this)
        this.handleSurcharge = this.handleSurcharge.bind(this)
        this.calculateSurcharges = this.calculateSurcharges.bind(this)

        this.total = 0
        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.settings = user_account[0].account.settings
    }

    componentWillMount () {
        window.addEventListener('resize', this.handleWindowSizeChange)
    }

    componentDidMount () {
        if (this.props.task_id) {
            this.loadInvoice()
        } else if (!this.props.invoice.id) {
            if (Object.prototype.hasOwnProperty.call(localStorage, 'quoteForm')) {
                const storedValues = JSON.parse(localStorage.getItem('quoteForm'))
                this.setState({ ...storedValues }, () => console.log('new state', this.state))
            }
        }

        if (this.props.invoice && this.props.invoice.customer_id) {
            const contacts = this.quoteModel.contacts
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
        this.setState({ width: window.innerWidth })
    }

    handleContactChange (e) {
        const invitations = this.quoteModel.buildInvitations(e.target.value, e.target.checked)
        // update the state with the new array of options
        this.setState({ invitations: invitations }, () => console.log('invitations', invitations))
    }

    handleInput (e) {
        if (e.target.name === 'customer_id') {
            const customer = this.quoteModel.customerChange(e.target.value)

            this.setState({
                customerName: customer.name,
                contacts: customer.contacts,
                address: customer.address
            }, () => localStorage.setItem('quoteForm', JSON.stringify(this.state)))
        }

        if (e.target.name === 'tax') {
            const name = e.target.options[e.target.selectedIndex].getAttribute('data-name')
            const rate = e.target.options[e.target.selectedIndex].getAttribute('data-rate')

            this.setState({
                tax: rate,
                tax_rate_name: name
            }, () => {
                localStorage.setItem('quoteForm', JSON.stringify(this.state))
                this.calculateTotals()
            })

            return
        }

        if (e.target.name === 'partial') {
            const has_partial = e.target.value.trim() !== ''
            this.setState({ has_partial: has_partial, partial: e.target.value })
            return
        }

        if (e.target.name === 'is_amount_discount') {
            this.setState({ is_amount_discount: e.target.value === 'true' })
            return
        }

        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value

        this.setState({
            [e.target.name]: value
        }, () => localStorage.setItem('quoteForm', JSON.stringify(this.state)))
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

    handleTaskChange (e) {
        axios.get(`/api/products/tasks/${this.props.task_id}/1,2`)
            .then((r) => {
                const arrLines = []
                let total = 0

                if (r.data && r.data.line_items) {
                    r.data.line_items.map((product) => {
                        const objLine = {
                            quantity: product.quantity,
                            product_id: product.product_id,
                            unit_price: product.unit_price,
                            unit_discount: product.unit_discount,
                            unit_tax: product.unit_tax,
                            order_id: r.data.id
                        }

                        total += parseFloat(product.unit_price)
                        arrLines.push(objLine)
                    })
                }

                this.setState({
                    line_items: arrLines,
                    total: total
                }, () => localStorage.setItem('quoteForm', JSON.stringify(this.state)))
            })
            .catch((e) => {
                console.warn(e)
            })
    }

    loadInvoice () {
        const url = this.props.task_id ? `/api/quotes/task/${this.props.task_id}` : `/api/quote/${this.state.id}`

        axios.get(url)
            .then((r) => {
                if (r.data) {
                    this.setState({
                        line_items: r.data.line_items,
                        due_date: moment(r.data.due_date).format('YYYY-MM-DD'),
                        po_number: r.data.po_number,
                        invoice_id: r.data.id,
                        customer_id: r.data.customer_id,
                        user_id: r.data.user_id,
                        company_id: r.data.company_id,
                        public_notes: r.data.public_notes,
                        private_notes: r.data.private_notes,
                        terms: r.data.terms,
                        footer: r.data.footer,
                        status_id: parseInt(r.data.status_id)
                    })
                }
            })
            .catch((e) => {
                console.warn(e)
            })
    }

    toggle () {
        this.setState({
            modalOpen: !this.state.modalOpen,
            errors: []
        }, () => {
            if (!this.state.modalOpen) {
                this.setState(this.initialState, () => localStorage.removeItem('quoteForm'))
            }
        })
    }

    updateData (rowData) {
        this.setState(prevState => ({
            line_items: [...prevState.line_items, rowData]
        }))
    }

    calculateTotals () {
        const totals = CalculateTotal({ invoice: this.state })

        this.setState({
            total: totals.total,
            discount_total: totals.discount_total,
            tax_total: totals.tax_total,
            sub_total: totals.sub_total
        }, () => localStorage.setItem('quoteForm', JSON.stringify(this.state)))
    }

    updatePriceData (index) {
        const line_items = this.state.line_items.slice()
        line_items[index] = CalculateLineTotals({ currentRow: line_items[index], settings: this.settings, invoice: this.state })

        this.setState({ line_items: line_items }, () => localStorage.setItem('quoteForm', JSON.stringify(this.state)))
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

    getFormData () {
        return {
            is_amount_discount: true,
            design_id: this.state.design_id,
            tax_rate: this.state.tax,
            tax_rate_name: this.state.tax_rate_name,
            is_recurring: this.state.is_recurring,
            invoice_id: this.state.id,
            task_id: this.props.task_id,
            due_date: this.state.due_date,
            customer_id: this.state.customer_id,
            company_id: this.state.company_id,
            line_items: this.state.line_items,
            total: this.state.total,
            balance: this.props.invoice && this.props.invoice.balance ? this.props.invoice.balance : this.state.total,
            po_number: this.state.po_number,
            sub_total: this.state.sub_total,
            tax_total: this.state.tax_total,
            discount_total: this.state.discount_total,
            public_notes: this.state.public_notes,
            private_notes: this.state.private_notes,
            terms: this.state.terms,
            footer: this.state.footer,
            date: this.state.date,
            partial: this.state.partial,
            partial_due_date: this.state.partial_due_date,
            recurring: this.state.recurring,
            custom_value1: this.state.custom_value1,
            custom_value2: this.state.custom_value2,
            custom_value3: this.state.custom_value3,
            custom_value4: this.state.custom_value4,
            custom_surcharge1: this.state.custom_surcharge1,
            custom_surcharge_tax1: this.state.custom_surcharge_tax1,
            custom_surcharge2: this.state.custom_surcharge2,
            custom_surcharge_tax2: this.state.custom_surcharge_tax2,
            invitations: this.state.invitations
        }
    }

    saveData () {
        this.quoteModel.save(this.getFormData()).then(response => {
            if (!response) {
                this.setState({ errors: this.quoteModel.errors, message: this.quoteModel.error_message })
                return
            }

            if (!this.state.id) {
                const firstInvoice = response
                const allInvoices = this.props.invoices
                allInvoices.push(firstInvoice)
                this.props.action(allInvoices)
                localStorage.removeItem('quoteForm')
                this.setState(this.initialState)
                return
            }

            const index = this.props.invoices.findIndex(invoice => invoice.id === this.state.id)
            this.props.invoices[index] = response
            this.props.action(this.props.invoices)
        })
    }

    setRecurring (recurring) {
        this.setState({ recurring: recurring })
    }

    buildForm () {
        const successMessage = this.state.showSuccessMessage !== false && this.state.showSuccessMessage !== ''
            ? <SuccessMessage message={this.state.showSuccessMessage}/> : null
        const errorMessage = this.state.showErrorMessage === true
            ? <ErrorMessage message="Something went wrong"/> : null

        const tabs = <Nav tabs>
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

        const isMobile = this.state.width <= 500

        const details = <Details handleInput={this.handleInput}
            customers={this.props.customers}
            errors={this.state.errors}
            quote={this.state}
            address={this.state.address} customerName={this.state.customerName}/>

        const custom = <CustomFieldsForm handleInput={this.handleInput} custom_value1={this.state.custom_value1}
            custom_value2={this.state.custom_value2}
            custom_value3={this.state.custom_value3}
            custom_value4={this.state.custom_value4}
            custom_fields={this.props.custom_fields}/>

        const contacts = <Contacts errors={this.state.errors} contacts={this.state.contacts}
            invitations={this.state.invitations} handleContactChange={this.handleContactChange}/>

        const settings = <InvoiceSettings handleSurcharge={this.handleSurcharge} settings={this.state}
            errors={this.state.errors} handleInput={this.handleInput}
            discount={this.state.discount} is_amount_discount={this.state.is_amount_discount} design_id={this.state.design_id}/>

        const items = <Items quote={this.state} errors={this.state.errors} handleFieldChange={this.handleFieldChange}
            handleAddFiled={this.handleAddFiled} setTotal={this.setTotal}
            handleDelete={this.handleDelete}
        />

        const notes = !isMobile
            ? <NoteTabs private_notes={this.state.private_notes} public_notes={this.state.public_notes}
                terms={this.state.terms} footer={this.state.footer} errors={this.state.errors}
                handleInput={this.handleInput}/>
            : <Notes private_notes={this.state.private_notes} public_notes={this.state.public_notes}
                terms={this.state.terms} footer={this.state.footer} errors={this.state.errors}
                handleInput={this.handleInput}/>

        const documents = this.state.id ? <Documents invoice={this.state}/> : null

        const email_editor = this.state.id
            ? <Emails emails={this.state.emails} template="email_template_quote" show_editor={true} entity="quote"
                entity_id={this.state.id}/> : null

        const dropdownMenu = this.state.id
            ? <DropdownMenuBuilder invoices={this.props.invoices}
                formData={this.getFormData()}
                model={this.quoteModel}
                handleTaskChange={this.handleTaskChange}
                action={this.props.action}/> : null

        const form = isMobile
            ? <React.Fragment>

                {tabs}

                <TabContent activeTab={this.state.activeTab}>
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
                            Quote
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

                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Row form>
                            <Col md={6}>
                                {details}
                                {custom}
                            </Col>

                            <Col md={6}>
                                {contacts}
                                {settings}
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

    render () {
        const form = this.buildForm()
        const { success } = this.state
        const button = this.props.add === true ? <AddButtons toggle={this.toggle}/>
            : <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_quote}</DropdownItem>

        if (this.props.modal) {
            return (
                <React.Fragment>
                    {button}
                    <Modal isOpen={this.state.modalOpen} toggle={this.toggle} className={this.props.className}
                        size="lg">
                        <ModalHeader toggle={this.toggle}>
                            Quote
                        </ModalHeader>

                        <ModalBody>
                            {form}
                        </ModalBody>
                        <ModalFooter>
                            <Button color="secondary" onClick={this.toggle}>{translations.close}</Button>
                            <Button color="success" onClick={this.saveData}>{translations.save}</Button>
                        </ModalFooter>
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
                <Button color="success" onClick={this.saveData}>{translations.save}</Button>
            </div>
        )
    }
}

export default EditInvoice
