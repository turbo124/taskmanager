import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label } from 'reactstrap'
import axios from 'axios'
import AddButtons from '../common/AddButtons'
import { translations } from '../common/_translations'
import DefaultModalHeader from '../common/ModalHeader'
import DefaultModalFooter from '../common/ModalFooter'

class AddCategory extends React.Component {
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
        this.buildParentOptions = this.buildParentOptions.bind(this)
        this.handleFileChange = this.handleFileChange.bind(this)
    }

    handleFileChange (e) {
        this.setState({
            [e.target.name]: e.target.files[0]
        })
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value
        })
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
        axios.post('/api/expense-categories', { name: this.state.name })
            .then((response) => {
                this.toggle()
                const newUser = response.data
                this.props.categories.push(newUser)
                this.props.action(this.props.categories)
                this.setState({
                    name: null
                })
            })
            .catch((error) => {
                alert(error)
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        })
    }

    render () {
        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <DefaultModalHeader toggle={this.toggle} title={translations.add_category} />

                    <ModalBody>
                        <FormGroup>
                            <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                                id="name" placeholder={translations.name} onChange={this.handleInput.bind(this)}/>
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

export default AddCategory
