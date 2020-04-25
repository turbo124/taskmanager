import React, { Component } from 'react'
import axios from 'axios'

import {
    Button,
    Card,
    CardBody,
    CardGroup,
    Col,
    Container,
    Form,
    Input,
    InputGroup,
    InputGroupAddon,
    InputGroupText,
    Row
} from 'reactstrap'

class Login extends Component {
    constructor (props) {
        super(props)
        this.state = {
            email: '',
            password: '',
            error: ''
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.dismissError = this.dismissError.bind(this)
    }

    dismissError () {
        this.setState({ error: '' })
    }

    handleSubmit (evt) {
        evt.preventDefault()

        if (!this.state.email) {
            return this.setState({ error: 'Email is required' })
        }

        if (!this.state.password) {
            return this.setState({ error: 'Password is required' })
        }

        axios.post('/api/login', {
            email: this.state.email,
            password: this.state.password
        })
            .then((response) => {
                if (response.data.success === true) {
                    const userData = {
                        name: response.data.data.name,
                        id: response.data.data.id,
                        email: response.data.data.email,
                        account_id: response.data.data.account_id,
                        auth_token: response.data.data.auth_token,
                        timestamp: new Date().toString()
                    }

                    const appState = {
                        isLoggedIn: true,
                        user: userData,
                        accounts: response.data.data.accounts
                    }

                    window.sessionStorage.setItem('authenticated', true)

                    // save app state with user date in local storage
                    localStorage.appState = JSON.stringify(appState)
                    localStorage.setItem('access_token', userData.auth_token)
                    localStorage.setItem('account_id', response.data.data.account_id)
                    this.setState({
                        isLoggedIn: appState.isLoggedIn,
                        user: appState.user
                    })
                    window.location.href = '/'
                } else {
                    return this.setState({ error: 'Unable to log in' })
                }
            })
    }

    handleChange (e) {
        this.setState({
            [e.target.name]: e.target.value
        })
    }

    render () {
        return (
            <div className="app flex-row align-items-center">
                <Container>
                    <Row className="justify-content-center">
                        <Col md="8">
                            <CardGroup>
                                <Card className="p-4">
                                    <CardBody>
                                        <Form onSubmit={this.handleSubmit}>

                                            {
                                                this.state.error &&
                                                <div className="alert alert-danger alert-dismissible" data-test="error"
                                                    onClick={this.dismissError}>
                                                    <button type="button" className="close" aria-label="Close"><span
                                                        aria-hidden="true">×</span></button>
                                                    {this.state.error}
                                                </div>
                                            }

                                            <h1>Login</h1>
                                            <p className="text-muted">Sign In to your account</p>
                                            <InputGroup className="mb-3">
                                                <InputGroupAddon addonType="prepend">
                                                    <InputGroupText>
                                                        <i className="icon-user" />
                                                    </InputGroupText>
                                                </InputGroupAddon>
                                                <Input type="text" name="email" placeholder="Email"
                                                    autoComplete="email" onChange={this.handleChange.bind(this)}/>
                                            </InputGroup>
                                            <InputGroup className="mb-4">
                                                <InputGroupAddon addonType="prepend">
                                                    <InputGroupText>
                                                        <i className="icon-lock" />
                                                    </InputGroupText>
                                                </InputGroupAddon>
                                                <Input type="password" name="password" placeholder="Password"
                                                    autoComplete="current-password"
                                                    onChange={this.handleChange.bind(this)}/>
                                            </InputGroup>
                                            <Row>
                                                <Col xs="6">
                                                    <Button type="submit" color="primary"
                                                        className="px-4">Login</Button>
                                                </Col>
                                                <Col xs="6" className="text-right">
                                                    <Button color="link" className="px-0">Forgot password?</Button>
                                                </Col>
                                            </Row>
                                        </Form>
                                    </CardBody>
                                </Card>
                            </CardGroup>
                        </Col>
                    </Row>
                </Container>
            </div>
        )
    }
}

export default Login
