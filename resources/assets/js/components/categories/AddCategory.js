import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label, CustomInput } from 'reactstrap'
import axios from 'axios'
import AddButtons from '../common/AddButtons'
import { translations } from '../common/_icons'

class AddCategory extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            name: '',
            parent: 0,
            description: '',
            status: 1,
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

        axios.post('/api/categories', formData)
            .then((response) => {
                this.toggle()
                const newUser = response.data
                this.props.categories.push(newUser)
                this.props.action(this.props.categories)
                this.setState({
                    name: null,
                    description: null
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
                <Label for="gender">Parent:</Label>
                <Input className={this.hasErrorFor('parent') ? 'is-invalid' : ''}
                    type="select"
                    name="parent"
                    onChange={this.handleInput.bind(this)}>
                    <option value="">Select Parent</option>
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
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.add_category}
                    </ModalHeader>
                    <ModalBody>
                        <FormGroup>
                            <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                                id="name" placeholder={translations.name} onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('name')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="description">{translations.description} </Label>
                            <Input className={this.hasErrorFor('description') ? 'is-invalid' : ''} type="textarea"
                                name="description" id="description" rows="5"
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

export default AddCategory
