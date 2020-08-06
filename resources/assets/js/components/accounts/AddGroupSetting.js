import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label } from 'reactstrap'
import axios from 'axios'
import AddButtons from '../common/AddButtons'
import { translations } from '../common/_translations'
import DefaultModalHeader from '../common/ModalHeader'
import DefaultModalFooter from '../common/ModalFooter'

class AddGroupSetting extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            name: '',
            loading: false,
            errors: []
        }

        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'groupForm')) {
            const storedValues = JSON.parse(localStorage.getItem('groupForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value
        }, () => localStorage.setItem('groupForm', JSON.stringify(this.state)))
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
        axios.post('/api/groups', {
            name: this.state.name
        })
            .then((response) => {
                const newUser = response.data
                this.props.groups.push(newUser)
                this.props.action(this.props.groups)
                localStorage.removeItem('groupForm')
                this.setState({
                    name: null
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
                    name: null,
                    icon: null
                }, () => localStorage.removeItem('groupForm'))
            }
        })
    }

    render () {
        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <DefaultModalHeader toggle={this.toggle} title={translations.add_group} />
                    <ModalBody>
                        <FormGroup>
                            <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                                id="name" value={this.state.name} placeholder={translations.name}
                                onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('name')}
                        </FormGroup>
                    </ModalBody>

                    <DefaultModalFooter show_success={true} toggle={this.toggle} saveData={this.handleClick.bind(this)}
                        loading={false}/>
                </Modal>
            </React.Fragment>
        )
    }
}

export default AddGroupSetting
