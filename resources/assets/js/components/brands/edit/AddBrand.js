import React from 'react'
import { Modal, ModalBody } from 'reactstrap'
import axios from 'axios'
import AddButtons from '../../common/AddButtons'
import { translations } from '../../common/_translations'
import Details from './Details'
import DefaultModalHeader from '../../common/ModalHeader'
import DefaultModalFooter from '../../common/ModalFooter'

class AddBrand extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            name: '',
            description: '',
            status: 1,
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
        formData.append('name', this.state.name)
        formData.append('description', this.state.description)
        formData.append('status', this.state.status)

        axios.post('/api/brands', formData)
            .then((response) => {
                this.toggle()
                const newUser = response.data
                this.props.brands.push(newUser)
                this.props.action(this.props.brands)
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

    render () {
        const theme = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme'

        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <DefaultModalHeader toggle={this.toggle} title={translations.add_brand}/>

                    <ModalBody className={theme}>
                        <Details brand={this.state} hasErrorFor={this.hasErrorFor} handleInput={this.handleInput}
                            renderErrorFor={this.renderErrorFor} handleFileChange={this.handleFileChange}/>
                    </ModalBody>

                    <DefaultModalFooter show_success={true} toggle={this.toggle}
                        saveData={this.handleClick.bind(this)}
                        loading={false}/>
                </Modal>
            </React.Fragment>
        )
    }
}

export default AddBrand
