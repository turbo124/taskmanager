import React from 'react'
import {
    Card,
    CardBody,
    CardHeader,
    Col,
    FormGroup,
    Input,
    Label,
    Nav,
    NavItem,
    NavLink,
    Progress,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import axios from 'axios'
import DesignDropdown from '../common/dropdowns/DesignDropdown'
import { translations } from '../utils/_translations'
import Variables from '../settings/Variables'

class Designs extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            loaded: 0,
            is_loading: false,
            is_mobile: window.innerWidth <= 768,
            modal: false,
            name: '',
            id: null,
            is_custom: true,
            design: {
                header: '',
                body: '',
                footer: '',
                // includes: '',
                product: '',
                task: ''
            },
            obj_url: null,
            activeTab: '1',
            loading: false,
            errors: []
        }

        this.toggleTabs = this.toggleTabs.bind(this)
        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.getPreview = this.getPreview.bind(this)
        this.switchDesign = this.switchDesign.bind(this)
        this.resetCounters = this.resetCounters.bind(this)
        this.update = this.update.bind(this)
        this.save = this.save.bind(this)
        this.handleWindowSizeChange = this.handleWindowSizeChange.bind(this)
    }

    componentWillMount () {
        window.addEventListener('resize', this.handleWindowSizeChange)
    }

    // make sure to remove the listener
    // when the component is not mounted anymore
    componentWillUnmount () {
        window.removeEventListener('resize', this.handleWindowSizeChange)
    }

    handleWindowSizeChange () {
        this.setState({ is_mobile: window.innerWidth <= 768 })
    }

    componentDidMount () {
        if (localStorage.hasOwnProperty('designForm')) {
            const storedValues = JSON.parse(localStorage.getItem('designForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    toggleTabs (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab }, () => {
                if (this.state.activeTab === '2') {
                    if (this.state.is_mobile) {
                        this.getPreview()
                    }
                }
            })
        }
    }

    handleChange (el) {
        const inputName = el.target.name
        const inputValue = el.target.value

        const statusCopy = Object.assign({}, this.state)
        statusCopy.design[inputName].value = inputValue

        this.setState(statusCopy)
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value
        }, () => localStorage.setItem('designForm', JSON.stringify(this.state)))
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

    getFormData () {
        return {
            name: this.state.name,
            design: this.state.design
        }
    }

    save () {
        axios.post('/api/designs', this.getFormData())
            .then((response) => {
                const newUser = response.data
                this.props.designs.push(newUser)
                this.props.action(this.props.designs)
                localStorage.removeItem('designForm')
                this.setState({
                    name: null
                })
                // this.toggle ()
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    update () {
        axios.put(`/api/designs/${this.state.id}`, this.getFormData())
            .then((response) => {
                const index = this.props.designs.findIndex(design => design.id === parseInt(this.state.id))
                this.props.designs[index] = response.data
                this.props.action(this.props.designs)
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    handleClick () {
        if (this.state.id !== null) {
            this.update()
            return
        }

        this.save()
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState({
                    name: null,
                    icon: null
                }, () => localStorage.removeItem('designForm'))
            }
        })
    }

    getPreview () {
        console.log('header', this.state.design.header)
        const design = {
            name: this.state.name,
            is_custom: this.state.is_custom,
            design: {
                body: this.state.design.body,
                header: this.state.design.header,
                footer: this.state.design.footer,
                // includes: this.state.design.includes,
                table: this.state.design.table,
                totals: this.state.design.totals,
                product: '',
                task: ''
            }
        }
        axios.post('/api/preview', {
            design: design
        }, {
            onUploadProgress: ProgressEvent => {
                this.setState({
                    loaded: (ProgressEvent.loaded / ProgressEvent.total * 100)
                })
            }
        })
            .then((response) => {
                console.log('respons', response.data.data)
                var base64str = response.data.data

                // decode base64 string, remove space for IE compatibility
                var binary = atob(base64str.replace(/\s/g, ''))
                var len = binary.length
                var buffer = new ArrayBuffer(len)
                var view = new Uint8Array(buffer)
                for (var i = 0; i < len; i++) {
                    view[i] = binary.charCodeAt(i)
                }

                // create the blob object with content-type "application/pdf"
                var blob = new Blob([view], { type: 'application/pdf' })
                var url = URL.createObjectURL(blob)

                /* const file = new Blob (
                 [ response.data.data ],
                 { type: 'application/pdf' } ) */
                // const fileURL = URL.createObjectURL ( file )

                this.setState({ loaded: 0, obj_url: url, is_loading: false }, () => URL.revokeObjectURL(url))
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    resetCounters () {
        this.setState({
            name: '',
            id: null,
            design: { header: '', body: '', footer: '' },
            obj_url: null,
            is_custom: true
        })
    }

    switchDesign (design) {
        this.setState({
            design: design[0].design,
            name: design[0].name,
            id: design[0].id,
            is_custom: false
        }, () => {
            if (!this.state.is_mobile) {
                this.getPreview()
            }
        })
    }

    render () {
        console.log('body', this.state.design.body)
        const title = this.state.is_custom === true ? <FormGroup>
            <Label for="name">Name <span className="text-danger">*</span></Label>
            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                id="name" value={this.state.name} placeholder="Name"
                onChange={this.handleInput.bind(this)}/>
            {this.renderErrorFor('name')}
        </FormGroup> : <FormGroup>
            <Label for="name">Name <span className="text-danger">*</span></Label>
            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                id="name" disabled="disabled" value={this.state.name} placeholder="Name"
                onChange={this.handleInput.bind(this)}/>
            {this.renderErrorFor('name')}
        </FormGroup>
        return (
            <React.Fragment>
                <link rel="stylesheet" type="text/css" href="public/css/pdf.css"/>

                <Row>
                    <Col md={6}>
                        <Nav tabs>
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '1' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTabs('1')
                                    }}>
                                    {translations.settings}
                                </NavLink>
                            </NavItem>

                            {!!this.state.is_mobile &&
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '2' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTabs('2')
                                    }}>
                                    {translations.preview}
                                </NavLink>
                            </NavItem>
                            }

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '3' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTabs('3')
                                    }}>
                                    {translations.header}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '4' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTabs('4')
                                    }}>
                                    {translations.body}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '5' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTabs('5')
                                    }}>
                                    {translations.total}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '6' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTabs('6')
                                    }}>
                                    {translations.footer}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '7' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTabs('7')
                                    }}>
                                    {translations.product}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '8' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTabs('8')
                                    }}>
                                    {translations.task}
                                </NavLink>
                            </NavItem>
                        </Nav>

                        <TabContent activeTab={this.state.activeTab}>
                            <TabPane tabId="1">
                                <Card>
                                    <CardHeader>{this.state.template_type}</CardHeader>
                                    <CardBody>
                                        {title}

                                        <FormGroup>
                                            <Label for="name">Design <span className="text-danger">*</span></Label>
                                            <DesignDropdown resetCounters={this.resetCounters}
                                                handleInputChanges={this.switchDesign}/>
                                        </FormGroup>
                                    </CardBody>
                                </Card>

                                <Card className="border-0">
                                    <CardBody>
                                        <Row>
                                            <Col sm={12}>
                                                <Variables class="fixed-margin-mobile"/>
                                            </Col>
                                        </Row>
                                    </CardBody>
                                </Card>
                            </TabPane>

                            {!!this.state.is_mobile &&
                            <TabPane tabId="2">
                                <Card>
                                    <CardHeader>{translations.preview}</CardHeader>
                                    <CardBody>
                                        <div className="embed-responsive embed-responsive-21by9">
                                            <iframe className="embed-responsive-item" id="viewer"
                                                src={this.state.obj_url}/>
                                        </div>
                                    </CardBody>
                                </Card>
                            </TabPane>
                            }

                            <TabPane tabId="3">
                                <Card>
                                    <CardHeader>{translations.header}</CardHeader>
                                    <CardBody>

                                        <FormGroup>
                                            <Label for="name">{translations.header} <span
                                                className="text-danger">*</span></Label>
                                            <Input type="textarea" style={{ height: '400px' }} size="lg"
                                                value={this.state.design.header}
                                                onChange={(e) => {
                                                    const value = e.target.value
                                                    this.setState(prevState => ({
                                                        design: { // object that we want to update
                                                            ...prevState.design, // keep all other key-value pairs
                                                            header: value // update the value of specific key
                                                        }
                                                    }), () => {
                                                        if (!this.state.is_loading && !this.state.is_mobile) {
                                                            this.setState({ is_loading: true })
                                                            setTimeout(() => {
                                                                this.getPreview()
                                                            }, 1000)
                                                        }
                                                    })
                                                }}
                                            />
                                        </FormGroup>
                                    </CardBody>
                                </Card>
                            </TabPane>

                            <TabPane tabId="4">
                                <Card>
                                    <CardHeader>{translations.body}</CardHeader>
                                    <CardBody>
                                        <FormGroup>
                                            <Label for="name">{translations.body} <span className="text-danger">*</span></Label>
                                            <Input type="textarea" style={{ height: '400px' }} size="lg"
                                                value={this.state.design.body}
                                                onChange={(e) => {
                                                    const value = e.target.value
                                                    this.setState(prevState => ({
                                                        design: { // object that we want to update
                                                            ...prevState.design, // keep all other key-value pairs
                                                            body: value // update the value of specific key
                                                        }
                                                    }), () => {
                                                        if (!this.state.is_loading && !this.state.is_mobile) {
                                                            this.setState({ is_loading: true, obj_url: '' })
                                                            setTimeout(() => {
                                                                this.getPreview()
                                                            }, 2000)
                                                        }
                                                    })
                                                }}
                                            />
                                        </FormGroup>
                                    </CardBody>
                                </Card>
                            </TabPane>

                            <TabPane tabId="5">
                                <Card>
                                    <CardHeader>{translations.total}</CardHeader>
                                    <CardBody>
                                        <FormGroup>
                                            <Label for="name">{translations.total} <span className="text-danger">*</span></Label>
                                            <Input type="textarea" style={{ height: '400px' }} size="lg"
                                                value={this.state.design.totals}
                                                onChange={(e) => {
                                                    const value = e.target.value
                                                    this.setState(prevState => ({
                                                        design: { // object that we want to update
                                                            ...prevState.design, // keep all other key-value pairs
                                                            totals: value // update the value of specific key
                                                        }
                                                    }), () => {
                                                        if (!this.state.is_loading && !this.state.is_mobile) {
                                                            this.setState({ is_loading: true, obj_url: '' })
                                                            setTimeout(() => {
                                                                this.getPreview()
                                                            }, 2000)
                                                        }
                                                    })
                                                }}
                                            />
                                        </FormGroup>
                                    </CardBody>
                                </Card>
                            </TabPane>

                            <TabPane tabId="6">
                                <Card>
                                    <CardHeader>{translations.footer}</CardHeader>
                                    <CardBody>
                                        <FormGroup>
                                            <Label for="name">{translations.footer} <span
                                                className="text-danger">*</span></Label>
                                            <Input type="textarea" style={{ height: '400px' }} size="lg"
                                                value={this.state.design.footer}
                                                onChange={(e) => {
                                                    const value = e.target.value
                                                    this.setState(prevState => ({
                                                        design: { // object that we want to update
                                                            ...prevState.design, // keep all other key-value pairs
                                                            footer: value // update the value of specific key
                                                        }
                                                    }), () => {
                                                        if (!this.state.is_loading && !this.state.is_mobile) {
                                                            this.setState({ is_loading: true, obj_url: '' })
                                                            setTimeout(() => {
                                                                this.getPreview()
                                                            }, 2000)
                                                        }
                                                    })
                                                }}
                                            />
                                        </FormGroup>
                                    </CardBody>
                                </Card>
                            </TabPane>

                            <TabPane tabId="7">
                                <Card>
                                    <CardHeader>{translations.product}</CardHeader>
                                    <CardBody/>
                                </Card>
                            </TabPane>

                            <TabPane tabId="8">
                                <Card>
                                    <CardHeader>{translations.task}</CardHeader>
                                    <CardBody/>
                                </Card>
                            </TabPane>
                        </TabContent>
                    </Col>

                    {!this.state.is_mobile &&
                    <Col md={6}>
                        {this.state.loaded > 0 &&
                        <Progress max="100" color="success"
                            value={this.state.loaded}>{Math.round(this.state.loaded, 2)}%</Progress>
                        }

                        <div style={{ minHeight: '600px' }} className="embed-responsive embed-responsive-21by9">
                            <iframe className="embed-responsive-item" id="viewer" src={this.state.obj_url}/>
                        </div>
                    </Col>
                    }
                </Row>

            </React.Fragment>
        )
    }
}

export default Designs
