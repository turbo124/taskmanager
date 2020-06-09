import React from 'react'
import {
    Button,
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    Input,
    FormGroup,
    Label,
    CustomInput,
    DropdownItem
} from 'reactstrap'
import axios from 'axios'
import { icons, translations } from "../common/_icons";

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

    buildParentOptions () {
        let categoryList
        if (!this.props.categories.length) {
            categoryList = <option value="">Loading...</option>
        } else {
            categoryList = this.props.categories.map((category, index) => (
                <option key={index} value={category.id}>{category.name}</option>
            ))
        }

        return (
            <FormGroup>
                <Label for="gender">{translations.parent}:</Label>
                <Input className={this.hasErrorFor('parent') ? 'is-invalid' : ''}
                    value={this.state.parent}
                    type="select"
                    name="parent"
                    onChange={this.handleInput.bind(this)}>
                    <option value="">{translations.select_option}</option>
                    {categoryList}
                </Input>
                {this.renderErrorFor('parent')}
            </FormGroup>
        )
    }

    render () {
        const parentDropdown = this.buildParentOptions()

        return (
            <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_category}</DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.edit_category}
                    </ModalHeader>
                    <ModalBody>
                        <FormGroup>
                            <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''}
                                value={this.state.name}
                                type="text"
                                name="name"
                                id="name"
                                placeholder={translations.name} onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('name')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="description">{translations.description} </Label>
                            <Input className={this.hasErrorFor('description') ? 'is-invalid' : ''}
                                value={this.state.description}
                                type="textarea"
                                name="description"
                                id="description" rows="5"
                                placeholder={translations.description} onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('description')}
                        </FormGroup>

                        {parentDropdown}

                        <FormGroup>
                            <Label>{translations.cover}</Label>
                            <CustomInput onChange={this.handleFileChange} type="file" id="cover"
                                name="cover"
                                label="Cover!"/>
                        </FormGroup>

                        <FormGroup>
                            <Label for="status">{translations.status} </Label>
                            <Input className={this.hasErrorFor('status') ? 'is-invalid' : ''} type="select"
                                value={this.state.status}
                                name="status"
                                id="status"
                                onChange={this.handleInput.bind(this)}
                            >
                                <option value="0">{translations.disable}</option>
                                <option value="1">{translations.enable}</option>
                            </Input>
                            {this.renderErrorFor('status')}
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

export default EditCategory
