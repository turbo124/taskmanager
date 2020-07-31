import React, { Component } from 'react'
import axios from 'axios'
import {
    Form,
    FormGroup,
    Label,
    CustomInput,
    TabContent,
    TabPane,
    Nav,
    NavItem,
    NavLink,
    Card,
    CardHeader,
    CardBody,
    Button,
    Modal, ModalBody, ModalFooter, ModalHeader

} from 'reactstrap'
import { toast } from 'react-toastify'
import { translations } from '../common/_translations'
import BlockButton from '../common/BlockButton'
import { icons } from '../common/_icons'

class ModuleSettings extends Component {
    constructor (props) {
        super(props)
        this.state = {
            id: localStorage.getItem('account_id'),
            activeTab: '1',
            showConfirm: false,
            modules: Object.prototype.hasOwnProperty.call(localStorage, 'modules') ? JSON.parse(localStorage.getItem('modules')) : {
                recurringInvoices: false,
                credits: false,
                orders: false,
                leads: false,
                deals: false,
                products: false,
                invoices: false,
                payments: false,
                quotes: false,
                expenses: false,
                events: false,
                customers: true,
                companies: true,
                projects: false,
                cases: false,
                tasks: false,
                recurringExpenses: false,
                recurringTasks: false
            },
            moduleTypes: [
                {
                    id: 'recurringInvoices',
                    value: 1,
                    label: translations.recurring_invoices,
                    isChecked: false
                },
                {
                    id: 'recurringQuotes',
                    value: 1,
                    label: translations.recurring_quotes,
                    isChecked: false
                },
                {
                    id: 'credits',
                    value: 2,
                    label: translations.credits,
                    isChecked: false
                },
                {
                    id: 'quotes',
                    value: 4,
                    label: translations.quotes,
                    isChecked: false
                },
                {
                    id: 'products',
                    value: 4,
                    label: translations.products,
                    isChecked: false
                },
                {
                    id: 'leads',
                    value: 4,
                    label: translations.leads,
                    isChecked: false
                },
                {
                    id: 'events',
                    value: 4,
                    label: translations.events,
                    isChecked: false
                },
                {
                    id: 'deals',
                    value: 4,
                    label: translations.deals,
                    isChecked: false
                },
                { id: 'tasks', value: 8, label: 'Tasks', isChecked: false },
                {
                    id: 'expenses',
                    value: 16,
                    label: translations.expenses,
                    isChecked: false
                },
                {
                    id: 'projects',
                    value: 32,
                    label: translations.projects,
                    isChecked: false
                },
                {
                    id: 'companies',
                    value: 64,
                    label: translations.companies,
                    isChecked: false
                },
                {
                    id: 'cases',
                    value: 128,
                    label: translations.cases,
                    isChecked: false
                },
                {
                    id: 'recurringExpenses',
                    value: 512,
                    label: translations.recurring_expenses,
                    isChecked: false
                },
                {
                    id: 'recurringTasks',
                    value: 1024,
                    label: 'Recurring Tasks',
                    isChecked: false
                },
                {
                    id: 'tasks',
                    value: 1024,
                    label: translations.tasks,
                    isChecked: false
                },
                {
                    id: 'payments',
                    value: 1024,
                    label: translations.payments,
                    isChecked: false
                },
                {
                    id: 'invoices',
                    value: 1024,
                    label: translations.invoices,
                    isChecked: false
                },
                {
                    id: 'orders',
                    value: 2000,
                    label: translations.orders,
                    isChecked: false
                }
            ]
        }

        this.deleteAccount = this.deleteAccount.bind(this)
        this.customInputSwitched = this.customInputSwitched.bind(this)
        this.handleAllChecked = this.handleAllChecked.bind(this)
        this.toggleTab = this.toggleTab.bind(this)
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    deleteAccount () {
        const url = `/api/account/${this.state.id}`
        axios.delete(url)
            .then((r) => {
                this.setState({
                    showConfirm: false
                })
                alert('The account has been deleted')
                location.href = '/Login#/login'
            })
            .catch((e) => {
                toast.error('There was an issue updating the settings')
            })
    }

    handleAllChecked (event) {
        const modules = this.state.modules
        Object.keys(modules).forEach(module => modules[module] = event.target.checked)
        this.setState({ modules: modules }, () => localStorage.setItem('modules', JSON.stringify(this.state.modules)))
    }

    customInputSwitched (buttonName, e) {
        const name = e.target.id
        const checked = e.target.checked

        this.setState(prevState => ({
            modules: {
                ...prevState.modules,
                [name]: checked
            }
        }), () => localStorage.setItem('modules', JSON.stringify(this.state.modules)))
    }

    render () {
        return (
            <React.Fragment>
                <div className="topbar">
                    <Card className="m-0">
                        <CardBody className="p-0">
                            <div className="d-flex justify-content-between align-items-center">
                                <h4 className="pl-3 pt-2">{translations.account_management}</h4>
                                <a className="pull-right pr-3">{translations.save}</a>
                            </div>
                            <Nav tabs className="setting-tabs disable-scrollbars">
                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '1' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggleTab('1')
                                        }}>
                                        {translations.overview}
                                    </NavLink>
                                </NavItem>

                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '2' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggleTab('2')
                                        }}>
                                        {translations.enable_modules}
                                    </NavLink>
                                </NavItem>
                            </Nav>
                        </CardBody>
                    </Card>
                </div>

                <TabContent className="fixed-margin-mobile bg-transparent" activeTab={this.state.activeTab}>
                    <TabPane tabId="1" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <BlockButton icon={icons.link} button_text={translations.subscriptions}
                                    button_link="/#/subscriptions"/>
                                <BlockButton icon={icons.token} button_text={translations.tokens}
                                    button_link="/#/tokens"/>

                                <Button onClick={() => this.setState({ showConfirm: true })} color="danger" size="lg"
                                    block>
                                    <i style={{ marginRight: '14px', fontSize: '24px' }}
                                        className={`fa ${icons.delete}`}/>{translations.delete_account}</Button>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="2" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <Form>
                                    <FormGroup>
                                        <Label for="exampleCheckbox">Switches <input type="checkbox"
                                            onClick={this.handleAllChecked}/>Check
                                            all </Label>
                                        {this.state.moduleTypes.map((module, index) => {
                                            const isChecked = this.state.modules[module.id]

                                            return (
                                                <div key={index}>
                                                    <CustomInput
                                                        checked={isChecked}
                                                        type="switch"
                                                        id={module.id}
                                                        name="customSwitch"
                                                        label={module.label}
                                                        onChange={this.customInputSwitched.bind(this, module.value)}
                                                    />
                                                </div>
                                            )
                                        }
                                        )}
                                    </FormGroup>
                                </Form>
                            </CardBody>
                        </Card>
                    </TabPane>
                </TabContent>

                <Modal isOpen={this.state.showConfirm} fade="false"
                    toggle={() => this.setState({ showConfirm: false })}>
                    <ModalHeader toggle={() => this.setState({ showConfirm: false })}>Are you sure?</ModalHeader>
                    <ModalBody>
                        {translations.delete_company_message}
                    </ModalBody>
                    <ModalFooter>

                        <Button onClick={() => this.setState({ showConfirm: false })}>Cancel</Button>
                        <Button onClick={this.deleteAccount} color="danger">Delete</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>

        )
    }
}

export default ModuleSettings
