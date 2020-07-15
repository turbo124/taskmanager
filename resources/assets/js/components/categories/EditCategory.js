import React from 'react'
import {
    Button,
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    DropdownItem
} from 'reactstrap'
import axios from 'axios'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import Details from './Details'

class EditCategory extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            name: this.props.category.name,
            description: this.props.category.description,
            status: this.props.category.status,
            id: this.props.category.id,
            parent: this.props.category.parent_id,
            loading: false,
            errors: []
        }

        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.handleFileChange = this.handleFileChange.bind(this)
        this.handleInput = this.handleInput.bind(this)
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
        const formData = new FormData()
        formData.append('cover', this.state.cover)
        formData.append('parent', this.state.parent)
        formData.append('name', this.state.name)
        formData.append('description', this.state.description)
        formData.append('status', this.state.status)
        formData.append('_method', 'PUT')

        axios.post(`/api/categories/${this.state.id}`, formData)
            .then((response) => {
                this.toggle()
                const index = this.props.categories.findIndex(category => category.id === this.state.id)
                this.props.categories[index].name = this.state.name
                this.props.categories[index].description = this.state.description
                this.props.action(this.props.categories)
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
        })
    }

    render () {
        return (
            <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_category}
                </DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.edit_category}
                    </ModalHeader>
                    <ModalBody>
                        <Details categories={this.props.categories} category={this.state} hasErrorFor={this.hasErrorFor}
                            handleInput={this.handleInput}
                            renderErrorFor={this.renderErrorFor} handleFileChange={this.handleFileChange}/>
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

export default EditCategory
